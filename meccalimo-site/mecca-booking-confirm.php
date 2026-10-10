<?php
/**
 * Mecca Limo: private "confirm your booking" links.
 *
 * Staff create a link in WP admin (Mecca Bookings → New booking link) with the
 * customer and trip details. The customer opens /confirm/?t=TOKEN, saves a card
 * on file through Square's hosted card field (card numbers never touch this
 * server), uploads a photo of their ID, accepts the terms and signs.
 *
 * Nothing is charged here. Square stores the card on the customer's profile.
 * ID photos are stored outside public access and only admins can view them.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MECCA_BC_SQUARE_VERSION = '2024-10-17';

function mecca_bc_opt( $k, $d = '' ) {
	$o = (array) get_option( 'mecca_bc_settings', array() );
	return isset( $o[ $k ] ) && '' !== $o[ $k ] ? $o[ $k ] : $d;
}

function mecca_bc_id_dir() {
	$dir = WP_CONTENT_DIR . '/uploads/mecca-ids';
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}
	if ( ! file_exists( $dir . '/.htaccess' ) ) {
		file_put_contents( $dir . '/.htaccess', "Require all denied\nDeny from all\n" );
		file_put_contents( $dir . '/index.html', '' );
	}
	return $dir;
}

add_action( 'init', function () {
	register_post_type( 'mecca_booking', array(
		'label'           => 'Bookings',
		'public'          => false,
		'show_ui'         => false,
		'supports'        => array( 'title' ),
		'capability_type' => 'post',
	) );
} );


/** Create a booking link from raw form fields. Returns array( id, link, details ). */
function mecca_bc_create( $in ) {
	$f = array();
	foreach ( array( 'first_name', 'last_name', 'email', 'phone', 'trip_date', 'pickup', 'dropoff', 'vehicle', 'price', 'notes' ) as $k ) {
		$f[ $k ] = 'notes' === $k ? sanitize_textarea_field( $in[ $k ] ?? '' ) : sanitize_text_field( $in[ $k ] ?? '' );
	}
	$token = wp_generate_password( 32, false, false );
	$id    = wp_insert_post( array(
		'post_type'   => 'mecca_booking',
		'post_status' => 'publish',
		'post_title'  => trim( $f['first_name'] . ' ' . $f['last_name'] ) . ' — ' . $f['trip_date'],
	) );
	update_post_meta( $id, '_bc_token', $token );
	update_post_meta( $id, '_bc_details', $f );
	update_post_meta( $id, '_bc_status', 'sent' );
	return array( 'id' => $id, 'link' => home_url( '/confirm/?t=' . $token ), 'details' => $f );
}

/** Update an unconfirmed booking's details; the customer's link stays the same. */
function mecca_bc_update( $id, $in ) {
	$f = array();
	foreach ( array( 'first_name', 'last_name', 'email', 'phone', 'trip_date', 'pickup', 'dropoff', 'vehicle', 'price', 'notes' ) as $k ) {
		$f[ $k ] = 'notes' === $k ? sanitize_textarea_field( $in[ $k ] ?? '' ) : sanitize_text_field( $in[ $k ] ?? '' );
	}
	wp_update_post( array( 'ID' => $id, 'post_title' => trim( $f['first_name'] . ' ' . $f['last_name'] ) . ' — ' . $f['trip_date'] ) );
	update_post_meta( $id, '_bc_details', $f );
	return array( 'id' => $id, 'link' => home_url( '/confirm/?t=' . get_post_meta( $id, '_bc_token', true ) ), 'details' => $f, 'updated' => true );
}

/**
 * Link to a booking in the dashboard that also works when logged out: the site hides
 * /wp-admin behind a custom login page (WPS Hide Login), so go through that page first.
 */
function mecca_bc_admin_link( $id ) {
	$dest  = admin_url( 'admin.php?page=mecca-bookings&view_id=' . (int) $id );
	$login = get_option( 'whl_page' ) ? home_url( '/' . get_option( 'whl_page' ) . '/' ) : wp_login_url();
	return add_query_arg( 'redirect_to', rawurlencode( $dest ), $login );
}

/** Secret key for the phone staff page (/new-booking/?k=KEY). */
function mecca_bc_staff_key() {
	$k = get_option( 'mecca_bc_staff_key' );
	if ( ! $k ) {
		$k = wp_generate_password( 24, false, false );
		update_option( 'mecca_bc_staff_key', $k, false );
	}
	return $k;
}

function mecca_bc_staff_url() {
	return home_url( '/new-booking/?k=' . mecca_bc_staff_key() );
}

/** Pre-written customer message for SMS/email. */
function mecca_bc_message( $d, $link ) {
	return 'Hi ' . ( $d['first_name'] ?? '' ) . ', this is Mecca Limo. Thanks for booking with us! Please confirm your ride (' . ( $d['trip_date'] ?? '' ) . ') here: ' . $link . ' It takes 2 minutes. Your card is saved securely and nothing is charged today. Questions? Call or text (843) 804-1188.';
}

/* ---------------------------------------------------------------- Phone staff page */

