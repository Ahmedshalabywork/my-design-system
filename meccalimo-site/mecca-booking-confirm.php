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
	return home_url( '/bk/' . (int) $id . '/' );
}

/* Short link /bk/ID/: straight to the booking when logged in, else login first. */
add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	if ( ! preg_match( '#^bk/(\d+)$#', $path, $m ) ) {
		return;
	}
	nocache_headers();
	$dest = home_url( '/new-booking/?view=' . (int) $m[1] );
	if ( current_user_can( 'manage_options' ) ) {
		wp_safe_redirect( $dest );
		exit;
	}
	// Remember the booking in a cookie: some login flows drop redirect_to and land on the dashboard.
	setcookie( 'mecca_bk', (string) (int) $m[1], time() + 900, COOKIEPATH ?: '/', '', is_ssl(), true );
	setcookie( 'mecca_bk', (string) (int) $m[1], time() + 900, ADMIN_COOKIE_PATH, '', is_ssl(), true );
	$login = get_option( 'whl_page' ) ? home_url( '/' . get_option( 'whl_page' ) . '/' ) : wp_login_url();
	wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( $dest ), $login ) );
	exit;
}, 0 );

/* After login, finish the trip to the booking the short link asked for. */
add_action( 'admin_init', function () {
	$id = (int) ( $_COOKIE['mecca_bk'] ?? 0 );
	if ( ! $id || wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	setcookie( 'mecca_bk', '', time() - 3600, COOKIEPATH ?: '/', '', is_ssl(), true );
	setcookie( 'mecca_bk', '', time() - 3600, ADMIN_COOKIE_PATH, '', is_ssl(), true );
	if ( ( $_GET['page'] ?? '' ) !== 'mecca-bookings' ) {
		wp_safe_redirect( home_url( '/new-booking/?view=' . $id ) );
		exit;
	}
} );

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

/**
 * Where a booking is: new (quote, no link) → sent (link waiting on customer) → confirmed
 * (signed, card + ID on file) → la (entered in Limo Anywhere) → done (trip finished).
 * A quote that already has a link reports 'linked'; its booking lives on the linked post.
 */
function mecca_bc_stage( $id ) {
	$s = get_post_meta( $id, '_bc_status', true );
	if ( 'confirmed' === $s ) {
		$g = get_post_meta( $id, '_bc_stage', true );
		return in_array( $g, array( 'la', 'done' ), true ) ? $g : 'confirmed';
	}
	if ( 'sent' === $s ) {
		return 'sent';
	}
	return get_post_meta( $id, '_bc_linked', true ) ? 'linked' : 'new';
}

function mecca_bc_stage_label( $st ) {
	$l = array( 'new' => 'New', 'sent' => 'Link sent', 'linked' => 'Link sent', 'confirmed' => 'Confirmed', 'la' => 'In Limo Anywhere', 'done' => 'Done' );
	return $l[ $st ] ?? $st;
}

/* ---------------------------------------------------------------- Limo Anywhere hand-off */

/**
 * Signed bookings waiting to be entered in Limo Anywhere, with the fields its reservation
 * form needs. Only bookings signed after the hand-off was switched on (older ones were
 * entered by hand). Read by Claude's scheduled check, which creates each reservation
 * through Zapier and then calls mecca_bc_la_mark().
 */
function mecca_bc_la_queue() {
	$since = (string) get_option( 'mecca_bc_la_since', '' );
	$out   = array();
	if ( '' === $since ) {
		return $out;
	}
	$veh = array( 'Executive Sedan' => 116180, 'Luxury SUV' => 117016, 'Mercedes Sprinter' => 138238 );
	foreach ( get_posts( array( 'post_type' => 'mecca_booking', 'numberposts' => 50, 'post_status' => 'publish', 'meta_key' => '_bc_status', 'meta_value' => 'confirmed' ) ) as $p ) {
		$sig = (array) get_post_meta( $p->ID, '_bc_signed', true );
		if ( 'confirmed' !== mecca_bc_stage( $p->ID ) || get_post_meta( $p->ID, '_bc_la_ref', true ) || ( $sig['time'] ?? '' ) < $since ) {
			continue;
		}
		$d  = (array) get_post_meta( $p->ID, '_bc_details', true );
		$ts = strtotime( str_replace( ' at ', ' ', (string) ( $d['trip_date'] ?? '' ) ) );
		if ( $ts && $ts < time() - 30 * DAY_IN_SECONDS ) {
			$ts = strtotime( '+1 year', $ts ); // "Sat, Jan 9" typed in December means next year.
		}
		$pax = preg_match( '/Passengers:\s*(\d+)/', (string) ( $d['notes'] ?? '' ), $m ) ? (int) $m[1] : 1;
		$geo = function_exists( 'mecca_zap_geocode' ) ? 'mecca_zap_geocode' : function () {
			return array( '32.7765', '-79.9311' );
		};
		$card  = (array) get_post_meta( $p->ID, '_bc_card', true );
		$out[] = array(
			'id'         => $p->ID,
			'pickup_at'  => $ts ? date( 'Y-m-d H:i', $ts ) : '',
			'first_name' => $d['first_name'] ?? '',
			'last_name'  => $d['last_name'] ?? '',
			'phone'      => $d['phone'] ?? '',
			'email'      => $d['email'] ?? '',
			'passengers' => $pax,
			'vehicle_id' => $veh[ $d['vehicle'] ?? '' ] ?? 116180,
			'vehicle'    => $d['vehicle'] ?? '',
			'pickup'     => $d['pickup'] ?? '',
			'pickup_ll'  => $geo( $d['pickup'] ?? '' ),
			'dropoff'    => $d['dropoff'] ?? '',
			'dropoff_ll' => $geo( $d['dropoff'] ?? '' ),
			'notes'      => trim( 'Mecca app booking #' . $p->ID . ' (signed, card on file ' . ( $card['last4'] ?? '' ) . '). Price quoted: ' . ( $d['price'] ?? '' ) . "\n" . ( $d['notes'] ?? '' ) ),
		);
	}
	return $out;
}

/** Record that a booking is in Limo Anywhere (moves it to the "In LA" step). */
function mecca_bc_la_mark( $id, $ref ) {
	update_post_meta( $id, '_bc_la_ref', sanitize_text_field( $ref ) );
	update_post_meta( $id, '_bc_stage', 'la' );
}

/** Pickup time as a timestamp, read from the typed "Sat, Oct 24 at 6:30 PM" date. */
function mecca_bc_trip_ts( $d ) {
	$ts = strtotime( str_replace( ' at ', ' ', (string) ( $d['trip_date'] ?? '' ) ) );
	if ( $ts && $ts < time() - 30 * DAY_IN_SECONDS ) {
		$ts = strtotime( '+1 year', $ts ); // "Sat, Jan 9" typed in December means next year.
	}
	return $ts ?: 0;
}

/** Total charged through the app's Charge card button, in cents. */
function mecca_bc_paid_cents( $id ) {
	$sum = 0;
	foreach ( (array) get_post_meta( $id, '_bc_payments', true ) as $p ) {
		$sum += (int) ( $p['cents'] ?? 0 );
	}
	return $sum;
}

/** Pre-written customer message for SMS/email. */
function mecca_bc_message( $d, $link ) {
	return 'Hi ' . ( $d['first_name'] ?? '' ) . ', this is Mecca Limo. Thanks for booking with us! Please confirm your ride (' . ( $d['trip_date'] ?? '' ) . ') here: ' . $link . ' It takes 2 minutes. Your card is saved securely and nothing is charged today. Questions? Call or text (843) 804-1188.';
}

/** Trip fields shared by the app's quote form and manual form. */
function mecca_bc_app_fields( $v, $pre ) {
	?>
	<div class="two"><div class="field"><label>First name</label><input name="first_name" value="<?php echo $v( 'first_name' ); ?>" required autocomplete="off"></div><div class="field"><label>Last name</label><input name="last_name" value="<?php echo $v( 'last_name' ); ?>" required autocomplete="off"></div></div>
	<div class="two"><div class="field"><label>Phone</label><input name="phone" value="<?php echo $v( 'phone' ); ?>" type="tel" inputmode="tel"></div><div class="field"><label>Email</label><input name="email" value="<?php echo $v( 'email' ); ?>" type="email" inputmode="email"></div></div>
	<div class="field"><label>Date &amp; time</label><input name="trip_date" value="<?php echo $v( 'trip_date' ); ?>" required placeholder="Sat, Oct 24 at 6:30 PM"></div>
	<div class="field"><label>Pickup</label><input name="pickup" value="<?php echo $v( 'pickup' ); ?>" required></div>
	<div class="field"><label>Drop-off</label><input name="dropoff" value="<?php echo $v( 'dropoff' ); ?>"></div>
	<div class="field"><label>Notes for customer</label><textarea name="notes" rows="3"><?php echo esc_textarea( $pre['notes'] ?? '' ); ?></textarea></div>
	<?php
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
	// The phone app remembers the key in a cookie, so it opens even without ?k= in the address.
	if ( '' === $key && ! empty( $_COOKIE['mecca_app'] ) && hash_equals( wp_hash( mecca_bc_staff_key() ), (string) $_COOKIE['mecca_app'] ) ) {
		$key = mecca_bc_staff_key();
	}
	if ( '' !== $key && hash_equals( mecca_bc_staff_key(), $key ) ) {
		setcookie( 'mecca_app', wp_hash( $key ), time() + YEAR_IN_SECONDS, '/new-booking', '', true, true );
	}
	if ( ! current_user_can( 'manage_options' ) && '' === $key ) {
		// Links in emails carry no key (customers can see replies); log in first.
		$login = get_option( 'whl_page' ) ? home_url( '/' . get_option( 'whl_page' ) . '/' ) : wp_login_url();
		wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( home_url( $_SERVER['REQUEST_URI'] ) ), $login ) );
		exit;
	}
	if ( ! current_user_can( 'manage_options' ) && ! hash_equals( mecca_bc_staff_key(), $key ) ) {
		status_header( 404 );
		echo '<!doctype html><meta name="viewport" content="width=device-width,initial-scale=1"><p style="font:16px sans-serif;padding:24px">Not found.</p>';
		exit;
	}
	status_header( 200 );
	$self = mecca_bc_staff_url();
	// Private ID photo, shown inside the app (same access check as the page itself).
	if ( isset( $_GET['idimg'] ) ) {
		$iid  = (int) $_GET['idimg'];
		$f    = 'mecca_booking' === get_post_type( $iid ) ? get_post_meta( $iid, '_bc_id_file', true ) : '';
		$path = $f ? mecca_bc_id_dir() . '/' . basename( (string) $f ) : '';
		if ( ! $path || ! is_file( $path ) ) {
			status_header( 404 );
			exit;
		}
		header( 'Content-Type: ' . ( wp_check_filetype( $path )['type'] ?: 'application/octet-stream' ) );
		header( 'Content-Disposition: inline; filename="id-' . $iid . '.' . pathinfo( $path, PATHINFO_EXTENSION ) . '"' );
		readfile( $path );
		exit;
	}
	// "Charge card" on a confirmed booking.
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['charge'] ) ) {
		$cid = (int) ( $_POST['id'] ?? 0 );
		$err = 'Your session expired. Please try again.';
		if ( wp_verify_nonce( $_POST['_sn'] ?? '', 'mecca_bc_staff' ) && 'mecca_booking' === get_post_type( $cid ) ) {
			$cents = (int) round( 100 * (float) preg_replace( '/[^0-9.]/', '', (string) $_POST['charge'] ) );
			$err   = mecca_bc_charge( $cid, $cents, (string) ( $_POST['ctok'] ?? '' ) );
		}
		wp_safe_redirect( add_query_arg( '' === $err ? array( 'view' => $cid, 'paid' => 1 ) : array( 'view' => $cid, 'payerr' => rawurlencode( $err ) ), $self ), 303 );
		exit;
	}
	// Next-step buttons on a confirmed booking.
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['stage'] ) ) {
		$sid = (int) ( $_POST['id'] ?? 0 );
		$to  = sanitize_key( $_POST['stage'] );
		if ( wp_verify_nonce( $_POST['_sn'] ?? '', 'mecca_bc_staff' ) && 'mecca_booking' === get_post_type( $sid ) && 'confirmed' === get_post_meta( $sid, '_bc_status', true ) && in_array( $to, array( 'confirmed', 'la', 'done' ), true ) ) {
			if ( 'confirmed' === $to ) {
				delete_post_meta( $sid, '_bc_stage' );
			} else {
				update_post_meta( $sid, '_bc_stage', $to );
			}
		}
		wp_safe_redirect( add_query_arg( 'view', $sid, $self ), 303 );
		exit;
	}
	// ?view=ID shows one booking with everything on it. A quote with a link opens its booking.
	$view = (int) ( $_GET['view'] ?? 0 );
	if ( $view && 'mecca_booking' !== get_post_type( $view ) ) {
		$view = 0;
	}
	if ( $view && 'linked' === mecca_bc_stage( $view ) ) {
		$view = (int) get_post_meta( $view, '_bc_linked', true );
	}
	$tab  = in_array( $_GET['tab'] ?? '', array( 'schedule', 'customers', 'money' ), true ) ? $_GET['tab'] : '';
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
	$quotes = get_posts( array( 'post_type' => 'mecca_booking', 'numberposts' => 30, 'post_status' => 'publish', 'meta_query' => array( array( 'key' => '_bc_status', 'value' => 'quote' ), array( 'key' => '_bc_linked', 'compare' => 'NOT EXISTS' ) ) ) );
	$all    = get_posts( array( 'post_type' => 'mecca_booking', 'numberposts' => 300, 'post_status' => 'publish' ) );
	$next   = array(); // Signed, not finished yet.
	$wait   = array(); // Link sent, customer hasn't signed.
	foreach ( $all as $q ) {
		$st = mecca_bc_stage( $q->ID );
		if ( 'confirmed' === $st || 'la' === $st ) {
			$next[] = $q;
		} elseif ( 'sent' === $st ) {
			$wait[] = $q;
		}
	}
	$v      = function ( $k ) use ( $pre ) {
		return esc_attr( $pre[ $k ] ?? '' );
	};
	$icon  = home_url( '/wp-content/plugins/mecca-home/assets/icon-192.png' );
	$route  = function ( $d ) {
		return array( trim( (string) ( $d['pickup'] ?? '' ) ), trim( (string) ( $d['dropoff'] ?? '' ) ) );
	};
	$digits = function ( $p ) {
		return preg_replace( '/[^0-9+]/', '', (string) $p );
	};
	?><!doctype html><html lang="en"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="robots" content="noindex,nofollow"><meta name="apple-mobile-web-app-capable" content="yes"><meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Mecca Limo"><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"><meta name="theme-color" content="#0b0b0d">