add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	if ( 'new-booking' !== $path ) {
		return;
	}
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow' );
	$key = (string) ( $_REQUEST['k'] ?? '' );
	if ( ! hash_equals( mecca_bc_staff_key(), $key ) ) {
		status_header( 404 );
		echo '<!doctype html><meta name="viewport" content="width=device-width,initial-scale=1"><p style="font:16px sans-serif;padding:24px">Not found.</p>';
		exit;
	}
	status_header( 200 );
	$made = null;
	// ?from=ID opens a quote request OR a not-yet-confirmed booking, pre-filled, so it can be fixed and re-sent.
	$from = (int) ( $_REQUEST['from'] ?? 0 );
	$pre  = array();
	$fst  = $from ? get_post_meta( $from, '_bc_status', true ) : '';
	if ( $from && in_array( $fst, array( 'quote', 'sent' ), true ) && 'mecca_booking' === get_post_type( $from ) ) {
		$pre    = (array) get_post_meta( $from, '_bc_details', true );
		$linked = 'quote' === $fst ? (int) get_post_meta( $from, '_bc_linked', true ) : 0;
		if ( $linked && 'sent' === get_post_meta( $linked, '_bc_status', true ) ) {
			$pre = (array) get_post_meta( $linked, '_bc_details', true ); // Show what was last sent, including the price.
		}
	} else {
		$from = 0;
	}
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && wp_verify_nonce( $_POST['_sn'] ?? '', 'mecca_bc_staff' ) ) {
		$target = 0;
		if ( $from && 'sent' === $fst ) {
			$target = $from; // Editing an unconfirmed link.
		} elseif ( $from && 'quote' === $fst ) {
			$linked = (int) get_post_meta( $from, '_bc_linked', true );
			if ( $linked && 'sent' === get_post_meta( $linked, '_bc_status', true ) ) {
				$target = $linked; // Quote already has an unconfirmed link: update it instead of making another.
			}
		}
		if ( $target ) {
			$made = mecca_bc_update( $target, wp_unslash( $_POST ) );
		} else {
			$made = mecca_bc_create( wp_unslash( $_POST ) );
		}
		if ( $from && 'quote' === $fst ) {
			update_post_meta( $from, '_bc_linked', $made['id'] ); // Keep the quote so it can be reopened.
		}
	}
	$quotes = get_posts( array( 'post_type' => 'mecca_booking', 'numberposts' => 10, 'post_status' => 'publish', 'meta_query' => array( array( 'key' => '_bc_status', 'value' => 'quote' ), array( 'key' => '_bc_linked', 'compare' => 'NOT EXISTS' ) ) ) );
	$recent = get_posts( array( 'post_type' => 'mecca_booking', 'numberposts' => 8, 'post_status' => 'publish', 'meta_query' => array( array( 'key' => '_bc_status', 'value' => 'quote', 'compare' => '!=' ) ) ) );
	$v      = function ( $k ) use ( $pre ) {
		return esc_attr( $pre[ $k ] ?? '' );
	};
	$self   = mecca_bc_staff_url();
	?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-title" content="Mecca Book">
<link rel="apple-touch-icon" href="<?php echo esc_url( home_url( '/wp-content/plugins/mecca-home/assets/icon-192.png' ) ); ?>">
<title>Mecca Book</title>
<style>
:root{--gold:#c9a45c;--bg:#0e0e10;--card:#17171a;--line:#2a2a2f;--text:#f2efe8;--muted:#a9a6a0}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:16px/1.45 -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif}
.wrap{max-width:520px;margin:0 auto;padding:16px 16px 40px}h1{font:600 22px Georgia,serif;margin:6px 0 14px;color:var(--gold)}
label{display:block;font-size:13px;color:var(--muted);margin:10px 0 4px}input,select,textarea{width:100%;padding:12px;border-radius:8px;border:1px solid var(--line);background:#0f0f12;color:var(--text);font-size:16px}
.two{display:flex;gap:10px}.two>div{flex:1}button,.btn{display:block;width:100%;text-align:center;padding:14px;border:0;border-radius:10px;background:var(--gold);color:#111;font-size:17px;font-weight:700;margin-top:14px;text-decoration:none}
.btn.alt{background:#24242a;color:var(--text);border:1px solid var(--line)}.box{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:14px;margin:0 0 16px}
.link{word-break:break-all;font-size:14px;color:var(--gold)}.list a{color:var(--text);text-decoration:none}.list div{padding:8px 0;border-bottom:1px solid var(--line);font-size:14px}.ok{color:#5fd38a}.muted{color:var(--muted)}
</style></head><body><div class="wrap">
<?php if ( $made ) :
		$msg = mecca_bc_message( $made['details'], $made['link'] );
		$ph  = preg_replace( '/[^0-9+]/', '', $made['details']['phone'] );
		?>
	<h1><?php echo ! empty( $made['updated'] ) ? 'Link updated ✓' : 'Link ready ✓'; ?></h1>
	<div class="box"><div class="muted" style="font-size:13px">For <?php echo esc_html( trim( $made['details']['first_name'] . ' ' . $made['details']['last_name'] ) ); ?></div><div class="link"><?php echo esc_html( $made['link'] ); ?></div></div>
	<a class="btn" href="sms:<?php echo esc_attr( $ph ); ?>?&amp;body=<?php echo rawurlencode( $msg ); ?>">💬 Text it</a>
	<?php if ( $made['details']['email'] ) : ?><a class="btn alt" href="mailto:<?php echo esc_attr( $made['details']['email'] ); ?>?subject=<?php echo rawurlencode( 'Confirm your Mecca Limo booking' ); ?>&amp;body=<?php echo rawurlencode( $msg ); ?>">✉️ Email it</a><?php endif; ?>
	<button type="button" class="btn alt" onclick="navigator.clipboard.writeText(<?php echo esc_attr( wp_json_encode( $made['link'] ) ); ?>);this.textContent='Copied ✓'">📋 Copy link</button>
	<a class="btn alt" href="<?php echo esc_url( $self ); ?>">+ New booking</a>
<?php else : ?>
	<h1>New booking link</h1>
	<?php if ( $from ) : ?><p class="muted" style="margin:-6px 0 6px"><?php echo 'sent' === $fst ? 'Editing this link. The customer\'s link stays the same; send it again after saving.' : 'Filled in from their quote request. Just add the price.'; ?></p><?php endif; ?>
	<form method="post" action="<?php echo esc_url( $from ? add_query_arg( 'from', $from, $self ) : $self ); ?>"><?php wp_nonce_field( 'mecca_bc_staff', '_sn' ); ?>
	<div class="two"><div><label>First name</label><input name="first_name" value="<?php echo $v( 'first_name' ); ?>" required autocomplete="off"></div><div><label>Last name</label><input name="last_name" value="<?php echo $v( 'last_name' ); ?>" required autocomplete="off"></div></div>
	<label>Phone</label><input name="phone" value="<?php echo $v( 'phone' ); ?>" type="tel" inputmode="tel" placeholder="843-555-1234">
	<label>Email (optional)</label><input name="email" value="<?php echo $v( 'email' ); ?>" type="email" inputmode="email">
	<label>Date &amp; time</label><input name="trip_date" value="<?php echo $v( 'trip_date' ); ?>" required placeholder="Sat, Oct 24 at 6:30 PM">
	<label>Pickup</label><input name="pickup" value="<?php echo $v( 'pickup' ); ?>" required>
	<label>Drop-off</label><input name="dropoff" value="<?php echo $v( 'dropoff' ); ?>">
	<div class="two"><div><label>Vehicle</label><select name="vehicle"><?php foreach ( array( 'Executive Sedan', 'Luxury SUV', 'Mercedes Sprinter' ) as $o ) { echo '<option' . selected( $pre['vehicle'] ?? '', $o, false ) . '>' . esc_html( $o ) . '</option>'; } ?></select></div><div><label>Price</label><input name="price" value="<?php echo $v( 'price' ); ?>" placeholder="$450 total"></div></div>
	<label>Notes for customer (optional)</label><textarea name="notes" rows="3"><?php echo esc_textarea( $pre['notes'] ?? '' ); ?></textarea>
	<button>Create link</button></form>
<?php endif; ?>
	<?php if ( $quotes && ! $made ) : ?>
	<div class="box list" style="margin-top:22px"><div class="muted" style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;border:0">New quote requests — tap to make a link</div>
	<?php foreach ( $quotes as $q ) : ?><div><a href="<?php echo esc_url( add_query_arg( 'from', $q->ID, $self ) ); ?>"><?php echo esc_html( $q->post_title ); ?> <span style="color:var(--gold)">→</span></a></div><?php endforeach; ?>
	</div>
	<?php endif; ?>
	<div class="box list" style="margin-top:22px"><div class="muted" style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;border:0">Recent</div>
	<?php foreach ( $recent as $p ) : $st = get_post_meta( $p->ID, '_bc_status', true ); $c = get_post_meta( $p->ID, '_bc_card', true ); ?>
		<div><?php if ( 'sent' === $st ) : ?><a href="<?php echo esc_url( add_query_arg( 'from', $p->ID, $self ) ); ?>"><?php echo esc_html( $p->post_title ); ?> <span style="color:var(--gold)">✎ edit / resend</span></a><?php else : echo esc_html( $p->post_title ); endif; ?><br><?php echo 'confirmed' === $st ? '<span class="ok">Confirmed · ' . esc_html( $c ? $c['brand'] . ' •••• ' . $c['last4'] : '' ) . '</span>' : '<span class="muted">Waiting on customer</span>'; ?></div>
	<?php endforeach; if ( ! $recent ) : ?><div class="muted">No bookings yet.</div><?php endif; ?>
	</div>
	<p class="muted" style="font-size:12px;text-align:center">Keep this page private. To see ID photos, use the website dashboard.</p>
</div></body></html>
	<?php
	exit;
}, 1 );


/* ---------------------------------------------------------------- Quote requests → prefilled booking links */

/**
 * When the website quote form emails "New Quote Request: ...", save the request so it
 * can be turned into a booking link with one tap, and add that button to the email.
 */
add_filter( 'wp_mail', function ( $args ) {
	if ( empty( $args['subject'] ) || 0 !== strpos( (string) $args['subject'], 'New Quote Request:' ) || empty( $_POST['mecca_qf_submit'] ) ) {
		return $args;
	}
	$p   = wp_unslash( $_POST );
	$g   = function ( $k ) use ( $p ) {
		return sanitize_text_field( $p[ $k ] ?? '' );
	};
	$ts  = strtotime( $g( 'date' ) . ' ' . $g( 'time' ) );
	$pax = (int) $g( 'passengers' );
	$notes = array_filter( array(
		$g( 'service' ) ? 'Service: ' . $g( 'service' ) : '',
		$pax ? 'Passengers: ' . $pax : '',
		$g( 'hours' ) ? 'Hours: ' . $g( 'hours' ) : '',
		$g( 'stop' ) ? 'Stop: ' . $g( 'stop' ) : '',
		$g( 'flight' ) ? 'Flight: ' . $g( 'flight' ) : '',
		! empty( $p['round_trip'] ) ? 'Return: ' . $g( 'return_date' ) . ' ' . $g( 'return_time' ) : '',
		sanitize_textarea_field( $p['notes'] ?? '' ),
	) );
	$d = array(
		'first_name' => $g( 'first_name' ),
		'last_name'  => $g( 'last_name' ),
		'email'      => sanitize_email( $p['email'] ?? '' ),
		'phone'      => $g( 'phone' ),
		'trip_date'  => $ts ? date( 'D, M j \\a\\t g:i A', $ts ) : trim( $g( 'date' ) . ' ' . $g( 'time' ) ),
		'pickup'     => $g( 'pickup' ),
		'dropoff'    => $g( 'dropoff' ),
		'vehicle'    => $pax > 5 ? 'Mercedes Sprinter' : ( $pax > 2 ? 'Luxury SUV' : 'Executive Sedan' ),
		'price'      => '',
		'notes'      => implode( "\n", $notes ),
	);
	$id = wp_insert_post( array(
		'post_type'   => 'mecca_booking',
		'post_status' => 'publish',
		'post_title'  => trim( $d['first_name'] . ' ' . $d['last_name'] ) . ' — ' . $d['trip_date'],
	) );
	if ( ! $id ) {
		return $args;
	}
	update_post_meta( $id, '_bc_details', $d );
	update_post_meta( $id, '_bc_status', 'quote' );
	$url  = add_query_arg( 'from', $id, mecca_bc_staff_url() );
	$html = false !== stripos( (string) $args['message'], '</' );
	$args['message'] .= $html
		? '<p style="margin:16px 0 0"><a href="' . esc_url( $url ) . '" style="display:inline-block;background:#c9a45c;color:#111;padding:12px 18px;border-radius:8px;font-weight:bold;text-decoration:none">Price agreed? Create booking link →</a></p>'
		: "\n\nPrice agreed? Create the booking link: " . $url;
	return $args;
}, 6 );

/* ---------------------------------------------------------------- Admin */

add_action( 'admin_menu', function () {
	add_menu_page( 'Mecca Bookings', 'Mecca Bookings', 'manage_options', 'mecca-bookings', 'mecca_bc_admin_list', 'dashicons-car', 3 );
	add_submenu_page( 'mecca-bookings', 'New booking link', 'New booking link', 'manage_options', 'mecca-bookings-new', 'mecca_bc_admin_new' );
	add_submenu_page( 'mecca-bookings', 'Square settings', 'Square settings', 'manage_options', 'mecca-bookings-settings', 'mecca_bc_admin_settings' );
} );

function mecca_bc_admin_settings() {
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['_wpnonce'] ) && check_admin_referer( 'mecca_bc_settings' ) ) {
		$old = (array) get_option( 'mecca_bc_settings', array() );
		$new = array(
			'env'         => 'production' === ( $_POST['env'] ?? '' ) ? 'production' : 'sandbox',
			'app_id'      => sanitize_text_field( wp_unslash( $_POST['app_id'] ?? '' ) ),
			'location_id' => sanitize_text_field( wp_unslash( $_POST['location_id'] ?? '' ) ),
			// Keep the saved token when the field is left blank.
			'token'       => '' !== trim( (string) ( $_POST['token'] ?? '' ) ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : ( $old['token'] ?? '' ),
		);
		update_option( 'mecca_bc_settings', $new, false );
		echo '<div class="updated"><p>Saved.</p></div>';
	}
	$has_token = '' !== mecca_bc_opt( 'token' );
	?>
	<div class="wrap"><h1>Square settings</h1>
	<p>From <a href="https://developer.squareup.com/apps" target="_blank" rel="noopener">developer.squareup.com/apps</a> → your app → Credentials (and Locations for the Location ID). Use <strong>Sandbox</strong> keys to test first, then switch to Production.</p>
	<form method="post"><?php wp_nonce_field( 'mecca_bc_settings' ); ?>
	<table class="form-table">
	<tr><th>Environment</th><td><select name="env"><option value="sandbox" <?php selected( mecca_bc_opt( 'env', 'sandbox' ), 'sandbox' ); ?>>Sandbox (testing)</option><option value="production" <?php selected( mecca_bc_opt( 'env' ), 'production' ); ?>>Production (live)</option></select></td></tr>
	<tr><th>Application ID</th><td><input class="regular-text" name="app_id" value="<?php echo esc_attr( mecca_bc_opt( 'app_id' ) ); ?>"></td></tr>
	<tr><th>Location ID</th><td><input class="regular-text" name="location_id" value="<?php echo esc_attr( mecca_bc_opt( 'location_id' ) ); ?>"></td></tr>
	<tr><th>Access token</th><td><input class="regular-text" type="password" name="token" placeholder="<?php echo $has_token ? 'Saved — leave blank to keep' : ''; ?>" autocomplete="off"></td></tr>
	</table><p><input type="hidden" name="mecca_bc_save" value="1"><button class="button button-primary">Save</button></p></form></div>
	<?php
}

function mecca_bc_admin_new() {
	$link = '';
	if ( isset( $_POST['mecca_bc_create'] ) && check_admin_referer( 'mecca_bc_new' ) ) {
		$link = mecca_bc_create( wp_unslash( $_POST ) )['link'];
	}
	?>
	<div class="wrap"><h1>New booking link</h1>
	<?php if ( $link ) : ?>
		<div class="updated"><p><strong>Link ready.</strong> Text or email this to the customer:</p>
		<p><input class="large-text" readonly value="<?php echo esc_attr( $link ); ?>" onclick="this.select()"></p></div>
	<?php endif; ?>
	<form method="post"><?php wp_nonce_field( 'mecca_bc_new' ); ?>
	<table class="form-table">
	<tr><th>First name</th><td><input name="first_name" required class="regular-text"></td></tr>
	<tr><th>Last name</th><td><input name="last_name" required class="regular-text"></td></tr>
	<tr><th>Email</th><td><input name="email" type="email" class="regular-text"></td></tr>
	<tr><th>Phone</th><td><input name="phone" class="regular-text" placeholder="843-555-1234"></td></tr>
	<tr><th>Date &amp; time</th><td><input name="trip_date" required class="regular-text" placeholder="Sat, Oct 24 at 6:30 PM"></td></tr>
	<tr><th>Pickup</th><td><input name="pickup" required class="large-text"></td></tr>
	<tr><th>Drop-off</th><td><input name="dropoff" class="large-text"></td></tr>
	<tr><th>Vehicle</th><td><select name="vehicle"><option>Executive Sedan</option><option>Luxury SUV</option><option>Mercedes Sprinter</option></select></td></tr>
	<tr><th>Quoted price</th><td><input name="price" class="regular-text" placeholder="$450 total"><p class="description">Shown only to this customer on their private link.</p></td></tr>
	<tr><th>Notes for customer</th><td><textarea name="notes" class="large-text" rows="3"></textarea></td></tr>
	</table><p><button class="button button-primary" name="mecca_bc_create" value="1">Create link</button></p></form></div>
	<?php
}

function mecca_bc_admin_list() {
	if ( isset( $_GET['view_id'] ) ) {
		mecca_bc_admin_view( (int) $_GET['view_id'] );
		return;
	}
	$q = get_posts( array( 'post_type' => 'mecca_booking', 'numberposts' => 100, 'post_status' => 'publish' ) );
	echo '<div class="wrap"><h1 class="wp-heading-inline">Mecca Bookings</h1> <a class="page-title-action" href="' . esc_url( admin_url( 'admin.php?page=mecca-bookings-new' ) ) . '">New booking link</a>';
	if ( '' === mecca_bc_opt( 'token' ) ) {
		echo '<div class="notice notice-warning"><p>Square isn\'t connected yet. Add your keys in <a href="' . esc_url( admin_url( 'admin.php?page=mecca-bookings-settings' ) ) . '">Square settings</a>.</p></div>';
	}
	if ( isset( $_GET['newkey'] ) && check_admin_referer( 'mecca_bc_newkey' ) ) {
		delete_option( 'mecca_bc_staff_key' );
	}
	echo '<div class="notice notice-info"><p><strong>Phone shortcut:</strong> open this on your phone and "Add to Home Screen": <input class="large-text" readonly value="' . esc_attr( mecca_bc_staff_url() ) . '" onclick="this.select()"> <a href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=mecca-bookings&newkey=1' ), 'mecca_bc_newkey' ) ) . '" onclick="return confirm(\'Make a new private link? The old one stops working.\')">Make a new private link</a></p></div>';
	echo '<table class="widefat striped" style="margin-top:12px"><thead><tr><th>Customer / trip</th><th>Status</th><th>Card</th><th>ID</th><th></th></tr></thead><tbody>';
	foreach ( $q as $p ) {
		$st   = get_post_meta( $p->ID, '_bc_status', true );
		$card = get_post_meta( $p->ID, '_bc_card', true );
		$idf  = get_post_meta( $p->ID, '_bc_id_file', true );
		echo '<tr><td>' . esc_html( $p->post_title ) . '</td><td>' . ( 'confirmed' === $st ? '<strong style="color:#1a7f37">Confirmed</strong>' : ( 'quote' === $st ? 'Quote request (no link yet)' : 'Waiting on customer' ) ) . '</td><td>' . esc_html( $card ? $card['brand'] . ' •••• ' . $card['last4'] : '—' ) . '</td><td>' . ( $idf ? 'Uploaded' : '—' ) . '</td><td><a href="' . esc_url( admin_url( 'admin.php?page=mecca-bookings&view_id=' . $p->ID ) ) . '">Open</a></td></tr>';
	}
	if ( ! $q ) {
		echo '<tr><td colspan="5">No booking links yet.</td></tr>';
	}
	echo '</tbody></table></div>';
}