<meta name="format-detection" content="telephone=no">
<link rel="apple-touch-icon" href="<?php echo esc_url( $icon ); ?>">
<title>Mecca Limo</title>
<style>
:root{--gold:#e3c27d;--gold2:#9c7a36;--goldg:linear-gradient(135deg,#f3dfa8 0%,#d4ad5e 45%,#a7802f 100%);--bg:#050505;--card:#0d0d0e;--card2:#121213;--line:rgba(227,194,125,.16);--text:#f7f3ea;--muted:#8f8a80;--ok:#7fd6a4;--r:18px}
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html{background:var(--bg)}
html,body{overflow-x:hidden}body{margin:0;background:radial-gradient(90% 45% at 50% -8%,rgba(227,194,125,.10) 0%,transparent 60%),var(--bg) fixed;color:var(--text);font:16px/1.4 -apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,sans-serif;-webkit-font-smoothing:antialiased;min-height:100vh}
a{color:inherit;text-decoration:none}
.top{position:sticky;top:0;z-index:5;padding:calc(env(safe-area-inset-top) + 10px) 18px 12px;background:rgba(5,5,5,.82);backdrop-filter:saturate(160%) blur(18px);-webkit-backdrop-filter:saturate(160%) blur(18px);border-bottom:1px solid var(--line);display:flex;align-items:center;gap:12px}
.top img{width:38px;height:38px;border-radius:10px;box-shadow:0 0 0 1px rgba(227,194,125,.35)}
.brand{background:var(--goldg);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;font:600 17px/1.1 "Didot","Bodoni 72",Georgia,serif;letter-spacing:.18em;color:var(--gold)}
.brand small{-webkit-text-fill-color:var(--muted);display:block;font:500 11px -apple-system,sans-serif;letter-spacing:.12em;color:var(--muted);margin-top:3px;text-transform:uppercase}
.back{font-size:28px;line-height:1;color:var(--gold);padding:0 6px 0 0}
.wrap{max-width:560px;margin:0 auto;padding:16px 16px calc(env(safe-area-inset-bottom) + 110px)}
.search{position:relative;margin:4px 0 18px}
.search input{width:100%;padding:15px 16px 15px 46px;border-radius:14px;border:1px solid var(--line);background:var(--card);color:var(--text);font-size:17px;outline:none;transition:border-color .2s,box-shadow .2s}
.search input:focus{border-color:rgba(227,194,125,.6);box-shadow:0 0 0 4px rgba(227,194,125,.12)}
.search svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);opacity:.55}
.sec{display:flex;align-items:center;justify-content:space-between;margin:22px 2px 10px}
.sec h2{margin:0;font:600 13px -apple-system,sans-serif;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
.badge{min-width:24px;padding:3px 9px;border-radius:99px;background:var(--goldg);color:#141006;font:700 13px -apple-system,sans-serif;text-align:center}
.card{background:linear-gradient(180deg,var(--card2),var(--card));border:1px solid var(--line);border-radius:var(--r);padding:16px;margin:0 0 12px;box-shadow:inset 0 1px 0 rgba(255,255,255,.04),0 18px 40px -24px #000;animation:in .35s ease both}
@keyframes in{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}
.name{font:600 18px/1.25 -apple-system,sans-serif;margin:0 0 4px}
.when{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--gold);background:rgba(227,194,125,.1);border:1px solid rgba(227,194,125,.22);padding:4px 10px;border-radius:99px;margin:4px 0 10px}
.route{position:relative;padding-left:18px;margin:2px 0 4px;font-size:14px;color:#d9d5cc}
.route div{padding:2px 0}
.route:before{content:"";position:absolute;left:4px;top:10px;bottom:10px;width:1px;background:linear-gradient(var(--gold),rgba(227,194,125,.2))}
.route div:before{content:"";position:absolute;left:0;width:9px;height:9px;margin-top:6px;border-radius:50%;background:var(--bg);border:2px solid var(--gold)}
.route div:last-child:before{background:var(--gold)}
.meta{font-size:13px;color:var(--muted);margin-top:6px}
.acts{display:flex;gap:8px;margin-top:14px}
.chip{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:12px 8px;border-radius:12px;background:transparent;border:1px solid var(--line);font:600 14px -apple-system,sans-serif;color:var(--text);transition:transform .12s}
.chip{min-width:0}.chip.go{flex:1.6;background:var(--goldg);color:#141006;border:0}
.chip:active,.btn:active{transform:scale(.97)}
.empty{text-align:center;color:var(--muted);padding:34px 16px;border:1px dashed var(--line);border-radius:var(--r)}
.empty b{display:block;color:var(--text);font-size:16px;margin-bottom:4px}
.row{display:flex;align-items:center;gap:12px;padding:13px 2px;border-bottom:1px solid var(--line)}
.row:last-child{border:0}.row .t{flex:1;min-width:0}.row .t b{display:block;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.row .t span{font-size:13px;color:var(--muted)}
.tag{font:600 12px -apple-system,sans-serif;padding:5px 10px;border-radius:99px;white-space:nowrap}
.tag.ok{color:var(--ok);background:rgba(76,210,138,.1)}.tag.wait{color:var(--gold);background:rgba(227,194,125,.1)}.tag.new{color:#141006;background:var(--gold)}
details.manual{margin-top:22px}details.manual summary{list-style:none;cursor:pointer;text-align:center;color:var(--muted);font-size:14px;padding:12px}details.manual summary::-webkit-details-marker{display:none}
.field{margin:0 0 12px}.field label{display:block;font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin:0 0 6px 2px}
.field input,.field textarea,.field select{width:100%;padding:14px;border-radius:12px;border:1px solid var(--line);background:#0a0a0b;color:var(--text);font-size:16px;outline:none;font-family:inherit}
.field input:focus,.field textarea:focus{border-color:rgba(227,194,125,.6)}
.two{display:flex;gap:10px}.two>.field{flex:1}
.price input{font:600 30px -apple-system,sans-serif;text-align:center;padding:18px;color:var(--gold);letter-spacing:.02em}
.seg{display:flex;background:#0a0a0b;border:1px solid var(--line);border-radius:12px;padding:4px;gap:4px}
.seg label{flex:1;margin:0}.seg input{display:none}.seg span{display:block;text-align:center;padding:11px 4px;border-radius:9px;font:600 13px -apple-system,sans-serif;color:var(--muted);transition:all .15s}
.seg input:checked+span{background:var(--goldg);color:#141006}
.sum{display:flex;align-items:center;gap:14px}.av{width:48px;height:48px;border-radius:50%;display:grid;place-items:center;background:rgba(227,194,125,.14);color:var(--gold);font:700 18px -apple-system,sans-serif;flex:none}
details.more{margin:6px 0 4px}details.more summary{list-style:none;color:var(--gold);font-size:14px;padding:10px 2px;cursor:pointer}details.more summary::-webkit-details-marker{display:none}
.dock{position:fixed;left:0;right:0;bottom:0;padding:12px 16px calc(env(safe-area-inset-bottom) + 12px);background:linear-gradient(transparent,rgba(11,11,13,.95) 30%)}
.btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;max-width:528px;margin:0 auto 10px;padding:17px;border:0;border-radius:14px;background:var(--goldg);color:#1a1307;font:700 17px -apple-system,sans-serif;letter-spacing:.02em;box-shadow:0 14px 34px -14px rgba(227,194,125,.5),inset 0 1px 0 rgba(255,255,255,.45);transition:transform .12s;cursor:pointer}
.btn.alt{background:transparent;color:var(--text);border:1px solid var(--line);box-shadow:none}
.done{text-align:center;padding:22px 0 8px}
.tick{width:74px;height:74px;margin:0 auto 14px;border-radius:50%;display:grid;place-items:center;background:rgba(76,210,138,.12);animation:pop .45s cubic-bezier(.2,1.6,.4,1) both}
@keyframes pop{from{transform:scale(.4);opacity:0}to{transform:none;opacity:1}}
.done h1{font:600 24px "Didot","Bodoni 72",Georgia,serif;margin:0 0 4px}.done p{margin:0;color:var(--muted)}
.linkbox{font:14px ui-monospace,Menlo,monospace;color:var(--gold);word-break:break-all;background:#111114;border:1px solid var(--line);border-radius:12px;padding:14px;margin:18px 0}
.hint{font-size:13px;color:var(--muted);margin:-2px 2px 14px}
.tabs{position:fixed;left:0;right:0;bottom:0;z-index:6;display:flex;background:rgba(5,5,5,.92);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-top:1px solid var(--line);padding:6px 4px calc(env(safe-area-inset-bottom) + 6px)}
.tabs a{flex:1;text-align:center;font:600 11px -apple-system,sans-serif;color:var(--muted);padding:4px 0}.tabs a span{display:block;font-size:20px;line-height:1.2}.tabs a.on{color:var(--gold)}
.steps{display:flex;gap:4px;margin:4px 0 16px}.steps div{flex:1;text-align:center;font:600 10px -apple-system,sans-serif;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)}
.steps div:before{content:"";display:block;height:4px;border-radius:4px;background:#1c1c1e;margin-bottom:6px}.steps div.on{color:var(--gold)}.steps div.on:before{background:var(--goldg)}
.kv{display:flex;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid var(--line);font-size:15px}.kv:last-child{border:0}.kv span{color:var(--muted)}.kv b{font-weight:600;text-align:right;word-break:break-word}
.idph{display:block;width:100%;border-radius:12px;border:1px solid var(--line);margin-top:6px}
form.inline{margin:0}
</style></head><body>
<header class="top">
<?php if ( $from || $made || $view ) : ?><a class="back" href="<?php echo esc_url( $self ); ?>" aria-label="Back">‹</a><?php endif; ?>
<img src="<?php echo esc_url( $icon ); ?>" alt=""><div class="brand">MECCA LIMO<small><?php echo $made ? 'Booking link' : ( $from ? 'Create booking' : ( $view ? 'Booking' : ( $tab ? ucfirst( $tab ) : 'Quotes &amp; bookings' ) ) ); ?></small></div>
</header>
<main class="wrap">
<?php if ( $view && ! $made && ! $from ) :
	$d    = (array) get_post_meta( $view, '_bc_details', true );
	$st   = mecca_bc_stage( $view );
	$nm   = trim( ( $d['first_name'] ?? '' ) . ' ' . ( $d['last_name'] ?? '' ) ) ?: get_the_title( $view );
	$ph   = $digits( $d['phone'] ?? '' );
	$tok  = get_post_meta( $view, '_bc_token', true );
	$link = $tok ? home_url( '/confirm/?t=' . $tok ) : '';
	$card = get_post_meta( $view, '_bc_card', true );
	$sig  = get_post_meta( $view, '_bc_signed', true );
	$idf  = get_post_meta( $view, '_bc_id_file', true );
	$ord  = array( 'new' => 0, 'sent' => 1, 'confirmed' => 2, 'la' => 3, 'done' => 4 );
	$at   = $ord[ $st ] ?? 0;
	list( $pu, $do ) = $route( $d );
	$stage_btn = function ( $to, $label, $cls = 'btn' ) use ( $view, $self ) {
		echo '<form class="inline" method="post" action="' . esc_url( $self ) . '">';
		wp_nonce_field( 'mecca_bc_staff', '_sn' );
		echo '<input type="hidden" name="id" value="' . (int) $view . '"><input type="hidden" name="stage" value="' . esc_attr( $to ) . '"><button class="' . esc_attr( $cls ) . '">' . esc_html( $label ) . '</button></form>';
	};
	?>
	<div class="steps"><?php foreach ( array( 'New', 'Link sent', 'Confirmed', 'In LA', 'Done' ) as $i => $s ) : ?><div class="<?php echo $i <= $at ? 'on' : ''; ?>"><?php echo esc_html( $s ); ?></div><?php endforeach; ?></div>
	<div class="card">
		<div class="name"><?php echo esc_html( $nm ); ?></div>
		<?php if ( ! empty( $d['trip_date'] ) ) : ?><div class="when">🗓 <?php echo esc_html( $d['trip_date'] ); ?></div><?php endif; ?>
		<?php if ( $pu || $do ) : ?><div class="route"><?php if ( $pu ) : ?><div><?php echo esc_html( $pu ); ?></div><?php endif; ?><?php if ( $do ) : ?><div><?php echo esc_html( $do ); ?></div><?php endif; ?></div><?php endif; ?>
		<div style="margin-top:10px">
		<?php foreach ( array( 'vehicle' => 'Vehicle', 'price' => 'Price', 'phone' => 'Phone', 'email' => 'Email' ) as $k => $lab ) : if ( ! empty( $d[ $k ] ) ) : ?>
			<div class="kv"><span><?php echo esc_html( $lab ); ?></span><b><?php echo esc_html( $d[ $k ] ); ?></b></div>
		<?php endif; endforeach; ?>
		<?php if ( ! empty( $d['notes'] ) ) : ?><div class="kv"><span>Notes</span><b><?php echo nl2br( esc_html( $d['notes'] ) ); ?></b></div><?php endif; ?>
		</div>
		<?php if ( $ph ) : ?><div class="acts"><a class="chip" href="tel:<?php echo esc_attr( $ph ); ?>">📞 Call</a><a class="chip" href="sms:<?php echo esc_attr( $ph ); ?>">💬 Text</a></div><?php endif; ?>
	</div>
	<?php if ( $card || $sig || $idf ) : ?>
	<div class="sec"><h2>Customer confirmed</h2></div>
	<div class="card">
		<?php if ( $card ) : ?><div class="kv"><span>Card on file</span><b><?php echo esc_html( trim( ucwords( strtolower( str_replace( '_', ' ', $card['brand'] ) ) ) . ' •••• ' . $card['last4'] ) ); ?></b></div><?php endif; ?>
		<?php if ( $sig ) : ?><div class="kv"><span>Signed</span><b><?php echo esc_html( $sig['name'] ); ?><br><small style="color:var(--muted);font-weight:400"><?php echo esc_html( mysql2date( 'M j, g:i A', $sig['time'] ) ); ?></small></b></div><?php endif; ?>
		<?php if ( $idf ) :
			$isimg = in_array( strtolower( pathinfo( (string) $idf, PATHINFO_EXTENSION ) ), array( 'jpg', 'jpeg', 'png', 'webp' ), true );
			$iurl  = add_query_arg( 'idimg', $view, $self );
			?>
			<div class="kv" style="display:block"><span>Driver's license / ID</span>
			<?php if ( $isimg ) : ?><a href="<?php echo esc_url( $iurl ); ?>" target="_blank"><img class="idph" src="<?php echo esc_url( $iurl ); ?>" alt="Photo ID"></a><?php else : ?><a class="chip" style="margin-top:8px" href="<?php echo esc_url( $iurl ); ?>" target="_blank">Open ID file</a><?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php if ( ! empty( $card['card_id'] ) ) :
		$pays  = array_filter( (array) get_post_meta( $view, '_bc_payments', true ) );
		$label = trim( ucwords( strtolower( str_replace( '_', ' ', $card['brand'] ) ) ) . ' •••• ' . $card['last4'] );
		$guess = preg_replace( '/[^0-9.]/', '', (string) ( $d['price'] ?? '' ) );
		?>
	<div class="sec"><h2>Payment</h2></div>
	<div class="card">
		<?php if ( isset( $_GET['paid'] ) ) : ?><p class="hint" style="color:var(--ok);margin:0 0 10px">✓ Payment went through.</p><?php endif; ?>
		<?php if ( isset( $_GET['payerr'] ) ) : ?><p class="hint" style="color:#ff8a80;margin:0 0 10px"><?php echo esc_html( wp_unslash( $_GET['payerr'] ) ); ?></p><?php endif; ?>
		<?php foreach ( $pays as $p ) : if ( empty( $p['id'] ) ) { continue; } ?>
			<div class="kv"><span>Paid <?php echo esc_html( mysql2date( 'M j, g:i A', $p['time'] ) ); ?></span><b>$<?php echo esc_html( number_format( $p['cents'] / 100, 2 ) ); ?><?php if ( $p['receipt'] ) : ?> · <a href="<?php echo esc_url( $p['receipt'] ); ?>" target="_blank" style="color:var(--gold)">receipt</a><?php endif; ?></b></div>
		<?php endforeach; ?>
		<form class="inline" method="post" action="<?php echo esc_url( $self ); ?>" onsubmit="var a=this.charge.value.replace(/[^0-9.]/g,'');if(!a||+a<1){alert('Enter an amount.');return false}if(!confirm('Charge $'+(+a).toFixed(2)+' to <?php echo esc_js( $label ); ?>?'))return false;this.querySelector('button').disabled=true;this.querySelector('button').textContent='Charging…'">
			<?php wp_nonce_field( 'mecca_bc_staff', '_sn' ); ?>
			<input type="hidden" name="id" value="<?php echo (int) $view; ?>"><input type="hidden" name="ctok" value="<?php echo esc_attr( wp_generate_password( 24, false, false ) ); ?>">
			<div class="field price" style="margin-top:8px"><label>Amount to charge · <?php echo esc_html( $label ); ?></label><input name="charge" inputmode="decimal" autocomplete="off" placeholder="$0.00" value="<?php echo $pays ? '' : esc_attr( $guess ); ?>"></div>
			<button class="btn">💳 Charge card</button>
		</form>
	</div>
	<?php endif; ?>
	<?php if ( get_post_meta( $view, '_bc_la_ref', true ) ) : ?><div class="card"><div class="kv"><span>Limo Anywhere</span><b><?php echo esc_html( get_post_meta( $view, '_bc_la_ref', true ) ); ?></b></div></div><?php endif; ?>
	<?php if ( $link ) : ?>
	<div class="sec"><h2>Customer link</h2></div>
	<div class="linkbox" style="margin-top:0"><?php echo esc_html( $link ); ?></div>
	<?php endif; ?>
	<div class="dock">
	<?php if ( 'new' === $st ) : ?>
		<a class="btn" href="<?php echo esc_url( add_query_arg( 'from', $view, $self ) ); ?>">Add price &amp; make link →</a>
	<?php elseif ( 'sent' === $st ) :
		$msg = mecca_bc_message( $d, $link );
		?>
		<button type="button" class="btn" onclick="navigator.clipboard.writeText(<?php echo esc_attr( wp_json_encode( $link ) ); ?>);this.textContent='Copied ✓'">📋 Copy link</button>
		<?php if ( $ph ) : ?><a class="btn alt" href="sms:<?php echo esc_attr( $ph ); ?>?&amp;body=<?php echo rawurlencode( $msg ); ?>">💬 Text link</a><?php endif; ?>
		<a class="btn alt" href="<?php echo esc_url( add_query_arg( 'from', $view, $self ) ); ?>">Edit trip</a>
	<?php elseif ( 'confirmed' === $st ) : ?>
		<?php $stage_btn( 'la', '✓ Added to Limo Anywhere' ); ?>
	<?php elseif ( 'la' === $st ) : ?>
		<?php $stage_btn( 'done', '✓ Trip done' ); ?>
		<?php $stage_btn( 'confirmed', 'Undo', 'btn alt' ); ?>
	<?php else : ?>
		<?php $stage_btn( 'la', 'Undo', 'btn alt' ); ?>
	<?php endif; ?>
	</div>
<?php elseif ( $made ) :
	$msg = mecca_bc_message( $made['details'], $made['link'] );
	$ph  = $digits( $made['details']['phone'] );
	$nm  = trim( $made['details']['first_name'] . ' ' . $made['details']['last_name'] );
	?>
	<div class="done">
		<div class="tick"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#4cd28a" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></div>
		<h1><?php echo ! empty( $made['updated'] ) ? 'Link updated' : 'Link ready'; ?></h1>
		<p>For <?php echo esc_html( $nm ); ?><?php echo ! empty( $made['details']['price'] ) ? ' · ' . esc_html( $made['details']['price'] ) : ''; ?></p>
	</div>
	<div class="linkbox"><?php echo esc_html( $made['link'] ); ?></div>
	<p class="hint">Nothing is sent until you tap send in Messages or Mail.</p>
	<?php if ( $ph ) : ?><a class="btn" href="sms:<?php echo esc_attr( $ph ); ?>?&amp;body=<?php echo rawurlencode( $msg ); ?>">💬 Text it to <?php echo esc_html( $made['details']['first_name'] ); ?></a><?php endif; ?>
	<?php if ( $made['details']['email'] ) : ?><a class="btn alt" href="mailto:<?php echo esc_attr( $made['details']['email'] ); ?>?subject=<?php echo rawurlencode( 'Confirm your Mecca Limo booking' ); ?>&amp;body=<?php echo rawurlencode( $msg ); ?>">✉️ Email it</a><?php endif; ?>
	<button type="button" class="btn alt" onclick="navigator.clipboard.writeText(<?php echo esc_attr( wp_json_encode( $made['link'] ) ); ?>);this.textContent='Copied ✓'">📋 Copy link</button>
	<a class="btn alt" href="<?php echo esc_url( add_query_arg( 'view', $made['id'], $self ) ); ?>">Open booking</a>
	<a class="btn alt" href="<?php echo esc_url( $self ); ?>">Done</a>
<?php elseif ( $from ) :
	list( $pu, $do ) = $route( $pre );
	$nm = trim( ( $pre['first_name'] ?? '' ) . ' ' . ( $pre['last_name'] ?? '' ) );
	?>
	<div class="card"><div class="sum"><div class="av"><?php echo esc_html( strtoupper( mb_substr( $nm, 0, 1 ) ) ); ?></div><div><div class="name"><?php echo esc_html( $nm ); ?></div><div class="meta" style="margin:0"><?php echo esc_html( $pre['trip_date'] ?? '' ); ?></div></div></div>
	<?php if ( $pu || $do ) : ?><div class="route" style="margin-top:12px"><?php if ( $pu ) : ?><div><?php echo esc_html( $pu ); ?></div><?php endif; ?><?php if ( $do ) : ?><div><?php echo esc_html( $do ); ?></div><?php endif; ?></div><?php endif; ?>
	<?php if ( 'sent' === $fst ) : ?><p class="hint" style="margin:10px 0 0">Editing an existing link — the customer's link stays the same.</p><?php endif; ?></div>
	<form method="post" id="bk" action="<?php echo esc_url( add_query_arg( 'from', $from, $self ) ); ?>"><?php wp_nonce_field( 'mecca_bc_staff', '_sn' ); ?>
	<div class="field price"><label>Price</label><input name="price" value="<?php echo $v( 'price' ); ?>" placeholder="$0" inputmode="text" autocomplete="off" <?php echo empty( $pre['price'] ) ? 'autofocus' : ''; ?>></div>
	<div class="field"><label>Vehicle</label><div class="seg"><?php foreach ( array( 'Executive Sedan' => 'Sedan', 'Luxury SUV' => 'SUV', 'Mercedes Sprinter' => 'Sprinter' ) as $o => $short ) : ?><label><input type="radio" name="vehicle" value="<?php echo esc_attr( $o ); ?>" <?php checked( ( $pre['vehicle'] ?? 'Executive Sedan' ), $o ); ?>><span><?php echo esc_html( $short ); ?></span></label><?php endforeach; ?></div></div>
	<details class="more"<?php echo ( empty( $pre['first_name'] ) || empty( $pre['last_name'] ) || empty( $pre['trip_date'] ) || empty( $pre['pickup'] ) ) ? ' open' : ''; ?>><summary>Edit trip details ›</summary>
	<?php mecca_bc_app_fields( $v, $pre ); ?>
	</details>
	</form>
	<div class="dock"><button class="btn" form="bk">Create booking link</button></div>
<?php elseif ( 'schedule' === $tab ) :
	// Every signed (or in-progress) trip from today on, grouped by day.
	$days = array();
	$now  = strtotime( 'today', current_time( 'timestamp' ) );
	foreach ( $all as $q ) {
		$st = mecca_bc_stage( $q->ID );
		if ( ! in_array( $st, array( 'sent', 'confirmed', 'la' ), true ) ) {
			continue;
		}
		$qd = (array) get_post_meta( $q->ID, '_bc_details', true );
		$ts = mecca_bc_trip_ts( $qd );
		if ( $ts && $ts < $now ) {
			continue;
		}
		$days[ $ts ? date( 'Y-m-d', $ts ) : 'zzzz' ][] = array( $ts, $q, $qd, $st );
	}
	ksort( $days );
	?>
	<div class="search"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg><input type="search" placeholder="Search trips" autocomplete="off" oninput="var t=this.value.toLowerCase();document.querySelectorAll('[data-c]').forEach(function(e){e.style.display=e.dataset.c.indexOf(t)>-1?'':'none'})"></div>
	<?php if ( ! $days ) : ?><div class="empty"><b>No upcoming trips</b>Signed bookings show up here by day.</div><?php endif; ?>
	<?php foreach ( $days as $day => $trips ) :
		usort( $trips, function ( $a, $b ) {
			return $a[0] - $b[0];
		} );
		$dts   = strtotime( $day );
		$title = 'zzzz' === $day ? 'Date to confirm' : ( $dts === $now ? 'Today' : ( $dts === $now + DAY_IN_SECONDS ? 'Tomorrow' : date( 'l, M j', $dts ) ) );
		?>
		<div class="sec"><h2><?php echo esc_html( $title ); ?></h2><span class="badge"><?php echo count( $trips ); ?></span></div>
		<?php foreach ( $trips as $t ) :
			list( $ts, $q, $qd, $st ) = $t;
			list( $pu, $do )          = $route( $qd );
			?>
			<a class="card" style="display:block" data-c="<?php echo esc_attr( strtolower( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) . ' ' . ( $qd['phone'] ?? '' ) . ' ' . $digits( $qd['phone'] ?? '' ) . ' ' . ( $qd['pickup'] ?? '' ) . ' ' . ( $qd['dropoff'] ?? '' ) . ' ' . ( $qd['vehicle'] ?? '' ) ) ); ?>" href="<?php echo esc_url( add_query_arg( 'view', $q->ID, $self ) ); ?>">
				<div class="sum"><div class="av" style="font-size:14px"><?php echo esc_html( $ts ? date( 'g:i', $ts ) : '—' ); ?><br><small style="font-size:10px"><?php echo esc_html( $ts ? date( 'A', $ts ) : '' ); ?></small></div>
				<div style="flex:1;min-width:0"><div class="name" style="margin:0"><?php echo esc_html( trim( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) ) ); ?></div><div class="meta" style="margin:2px 0 0"><?php echo esc_html( $qd['vehicle'] ?? '' ); ?></div></div>
				<span class="tag <?php echo 'sent' === $st ? 'wait' : 'ok'; ?>"><?php echo esc_html( mecca_bc_stage_label( $st ) ); ?></span></div>
				<?php if ( $pu || $do ) : ?><div class="route" style="margin-top:10px"><?php if ( $pu ) : ?><div><?php echo esc_html( $pu ); ?></div><?php endif; ?><?php if ( $do ) : ?><div><?php echo esc_html( $do ); ?></div><?php endif; ?></div><?php endif; ?>
			</a>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php elseif ( 'customers' === $tab ) :
	// One row per customer (matched by phone, else email, else name) with every trip they've had.
	$people = array();
	foreach ( $all as $q ) {
		$qd  = (array) get_post_meta( $q->ID, '_bc_details', true );
		$key = $digits( $qd['phone'] ?? '' ) ?: strtolower( trim( (string) ( $qd['email'] ?? '' ) ) ) ?: strtolower( $q->post_title );
		if ( ! isset( $people[ $key ] ) ) {
			$people[ $key ] = array( 'n' => trim( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) ), 'p' => $qd['phone'] ?? '', 'e' => $qd['email'] ?? '', 'trips' => array(), 'paid' => 0 );
		}
		$people[ $key ]['trips'][] = $q;
		$people[ $key ]['paid']   += mecca_bc_paid_cents( $q->ID );
	}
	uasort( $people, function ( $a, $b ) {
		return strcasecmp( $a['n'], $b['n'] );
	} );
	?>
	<div class="search"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg><input id="cfind" type="search" placeholder="Search customers" autocomplete="off" oninput="var t=this.value.toLowerCase();document.querySelectorAll('[data-c]').forEach(function(e){e.style.display=e.dataset.c.indexOf(t)>-1?'':'none'})"></div>
	<div class="sec"><h2>Customers</h2><span class="badge"><?php echo count( $people ); ?></span></div>
	<?php foreach ( $people as $c ) : $ph = $digits( $c['p'] ); ?>
		<details class="card" data-c="<?php echo esc_attr( strtolower( $c['n'] . ' ' . $c['p'] . ' ' . $ph . ' ' . $c['e'] ) ); ?>">
			<summary style="list-style:none;cursor:pointer"><div class="sum"><div class="av"><?php echo esc_html( strtoupper( mb_substr( $c['n'] ?: '?', 0, 1 ) ) ); ?></div><div style="flex:1;min-width:0"><div class="name" style="margin:0"><?php echo esc_html( $c['n'] ); ?></div><div class="meta" style="margin:2px 0 0"><?php echo count( $c['trips'] ); ?> trip<?php echo 1 === count( $c['trips'] ) ? '' : 's'; ?><?php echo $c['paid'] ? ' · $' . esc_html( number_format( $c['paid'] / 100, 2 ) ) . ' paid' : ''; ?></div></div></div></summary>
			<?php if ( $c['e'] ) : ?><div class="meta"><?php echo esc_html( $c['e'] ); ?></div><?php endif; ?>
			<?php
			// Newest card and ID photo this customer gave on any signed booking.
			$ccard = null;
			$cid   = 0;
			foreach ( $c['trips'] as $q ) {
				$cc = get_post_meta( $q->ID, '_bc_card', true );
				if ( ! $ccard && ! empty( $cc['last4'] ) ) {
					$ccard = $cc;
				}
				if ( ! $cid && get_post_meta( $q->ID, '_bc_id_file', true ) ) {
					$cid = $q->ID;
				}
			}
			?>
			<?php if ( $ccard ) : ?><div class="kv"><span>Card on file</span><b><?php echo esc_html( trim( ucwords( strtolower( str_replace( '_', ' ', $ccard['brand'] ) ) ) . ' •••• ' . $ccard['last4'] ) ); ?></b></div><?php endif; ?>
			<?php if ( $cid ) : $iurl = add_query_arg( 'idimg', $cid, $self ); ?>
				<div class="kv" style="display:block"><span>Driver's license / ID</span><a href="<?php echo esc_url( $iurl ); ?>" target="_blank"><img class="idph" loading="lazy" src="<?php echo esc_url( $iurl ); ?>" alt="Photo ID" onerror="this.replaceWith('Open ID file ›')"></a></div>
			<?php endif; ?>
			<?php if ( $ph ) : ?><div class="acts"><a class="chip" href="tel:<?php echo esc_attr( $ph ); ?>">📞 Call</a><a class="chip" href="sms:<?php echo esc_attr( $ph ); ?>">💬 Text</a></div><?php endif; ?>
			<div style="margin-top:10px">
			<?php foreach ( $c['trips'] as $q ) : $qd = (array) get_post_meta( $q->ID, '_bc_details', true ); $st = mecca_bc_stage( $q->ID ); ?>
				<a class="row" href="<?php echo esc_url( add_query_arg( 'view', $q->ID, $self ) ); ?>"><div class="t"><b><?php echo esc_html( $qd['trip_date'] ?? '' ); ?></b><span><?php echo esc_html( $qd['vehicle'] ?? '' ); ?></span></div><span class="tag <?php echo 'new' === $st ? 'new' : ( in_array( $st, array( 'sent', 'linked' ), true ) ? 'wait' : 'ok' ); ?>"><?php echo esc_html( mecca_bc_stage_label( $st ) ); ?></span></a>
			<?php endforeach; ?>
			</div>
		</details>
	<?php endforeach; ?>
<?php elseif ( 'money' === $tab ) :
	// Everything charged through the app, newest first, plus signed trips with nothing charged yet.
	$rows   = array();
	$unpaid = array();
	foreach ( $all as $q ) {
		foreach ( (array) get_post_meta( $q->ID, '_bc_payments', true ) as $p ) {
			if ( ! empty( $p['id'] ) ) {
				$rows[] = array( strtotime( $p['time'] ), $p, $q );
			}
		}
		if ( in_array( mecca_bc_stage( $q->ID ), array( 'confirmed', 'la', 'done' ), true ) && ! mecca_bc_paid_cents( $q->ID ) ) {
			$unpaid[] = $q;
		}
	}
	usort( $rows, function ( $a, $b ) {
		return $b[0] - $a[0];
	} );
	$today = strtotime( 'today', current_time( 'timestamp' ) );
	$sums  = array( 'Today' => $today, 'This week' => strtotime( 'monday this week', $today ), 'This month' => strtotime( 'first day of this month', $today ) );
	?>
	<div class="search"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg><input type="search" placeholder="Search payments" autocomplete="off" oninput="var t=this.value.toLowerCase();document.querySelectorAll('[data-c]').forEach(function(e){e.style.display=e.dataset.c.indexOf(t)>-1?'':'none'})"></div>
	<div class="two" style="margin-top:6px">
	<?php foreach ( $sums as $lab => $from_ts ) :
		$tot = 0;
		foreach ( $rows as $r ) {
			if ( $r[0] >= $from_ts ) {
				$tot += $r[1]['cents'];
			}
		}
		?>
		<div class="card" style="flex:1;text-align:center;padding:14px 6px"><div class="meta" style="margin:0"><?php echo esc_html( $lab ); ?></div><div class="name" style="color:var(--gold);margin:4px 0 0">$<?php echo esc_html( number_format( $tot / 100 ) ); ?></div></div>
	<?php endforeach; ?>
	</div>
	<?php if ( $unpaid ) : ?>
		<div class="sec"><h2>Signed, not charged yet</h2><span class="badge"><?php echo count( $unpaid ); ?></span></div>
		<div class="card" style="padding:4px 14px">
		<?php foreach ( $unpaid as $q ) : $qd = (array) get_post_meta( $q->ID, '_bc_details', true ); ?>
			<a class="row" data-c="<?php echo esc_attr( strtolower( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) . ' ' . ( $qd['trip_date'] ?? '' ) ) ); ?>" href="<?php echo esc_url( add_query_arg( 'view', $q->ID, $self ) ); ?>"><div class="t"><b><?php echo esc_html( trim( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) ) ); ?></b><span><?php echo esc_html( $qd['trip_date'] ?? '' ); ?></span></div><span class="tag wait">Charge ›</span></a>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="sec"><h2>Payments</h2></div>
	<?php if ( ! $rows ) : ?><div class="empty"><b>No payments yet</b>Charges you make from a booking show up here.</div><?php else : ?>
		<div class="card" style="padding:4px 14px">
		<?php foreach ( $rows as $r ) : list( $ts, $p, $q ) = $r; $qd = (array) get_post_meta( $q->ID, '_bc_details', true ); ?>
			<a class="row" data-c="<?php echo esc_attr( strtolower( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) . ' ' . number_format( $p['cents'] / 100, 2 ) ) ); ?>" href="<?php echo esc_url( add_query_arg( 'view', $q->ID, $self ) ); ?>"><div class="t"><b><?php echo esc_html( trim( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) ) ); ?></b><span><?php echo esc_html( date( 'M j, g:i A', $ts ) ); ?></span></div><b style="color:var(--ok)">$<?php echo esc_html( number_format( $p['cents'] / 100, 2 ) ); ?></b></a>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php else : ?>
	<div class="search"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg><input id="find" type="search" placeholder="Search name or phone" autocomplete="off" enterkeyhint="search"></div>
	<div id="found" style="display:none"></div>
	<div id="home">
	<?php foreach ( array( 'Confirmed — next step' => $next, 'Waiting on customer' => $wait ) as $title => $list ) : if ( $list ) : ?>
		<div class="sec"><h2><?php echo esc_html( $title ); ?></h2><span class="badge"><?php echo count( $list ); ?></span></div>
		<div class="card" style="padding:4px 14px">
		<?php foreach ( $list as $q ) :
			$qd = (array) get_post_meta( $q->ID, '_bc_details', true );
			$st = mecca_bc_stage( $q->ID );
			?>
			<a class="row" href="<?php echo esc_url( add_query_arg( 'view', $q->ID, $self ) ); ?>"><div class="t"><b><?php echo esc_html( trim( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) ) ?: $q->post_title ); ?></b><span><?php echo esc_html( $qd['trip_date'] ?? '' ); ?></span></div><span class="tag <?php echo 'sent' === $st ? 'wait' : 'ok'; ?>"><?php echo esc_html( mecca_bc_stage_label( $st ) ); ?></span></a>
		<?php endforeach; ?>
		</div>
	<?php endif; endforeach; ?>
	<div class="sec"><h2>New requests</h2><?php if ( $quotes ) : ?><span class="badge"><?php echo count( $quotes ); ?></span><?php endif; ?></div>
	<?php foreach ( $quotes as $q ) :
		$qd = (array) get_post_meta( $q->ID, '_bc_details', true );
		list( $pu, $do ) = $route( $qd );
		$ph = $digits( $qd['phone'] ?? '' );
		$nm = trim( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) ) ?: $q->post_title;
		?>
		<div class="card">
			<a class="name" style="display:block" href="<?php echo esc_url( add_query_arg( 'view', $q->ID, $self ) ); ?>"><?php echo esc_html( $nm ); ?> <span style="color:var(--gold)">›</span></a>
			<?php if ( ! empty( $qd['trip_date'] ) ) : ?><div class="when">🗓 <?php echo esc_html( $qd['trip_date'] ); ?></div><?php endif; ?>
			<?php if ( $pu || $do ) : ?><div class="route"><?php if ( $pu ) : ?><div><?php echo esc_html( $pu ); ?></div><?php endif; ?><?php if ( $do ) : ?><div><?php echo esc_html( $do ); ?></div><?php endif; ?></div><?php endif; ?>
			<?php if ( ! empty( $qd['vehicle'] ) ) : ?><div class="meta"><?php echo esc_html( $qd['vehicle'] ); ?></div><?php endif; ?>
			<div class="acts">
				<?php if ( $ph ) : ?><a class="chip" href="tel:<?php echo esc_attr( $ph ); ?>">📞 Call</a><a class="chip" href="sms:<?php echo esc_attr( $ph ); ?>">💬 Text</a><?php endif; ?>
				<a class="chip go" href="<?php echo esc_url( add_query_arg( 'from', $q->ID, $self ) ); ?>">Make link →</a>
			</div>
		</div>
	<?php endforeach; if ( ! $quotes ) : ?><div class="empty"><b>You're all caught up</b>New quote requests from the website appear here.</div><?php endif; ?>
	<details class="manual"><summary>+ Make a booking link by hand</summary>
	<form method="post" action="<?php echo esc_url( $self ); ?>" style="margin-top:8px"><?php wp_nonce_field( 'mecca_bc_staff', '_sn' ); ?>
	<?php mecca_bc_app_fields( $v, $pre ); ?>
	<div class="two"><div class="field"><label>Vehicle</label><select name="vehicle"><option>Executive Sedan</option><option>Luxury SUV</option><option>Mercedes Sprinter</option></select></div><div class="field"><label>Price</label><input name="price" placeholder="$450 total"></div></div>
	<button class="btn">Create booking link</button></form>
	</details>
	</div>
	<?php
	$pool = array();
	foreach ( $all as $q ) {
		$st = mecca_bc_stage( $q->ID );
		$qd = (array) get_post_meta( $q->ID, '_bc_details', true );
		$pool[] = array(
			'n' => trim( ( $qd['first_name'] ?? '' ) . ' ' . ( $qd['last_name'] ?? '' ) ) ?: $q->post_title,
			'd' => (string) ( $qd['trip_date'] ?? '' ),
			's' => strtolower( $q->post_title . ' ' . ( $qd['phone'] ?? '' ) . ' ' . $digits( $qd['phone'] ?? '' ) . ' ' . ( $qd['email'] ?? '' ) ),
			't' => 'new' === $st ? 'new' : ( in_array( $st, array( 'sent', 'linked' ), true ) ? 'wait' : 'ok' ),
			'l' => mecca_bc_stage_label( $st ),
			'u' => add_query_arg( 'view', $q->ID, $self ),
		);
	}
	?>
	<script>
	(function(){var P=<?php echo wp_json_encode( $pool ); ?>,f=document.getElementById('find'),o=document.getElementById('found'),h=document.getElementById('home');
	function e(s){return String(s).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]})}
	f.addEventListener('input',function(){var t=f.value.trim().toLowerCase();if(t.length<2){o.style.display='none';h.style.display='';return}
	var r=P.filter(function(x){return x.s.indexOf(t)>-1||x.s.indexOf(t.replace(/\D/g,'')||'~')>-1}).slice(0,25),s='<div class="card" style="padding:4px 14px">';
	r.forEach(function(x){var row='<div class="t"><b>'+e(x.n)+'</b><span>'+e(x.d)+'</span></div><span class="tag '+x.t+'">'+e(x.l)+'</span>';s+=x.u?'<a class="row" href="'+e(x.u)+'">'+row+'</a>':'<div class="row">'+row+'</div>'});
	s+='</div>';o.innerHTML=r.length?s:'<div class="empty"><b>No match</b>Nobody found for “'+e(f.value)+'”.</div>';o.style.display='';h.style.display='none'});})();
	document.addEventListener('visibilitychange',function(){if(!document.hidden&&!document.getElementById('find').value&&!document.querySelector('details[open]'))location.reload()});
	</script>
<?php endif; ?>
</main>
<?php if ( ! $view && ! $from && ! $made ) : ?>
<nav class="tabs"><?php foreach ( array( '' => array( '📥', 'Bookings' ), 'schedule' => array( '🗓', 'Schedule' ), 'customers' => array( '👥', 'Customers' ), 'money' => array( '💳', 'Money' ) ) as $k => $t ) : ?><a class="<?php echo $tab === $k ? 'on' : ''; ?>" href="<?php echo esc_url( $k ? add_query_arg( 'tab', $k, $self ) : $self ); ?>"><span><?php echo $t[0]; ?></span><?php echo esc_html( $t[1] ); ?></a><?php endforeach; ?></nav>
<?php endif; ?>
</body></html>
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
	// The quote now shows up in the Mecca app (/new-booking); the email stays button-free so replies are safe.
	return $args;
}, 6 );