function mecca_bc_admin_view( $id ) {
	$p = get_post( $id );
	if ( ! $p || 'mecca_booking' !== $p->post_type ) {
		echo '<div class="wrap"><p>Not found.</p></div>';
		return;
	}
	$d    = (array) get_post_meta( $id, '_bc_details', true );
	$card = get_post_meta( $id, '_bc_card', true );
	$sig  = get_post_meta( $id, '_bc_signed', true );
	echo '<div class="wrap"><h1>' . esc_html( $p->post_title ) . '</h1><table class="form-table">';
	foreach ( $d as $k => $v ) {
		echo '<tr><th>' . esc_html( ucwords( str_replace( '_', ' ', $k ) ) ) . '</th><td>' . nl2br( esc_html( $v ) ) . '</td></tr>';
	}
	echo '<tr><th>Customer link</th><td><input class="large-text" readonly value="' . esc_attr( home_url( '/confirm/?t=' . get_post_meta( $id, '_bc_token', true ) ) ) . '" onclick="this.select()"></td></tr>';
	echo '<tr><th>Status</th><td>' . esc_html( get_post_meta( $id, '_bc_status', true ) ) . '</td></tr>';
	if ( $card ) {
		echo '<tr><th>Card on file (Square)</th><td>' . esc_html( $card['brand'] . ' •••• ' . $card['last4'] . ' exp ' . $card['exp'] ) . '<br>Square customer ID: <code>' . esc_html( $card['customer_id'] ) . '</code><br>Card ID: <code>' . esc_html( $card['card_id'] ) . '</code><br><em>Charge it from your Square Dashboard → Customers, or in Limo Anywhere once Square is connected there.</em></td></tr>';
	}
	if ( $sig ) {
		echo '<tr><th>Signed</th><td>' . esc_html( $sig['name'] . ' — ' . $sig['time'] . ' — IP ' . $sig['ip'] ) . '</td></tr>';
	}
	if ( get_post_meta( $id, '_bc_id_file', true ) ) {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=mecca_bc_id&id=' . $id ), 'mecca_bc_id_' . $id );
		echo '<tr><th>Photo ID</th><td><a class="button" href="' . esc_url( $url ) . '" target="_blank">View ID</a> <a class="button" href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=mecca_bc_id_delete&id=' . $id ), 'mecca_bc_id_del_' . $id ) ) . '" onclick="return confirm(\'Delete this ID photo permanently?\')">Delete ID photo</a><p class="description">Delete the ID once the trip is done. There\'s no reason to keep it.</p></td></tr>';
	}
	echo '</table></div>';
}

add_action( 'admin_post_mecca_bc_id', function () {
	$id = (int) ( $_GET['id'] ?? 0 );
	if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'mecca_bc_id_' . $id ) ) {
		wp_die( 'Not allowed.' );
	}
	$f = get_post_meta( $id, '_bc_id_file', true );
	$path = mecca_bc_id_dir() . '/' . basename( (string) $f );
	if ( ! $f || ! is_file( $path ) ) {
		wp_die( 'File not found.' );
	}
	nocache_headers();
	header( 'Content-Type: ' . ( wp_check_filetype( $path )['type'] ?: 'application/octet-stream' ) );
	header( 'Content-Disposition: inline; filename="id-' . $id . '.' . pathinfo( $path, PATHINFO_EXTENSION ) . '"' );
	readfile( $path );
	exit;
} );

add_action( 'admin_post_mecca_bc_id_delete', function () {
	$id = (int) ( $_GET['id'] ?? 0 );
	if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'mecca_bc_id_del_' . $id ) ) {
		wp_die( 'Not allowed.' );
	}
	$f = get_post_meta( $id, '_bc_id_file', true );
	if ( $f ) {
		@unlink( mecca_bc_id_dir() . '/' . basename( $f ) );
		delete_post_meta( $id, '_bc_id_file' );
	}
	wp_safe_redirect( admin_url( 'admin.php?page=mecca-bookings&view_id=' . $id ) );
	exit;
} );