/* ---------------------------------------------------------------- Admin */

add_action( 'admin_menu', function () {
	add_menu_page( 'Mecca Bookings', 'Mecca Bookings', 'manage_options', 'mecca-bookings', 'mecca_bc_admin_list', 'dashicons-car', 3 );
	add_submenu_page( 'mecca-bookings', 'Open the app', 'Open the app', 'manage_options', 'mecca-bookings-app', function () {
		echo '<script>location.href=' . wp_json_encode( home_url( '/new-booking/' ) ) . '</script>';
	} );
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
		$ext = strtolower( pathinfo( (string) get_post_meta( $id, '_bc_id_file', true ), PATHINFO_EXTENSION ) );
		$img = in_array( $ext, array( 'jpg', 'jpeg', 'png', 'webp' ), true ) ? '<a href="' . esc_url( $url ) . '" target="_blank"><img src="' . esc_url( $url ) . '" style="max-width:420px;width:100%;border:1px solid #ccd0d4;border-radius:6px;display:block;margin-bottom:8px" alt="Photo ID"></a>' : '';
		echo '<tr><th>Photo ID</th><td>' . $img . '<a class="button" href="' . esc_url( $url ) . '" target="_blank">View ID</a> <a class="button" href="' . esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=mecca_bc_id_delete&id=' . $id ), 'mecca_bc_id_del_' . $id ) ) . '" onclick="return confirm(\'Delete this ID photo permanently?\')">Delete ID photo</a><p class="description">Delete the ID once the trip is done. There\'s no reason to keep it.</p></td></tr>';
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

/**
 * Charge the card a customer saved on their confirm page. Staff enter the amount and
 * confirm it in the app; nothing is ever charged automatically. $token makes a double
 * tap or a re-sent form reuse the same Square idempotency key, so it charges once.
 * Returns '' on success or an error message.
 */
function mecca_bc_charge( $id, $cents, $token ) {
	$card = (array) get_post_meta( $id, '_bc_card', true );
	if ( empty( $card['card_id'] ) ) {
		return 'No card on file for this booking.';
	}
	if ( $cents < 100 || $cents > 500000 ) {
		return 'Enter an amount between $1 and $5,000.';
	}
	$res = mecca_bc_square( 'POST', '/v2/payments', array(
		'idempotency_key' => 'chg-' . $id . '-' . substr( md5( $token ), 0, 20 ),
		'source_id'       => $card['card_id'],
		'customer_id'     => $card['customer_id'],
		'amount_money'    => array( 'amount' => (int) $cents, 'currency' => 'USD' ),
		'autocomplete'    => true,
		'reference_id'    => 'mecca-booking-' . $id,
		'note'            => 'Mecca Limo: ' . get_the_title( $id ),
	) );
	if ( empty( $res['payment']['id'] ) ) {
		return 'Square declined it: ' . ( $res['errors'][0]['detail'] ?? 'unknown error' );
	}
	$pays = (array) get_post_meta( $id, '_bc_payments', true );
	foreach ( $pays as $p ) {
		if ( ( $p['id'] ?? '' ) === $res['payment']['id'] ) {
			return ''; // Same request sent twice: Square returned the original payment.
		}
	}
	$pays[] = array(
		'id'      => $res['payment']['id'],
		'cents'   => (int) $cents,
		'status'  => $res['payment']['status'] ?? '',
		'receipt' => $res['payment']['receipt_url'] ?? '',
		'time'    => current_time( 'mysql' ),
	);
	update_post_meta( $id, '_bc_payments', $pays );
	return '';
}

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
<meta name="theme-color" content="#050505"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<style>
:root{--gold:#e3c27d;--goldg:linear-gradient(135deg,#f3dfa8 0%,#d4ad5e 45%,#a7802f 100%);--bg:#050505;--card:#0d0d0e;--card2:#121213;--line:rgba(227,194,125,.16);--text:#f7f3ea;--muted:#8f8a80}
*{box-sizing:border-box}html,body{overflow-x:hidden}
body{margin:0;background:radial-gradient(90% 40% at 50% -6%,rgba(227,194,125,.12) 0%,transparent 60%),var(--bg);color:var(--text);font:16px/1.5 -apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,sans-serif;-webkit-font-smoothing:antialiased;min-height:100vh}
.wrap{max-width:560px;margin:0 auto;padding:calc(env(safe-area-inset-top) + 26px) 18px calc(env(safe-area-inset-bottom) + 48px)}
.logo{display:block;width:180px;margin:6px auto 22px;filter:drop-shadow(0 8px 24px rgba(227,194,125,.18))}
h1{font:500 30px/1.2 "Didot","Bodoni 72",Georgia,serif;margin:0 0 8px;text-align:center;letter-spacing:.01em}
.sub{color:var(--muted);text-align:center;margin:0 auto 26px;max-width:420px}
.rule{width:56px;height:1px;margin:0 auto 22px;background:var(--goldg)}
.box{background:linear-gradient(180deg,var(--card2),var(--card));border:1px solid var(--line);border-radius:18px;padding:20px;margin:0 0 16px;box-shadow:inset 0 1px 0 rgba(255,255,255,.04),0 18px 40px -26px #000}
.box h2{display:flex;align-items:center;gap:10px;font:600 12px -apple-system,sans-serif;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);margin:0 0 14px}
.box h2 i{font-style:normal;display:grid;place-items:center;width:24px;height:24px;border-radius:50%;background:var(--goldg);color:#1a1307;font-size:12px;letter-spacing:0}
.row{display:flex;justify-content:space-between;gap:14px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.06)}.row:last-child{border:0}.row span{color:var(--muted);font-size:14px}.row b{text-align:right;font-weight:600}
.row.price b{font:600 22px -apple-system,sans-serif;background:var(--goldg);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
label{display:block;font-weight:600;margin:14px 0 8px;font-size:15px}
input[type=text]{width:100%;padding:14px;border-radius:12px;border:1px solid var(--line);background:#0a0a0b;color:var(--text);font-size:16px;outline:none}
input[type=text]:focus{border-color:rgba(227,194,125,.65);box-shadow:0 0 0 4px rgba(227,194,125,.1)}
.drop{display:flex;flex-direction:column;align-items:center;gap:6px;padding:22px 14px;border:1.5px dashed rgba(227,194,125,.35);border-radius:14px;text-align:center;color:var(--muted);cursor:pointer;margin:0;font-weight:500}
.drop b{color:var(--text)}.drop.has{border-style:solid;border-color:var(--gold);color:var(--gold)}
.drop input{position:absolute;opacity:0;width:1px;height:1px}
#card-container{min-height:96px}.check{display:flex;gap:12px;align-items:flex-start;font-weight:400;color:#cfcac0;font-size:14px}.check input{margin-top:2px;width:20px;height:20px;accent-color:#d4ad5e;flex:none}
.terms{font-size:13px;color:var(--muted);max-height:150px;overflow:auto;border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:12px;background:#0a0a0b}
button{width:100%;padding:18px;border:0;border-radius:14px;background:var(--goldg);color:#1a1307;font:700 17px -apple-system,sans-serif;letter-spacing:.02em;cursor:pointer;margin-top:6px;box-shadow:0 14px 34px -14px rgba(227,194,125,.5),inset 0 1px 0 rgba(255,255,255,.45);transition:transform .12s}button:active{transform:scale(.98)}button:disabled{opacity:.6}
.err{background:rgba(160,40,40,.15);border:1px solid rgba(220,90,90,.5);color:#ffd7d7;border-radius:12px;padding:12px;margin:0 0 16px}
.ok{text-align:center;padding:30px 20px}.ok .tick{width:76px;height:76px;margin:0 auto 16px;border-radius:50%;display:grid;place-items:center;background:var(--goldg);color:#1a1307;font-size:36px;box-shadow:0 16px 40px -14px rgba(227,194,125,.6)}
.note{font-size:13px;color:var(--muted);text-align:center;margin-top:16px}.note a{color:var(--gold);text-decoration:none}
.sandbox{background:rgba(227,194,125,.08);border:1px solid var(--line);color:var(--gold);border-radius:12px;padding:8px 12px;font-size:13px;margin-bottom:14px;text-align:center}
.foot{margin-top:28px;text-align:center;font:500 11px -apple-system,sans-serif;letter-spacing:.2em;text-transform:uppercase;color:#5f5b54}
</style></head><body><div class="wrap"><img class="logo" src="<?php echo esc_url( $logo ); ?>" alt="Mecca Limo">
<?php if ( ! $post ) : ?>
	<h1>Link not found</h1><p class="sub">This booking link is invalid or has expired. Please call or text <a style="color:var(--gold)" href="tel:+18438041188">(843) 804-1188</a>.</p>
<?php elseif ( $done ) : ?>
	<div class="box ok"><div class="tick">✓</div><h1>You're all set, <?php echo esc_html( $d['first_name'] ?? '' ); ?></h1><p class="sub">Your booking is confirmed and your card is saved on file. Nothing has been charged yet. We'll be in touch before your ride.</p></div>
	<p class="note">Questions? Call or text <a style="color:var(--gold)" href="tel:+18438041188">(843) 804-1188</a></p>
<?php else : ?>
	<h1>Confirm your booking</h1><div class="rule"></div><p class="sub">Hi <?php echo esc_html( $d['first_name'] ?? '' ); ?>, please review your trip and complete the steps below.</p>
	<?php if ( $sandbox ) : ?><div class="sandbox">Test mode — no real cards are saved.</div><?php endif; ?>
	<?php if ( $err ) : ?><div class="err"><?php echo esc_html( $err ); ?></div><?php endif; ?>
	<div class="box"><h2>Your trip</h2>
		<?php foreach ( array( 'trip_date' => 'Date & time', 'pickup' => 'Pickup', 'dropoff' => 'Drop-off', 'vehicle' => 'Vehicle', 'price' => 'Quoted price' ) as $k => $lab ) : if ( ! empty( $d[ $k ] ) ) : ?>
			<div class="row<?php echo 'price' === $k ? ' price' : ''; ?>"><span><?php echo esc_html( $lab ); ?></span><b><?php echo esc_html( $d[ $k ] ); ?></b></div>
		<?php endif; endforeach; ?>
		<?php if ( ! empty( $d['notes'] ) ) : ?><p style="color:var(--muted);font-size:14px;margin:10px 0 0"><?php echo nl2br( esc_html( $d['notes'] ) ); ?></p><?php endif; ?>
	</div>
	<form id="bf" method="post" enctype="multipart/form-data" action="<?php echo esc_url( home_url( '/confirm/?t=' . get_post_meta( $post->ID, '_bc_token', true ) ) ); ?>">
	<?php wp_nonce_field( 'mecca_bc_confirm_' . $post->ID, '_bcn' ); ?>
	<input type="hidden" name="card_nonce" id="card_nonce"><input type="hidden" name="verification_token" id="verification_token">
	<div class="box"><h2><i>1</i>Card on file</h2><p style="color:var(--muted);font-size:14px;margin:0 0 10px">Your card is saved securely by Square. <strong>Nothing is charged now.</strong></p><div id="card-container"></div><p style="color:var(--muted);font-size:13px;margin:6px 0 0">On iPhone, tap <b style="color:var(--text)">Card number</b>, then <b style="color:var(--text)">AutoFill</b> above the keyboard → <b style="color:var(--text)">Scan Card</b> to fill it with your camera.</p></div>
	<div class="box"><h2><i>2</i>Photo ID</h2><label class="drop" for="photo_id" id="dropl"><span style="font-size:26px">🪪</span><b id="dropt">Tap to add your driver's license</b><span>Take a photo or choose one</span><input type="file" id="photo_id" name="photo_id" accept="image/*,.pdf" required onchange="var l=document.getElementById('dropl');if(this.files[0]){l.classList.add('has');document.getElementById('dropt').textContent='✓ '+this.files[0].name;}"></label><p style="color:var(--muted);font-size:13px;margin:8px 0 0">Kept private and deleted after your trip.</p></div>
	<div class="box"><h2><i>3</i>Agreement</h2>
		<div class="terms">By confirming, you authorize Mecca Limo to store this card with Square and to charge it for this reservation, including the agreed fare, any approved extra time, and cancellation fees under our policy: a $150 non-refundable deposit; 50% of the fare for cancellations within 30 days of the trip; 100% within 15 days. Quotes are honored for 48 hours. A 15-minute grace period applies at pickup. Full policy: meccalimo.com/policy.</div>
		<label class="check"><input type="checkbox" name="agree" value="1" required> I agree to the terms above and authorize Mecca Limo to keep my card on file for this booking.</label>
		<label for="signature">Type your full name to sign</label><input type="text" id="signature" name="signature" required autocomplete="name">
	</div>
	<button type="submit" id="go">Confirm booking</button>
	<p class="note">🔒 Card details go directly to Square and never touch our servers.</p><p class="foot">Mecca Limo · Charleston, SC</p>
	</form>
	<script src="https://<?php echo $sandbox ? 'sandbox.' : ''; ?>web.squarecdn.com/v1/square.js"></script>
	<script>
	(async function(){
		var form=document.getElementById('bf'),btn=document.getElementById('go');
		if(!window.Square){btn.disabled=true;btn.textContent='Card form unavailable — please call (843) 804-1188';return;}
		var payments=Square.payments(<?php echo wp_json_encode( mecca_bc_opt( 'app_id' ) ); ?>,<?php echo wp_json_encode( mecca_bc_opt( 'location_id' ) ); ?>);
		var card;try{card=await payments.card({style:{'.input-container':{borderColor:'#2b261c',borderRadius:'12px'},'.input-container.is-focus':{borderColor:'#e3c27d'},'.input-container.is-error':{borderColor:'#d96a6a'},'input':{backgroundColor:'#0a0a0b',color:'#f7f3ea',fontSize:'16px'},'input::placeholder':{color:'#7d786f'},'.message-text':{color:'#8f8a80'},'.message-icon':{color:'#8f8a80'},'.message-text.is-error':{color:'#ff9b9b'},'.message-icon.is-error':{color:'#ff9b9b'}}});}catch(z){card=await payments.card();}await card.attach('#card-container');
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

/* "Remember Me" keeps you logged in for a year, so the booking page opens without logging in each time. */
add_filter( 'auth_cookie_expiration', function ( $ttl, $user_id, $remember ) {
	return $remember ? YEAR_IN_SECONDS : $ttl;
}, 10, 3 );