/* ---------------------------------------------------------------- Square */

function mecca_bc_square( $method, $path, $body ) {
	$base = 'production' === mecca_bc_opt( 'env', 'sandbox' ) ? 'https://connect.squareup.com' : 'https://connect.squareupsandbox.com';
	$res  = wp_remote_request( $base . $path, array(
		'method'  => $method,
		'timeout' => 20,
		'headers' => array(
			'Authorization'  => 'Bearer ' . mecca_bc_opt( 'token' ),
			'Square-Version' => MECCA_BC_SQUARE_VERSION,
			'Content-Type'   => 'application/json',
		),
		'body'    => wp_json_encode( $body ),
	) );
	if ( is_wp_error( $res ) ) {
		return array( 'errors' => array( array( 'detail' => $res->get_error_message() ) ) );
	}
	return (array) json_decode( wp_remote_retrieve_body( $res ), true );
}

function mecca_bc_find( $token ) {
	if ( ! preg_match( '/^[A-Za-z0-9]{32}$/', (string) $token ) ) {
		return null;
	}
	$q = get_posts( array( 'post_type' => 'mecca_booking', 'numberposts' => 1, 'meta_key' => '_bc_token', 'meta_value' => $token ) );
	return $q ? $q[0] : null;
}

/* ---------------------------------------------------------------- Customer page */

add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	if ( 'confirm' !== $path ) {
		return;
	}
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true ); // Private, per-customer page: never cache.
	}
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow' );
	$post = mecca_bc_find( $_REQUEST['t'] ?? '' );
	status_header( $post ? 200 : 404 );
	$msg  = '';
	$err  = '';

	if ( $post && 'POST' === $_SERVER['REQUEST_METHOD'] && 'confirmed' !== get_post_meta( $post->ID, '_bc_status', true ) ) {
		$err = mecca_bc_submit( $post );
		if ( '' === $err ) {
			// Send the browser to a plain GET of the same link so a refresh or "back" never re-posts or lands elsewhere.
			wp_safe_redirect( home_url( '/confirm/?t=' . get_post_meta( $post->ID, '_bc_token', true ) . '&done=1' ), 303 );
			exit;
		}
	}
	mecca_bc_render( $post, $msg, $err );
	exit;
}, 1 );

function mecca_bc_submit( $post ) {
	if ( ! wp_verify_nonce( $_POST['_bcn'] ?? '', 'mecca_bc_confirm_' . $post->ID ) ) {
		return 'Your session expired. Please reload the page and try again.';
	}
	$d     = (array) get_post_meta( $post->ID, '_bc_details', true );
	$name  = sanitize_text_field( wp_unslash( $_POST['signature'] ?? '' ) );
	$nonce = sanitize_text_field( wp_unslash( $_POST['card_nonce'] ?? '' ) );
	$vt    = sanitize_text_field( wp_unslash( $_POST['verification_token'] ?? '' ) );
	if ( empty( $_POST['agree'] ) || strlen( $name ) < 3 ) {
		return 'Please accept the terms and type your full name to sign.';
	}
	if ( '' === $nonce ) {
		return 'Please enter your card details.';
	}
	// ID upload: image or PDF, max 10 MB.
	if ( empty( $_FILES['photo_id']['tmp_name'] ) || UPLOAD_ERR_OK !== $_FILES['photo_id']['error'] ) {
		return 'Please upload a photo of your driver\'s license or ID.';
	}
	if ( $_FILES['photo_id']['size'] > 10 * MB_IN_BYTES ) {
		return 'That ID file is too large. Please use a photo under 10 MB.';
	}
	$check = wp_check_filetype_and_ext( $_FILES['photo_id']['tmp_name'], $_FILES['photo_id']['name'], array(
		'jpg|jpeg' => 'image/jpeg',
		'png'      => 'image/png',
		'heic'     => 'image/heic',
		'webp'     => 'image/webp',
		'pdf'      => 'application/pdf',
	) );
	if ( empty( $check['ext'] ) ) {
		return 'Please upload the ID as a photo (JPG, PNG, HEIC) or a PDF.';
	}

	// Save the card on file with Square (nothing is charged).
	$cust = mecca_bc_square( 'POST', '/v2/customers', array(
		'idempotency_key' => 'cust-' . $post->ID,
		'given_name'      => $d['first_name'] ?? '',
		'family_name'     => $d['last_name'] ?? '',
		'email_address'   => $d['email'] ?? '',
		'phone_number'    => $d['phone'] ?? '',
		'reference_id'    => 'mecca-booking-' . $post->ID,
		'note'            => 'Mecca Limo booking: ' . $post->post_title,
	) );
	if ( empty( $cust['customer']['id'] ) ) {
		return 'We couldn\'t save your card (' . esc_html( $cust['errors'][0]['detail'] ?? 'Square error' ) . '). Please call (843) 804-1188.';
	}
	$body = array(
		'idempotency_key' => 'card-' . $post->ID . '-' . substr( md5( $nonce ), 0, 8 ),
		'source_id'       => $nonce,
		'card'            => array(
			'customer_id'     => $cust['customer']['id'],
			'cardholder_name' => $name,
			'reference_id'    => 'mecca-booking-' . $post->ID,
		),
	);
	if ( $vt ) {
		$body['verification_token'] = $vt;
	}
	$card = mecca_bc_square( 'POST', '/v2/cards', $body );
	if ( empty( $card['card']['id'] ) ) {
		return 'Your card was declined or couldn\'t be saved (' . esc_html( $card['errors'][0]['detail'] ?? 'Square error' ) . '). Please try another card or call (843) 804-1188.';
	}

	// Store the ID photo outside public access.
	$fname = $post->ID . '-' . wp_generate_password( 16, false, false ) . '.' . $check['ext'];
	if ( ! move_uploaded_file( $_FILES['photo_id']['tmp_name'], mecca_bc_id_dir() . '/' . $fname ) ) {
		return 'We saved your card but couldn\'t save your ID photo. Please try uploading it again.';
	}

	$c = $card['card'];
	update_post_meta( $post->ID, '_bc_card', array(
		'customer_id' => $cust['customer']['id'],
		'card_id'     => $c['id'],
		'brand'       => $c['card_brand'] ?? '',
		'last4'       => $c['last_4'] ?? '',
		'exp'         => ( $c['exp_month'] ?? '' ) . '/' . ( $c['exp_year'] ?? '' ),
	) );
	update_post_meta( $post->ID, '_bc_id_file', $fname );
	update_post_meta( $post->ID, '_bc_signed', array(
		'name' => $name,
		'time' => current_time( 'mysql' ),
		'ip'   => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ),
	) );
	update_post_meta( $post->ID, '_bc_status', 'confirmed' );

	wp_mail(
		'info@meccalimo.com',
		'Booking confirmed: ' . $post->post_title,
		"A customer completed their booking confirmation.\n\n" . $post->post_title . "\nCard on file: " . ( $c['card_brand'] ?? '' ) . ' •••• ' . ( $c['last_4'] ?? '' ) . "\nSigned by: " . $name . "\n\nOpen it (card, signature and ID photo): " . mecca_bc_admin_link( $post->ID )
	);
	return '';
}

function mecca_bc_render( $post, $msg, $err ) {
	$d       = $post ? (array) get_post_meta( $post->ID, '_bc_details', true ) : array();
	$done    = $post && 'confirmed' === get_post_meta( $post->ID, '_bc_status', true );
	$sandbox = 'production' !== mecca_bc_opt( 'env', 'sandbox' );
	$logo    = 'https://www.meccalimo.com/wp-content/uploads/2026/10/mecca-limo-logo-gold.png';
	?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Confirm your booking — Mecca Limo</title>
<style>
:root{--gold:#c9a45c;--bg:#0e0e10;--card:#17171a;--line:#2a2a2f;--text:#f2efe8;--muted:#a9a6a0}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:16px/1.5 -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif}
.wrap{max-width:560px;margin:0 auto;padding:24px 16px 48px}.logo{display:block;width:170px;margin:8px auto 20px}
h1{font:600 26px/1.25 Georgia,serif;margin:0 0 6px;text-align:center}.sub{color:var(--muted);text-align:center;margin:0 0 22px}
.box{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:18px;margin:0 0 16px}
.box h2{font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:var(--gold);margin:0 0 12px}
.row{display:flex;justify-content:space-between;gap:12px;padding:6px 0;border-bottom:1px solid var(--line)}.row:last-child{border:0}.row span{color:var(--muted)}.row b{text-align:right;font-weight:600}
label{display:block;font-weight:600;margin:12px 0 6px}input[type=text],input[type=file]{width:100%;padding:12px;border-radius:8px;border:1px solid var(--line);background:#0f0f12;color:var(--text);font-size:16px}
#card-container{min-height:90px}.check{display:flex;gap:10px;align-items:flex-start;font-weight:400;color:var(--muted);font-size:14px}.check input{margin-top:4px;width:18px;height:18px}
.terms{font-size:13px;color:var(--muted);max-height:140px;overflow:auto;border:1px solid var(--line);border-radius:8px;padding:10px;background:#0f0f12}
button{width:100%;padding:15px;border:0;border-radius:10px;background:var(--gold);color:#111;font-size:17px;font-weight:700;cursor:pointer;margin-top:8px}button:disabled{opacity:.6}
.err{background:#3a1515;border:1px solid #7a2a2a;color:#ffd7d7;border-radius:8px;padding:12px;margin:0 0 16px}.ok{text-align:center}.ok .tick{font-size:48px;color:var(--gold)}
.note{font-size:13px;color:var(--muted);text-align:center;margin-top:14px}.sandbox{background:#3a3315;border:1px solid #7a6a2a;color:#ffe9a8;border-radius:8px;padding:8px 12px;font-size:13px;margin-bottom:14px;text-align:center}
</style></head><body><div class="wrap"><img class="logo" src="<?php echo esc_url( $logo ); ?>" alt="Mecca Limo">
<?php if ( ! $post ) : ?>
	<h1>Link not found</h1><p class="sub">This booking link is invalid or has expired. Please call or text <a style="color:var(--gold)" href="tel:+18438041188">(843) 804-1188</a>.</p>
<?php elseif ( $done ) : ?>
	<div class="box ok"><div class="tick">✓</div><h1>You're all set, <?php echo esc_html( $d['first_name'] ?? '' ); ?></h1><p class="sub">Your booking is confirmed and your card is saved on file. Nothing has been charged yet. We'll be in touch before your ride.</p></div>
	<p class="note">Questions? Call or text <a style="color:var(--gold)" href="tel:+18438041188">(843) 804-1188</a></p>
<?php else : ?>
	<h1>Confirm your booking</h1><p class="sub">Hi <?php echo esc_html( $d['first_name'] ?? '' ); ?>, please review your trip and complete the steps below.</p>
	<?php if ( $sandbox ) : ?><div class="sandbox">Test mode — no real cards are saved.</div><?php endif; ?>
	<?php if ( $err ) : ?><div class="err"><?php echo esc_html( $err ); ?></div><?php endif; ?>
	<div class="box"><h2>Your trip</h2>
		<?php foreach ( array( 'trip_date' => 'Date & time', 'pickup' => 'Pickup', 'dropoff' => 'Drop-off', 'vehicle' => 'Vehicle', 'price' => 'Quoted price' ) as $k => $lab ) : if ( ! empty( $d[ $k ] ) ) : ?>
			<div class="row"><span><?php echo esc_html( $lab ); ?></span><b><?php echo esc_html( $d[ $k ] ); ?></b></div>
		<?php endif; endforeach; ?>
		<?php if ( ! empty( $d['notes'] ) ) : ?><p style="color:var(--muted);font-size:14px;margin:10px 0 0"><?php echo nl2br( esc_html( $d['notes'] ) ); ?></p><?php endif; ?>
	</div>
	<form id="bf" method="post" enctype="multipart/form-data" action="<?php echo esc_url( home_url( '/confirm/?t=' . get_post_meta( $post->ID, '_bc_token', true ) ) ); ?>">
	<?php wp_nonce_field( 'mecca_bc_confirm_' . $post->ID, '_bcn' ); ?>
	<input type="hidden" name="card_nonce" id="card_nonce"><input type="hidden" name="verification_token" id="verification_token">
	<div class="box"><h2>1. Card on file</h2><p style="color:var(--muted);font-size:14px;margin:0 0 10px">Your card is saved securely by Square. <strong>Nothing is charged now.</strong></p><div id="card-container"></div></div>
	<div class="box"><h2>2. Photo ID</h2><label for="photo_id">Upload a photo of your driver's license or ID</label><input type="file" id="photo_id" name="photo_id" accept="image/*,.pdf" required><p style="color:var(--muted);font-size:13px;margin:8px 0 0">Kept private and deleted after your trip.</p></div>
	<div class="box"><h2>3. Agreement</h2>
		<div class="terms">By confirming, you authorize Mecca Limo to store this card with Square and to charge it for this reservation, including the agreed fare, any approved extra time, and cancellation fees under our policy: a $150 non-refundable deposit; 50% of the fare for cancellations within 30 days of the trip; 100% within 15 days. Quotes are honored for 48 hours. A 15-minute grace period applies at pickup. Full policy: meccalimo.com/policy.</div>
		<label class="check"><input type="checkbox" name="agree" value="1" required> I agree to the terms above and authorize Mecca Limo to keep my card on file for this booking.</label>
		<label for="signature">Type your full name to sign</label><input type="text" id="signature" name="signature" required autocomplete="name">
	</div>
	<button type="submit" id="go">Confirm booking</button>
	<p class="note">🔒 Card details go directly to Square and never touch our servers.</p>
	</form>
	<script src="https://<?php echo $sandbox ? 'sandbox.' : ''; ?>web.squarecdn.com/v1/square.js"></script>
	<script>
	(async function(){
		var form=document.getElementById('bf'),btn=document.getElementById('go');
		if(!window.Square){btn.disabled=true;btn.textContent='Card form unavailable — please call (843) 804-1188';return;}
		var payments=Square.payments(<?php echo wp_json_encode( mecca_bc_opt( 'app_id' ) ); ?>,<?php echo wp_json_encode( mecca_bc_opt( 'location_id' ) ); ?>);
		var card=await payments.card();await card.attach('#card-container');
		form.addEventListener('submit',async function(e){
			if(document.getElementById('card_nonce').value)return;
			e.preventDefault();btn.disabled=true;btn.textContent='Saving…';
			try{
				var r=await card.tokenize();
				if(r.status!=='OK'){throw new Error((r.errors&&r.errors[0]&&r.errors[0].message)||'Please check your card details.');}
				document.getElementById('card_nonce').value=r.token;
				try{var v=await payments.verifyBuyer(r.token,{intent:'STORE',billingContact:{givenName:<?php echo wp_json_encode( $d['first_name'] ?? '' ); ?>,familyName:<?php echo wp_json_encode( $d['last_name'] ?? '' ); ?>}});if(v&&v.token)document.getElementById('verification_token').value=v.token;}catch(x){}
				form.submit();
			}catch(x){alert(x.message);btn.disabled=false;btn.textContent='Confirm booking';}
		});
	})();
	</script>
<?php endif; ?>
</div></body></html>
	<?php
}
