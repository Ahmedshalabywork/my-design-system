<?php
/**
 * Plugin Name: Mecca Limo Quote Form
 * Description: "Get a Quote" form ([mecca_quote_form]) with email/phone validation; sends requests to info@meccalimo.com and a confirmation to the customer.
 * Version: 1.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MECCA_QF_TO = 'info@meccalimo.com';

function mecca_qf_hourly() {
	return array( 'Wedding', 'Hourly Charter', 'Night Out / Party Bus', 'Corporate', 'Prom / Special Event', 'Sightseeing / Tour', 'Golf Course', 'Other' );
}

function mecca_qf_services() {
	return array(
		'Airport Transfer',
		'Wedding',
		'Hourly Charter',
		'Night Out / Party Bus',
		'Corporate',
		'Prom / Special Event',
		'Cruise Port Transfer',
		'Hotel Shuttle',
		'Golf Course',
		'Sightseeing / Tour',
		'Other',
	);
}

/** Valid US/Canada number: 10 digits (optionally leading 1), NANP area code and exchange rules. Returns formatted number or false. */
function mecca_qf_phone( $raw ) {
	$d = preg_replace( '/\D/', '', (string) $raw );
	if ( strlen( $d ) === 11 && $d[0] === '1' ) {
		$d = substr( $d, 1 );
	}
	if ( strlen( $d ) !== 10 ) {
		return false;
	}
	if ( ! preg_match( '/^[2-9]\d{2}[2-9]\d{6}$/', $d ) ) {
		return false;
	}
	if ( preg_match( '/^(\d)\1{9}$/', $d ) || substr( $d, 3, 3 ) === '555' && substr( $d, 6, 2 ) === '01' ) {
		return false;
	}
	return sprintf( '(%s) %s-%s', substr( $d, 0, 3 ), substr( $d, 3, 3 ), substr( $d, 6 ) );
}

/** Valid email syntax and a domain that can actually receive mail. */
function mecca_qf_email( $raw ) {
	$e = sanitize_email( $raw );
	if ( ! $e || ! is_email( $e ) ) {
		return false;
	}
	$domain = substr( strrchr( $e, '@' ), 1 );
	if ( function_exists( 'checkdnsrr' ) && ! checkdnsrr( $domain, 'MX' ) && ! checkdnsrr( $domain, 'A' ) ) {
		return false;
	}
	return $e;
}

function mecca_qf_handle() {
	$f      = array();
	$errors = array();
	$p      = wp_unslash( $_POST );

	// Spam traps: hidden field must stay empty; form must take more than 3 seconds.
	if ( ! empty( $p['mecca_qf_website'] ) || ( time() - (int) ( $p['mecca_qf_t'] ?? 0 ) ) < 3 ) {
		return array( 'sent' => true, 'values' => array() );
	}

	$text = array( 'pickup', 'stop', 'dropoff', 'date', 'time', 'service', 'hours', 'passengers', 'first_name', 'last_name', 'email', 'phone', 'notes', 'flight', 'return_date', 'return_time' );
	foreach ( $text as $k ) {
		$f[ $k ] = isset( $p[ $k ] ) ? ( $k === 'notes' ? sanitize_textarea_field( $p[ $k ] ) : sanitize_text_field( $p[ $k ] ) ) : '';
	}
	$f['round_trip'] = ! empty( $p['round_trip'] );
	$f['sms_ok']     = ! empty( $p['sms_ok'] );

	foreach ( array( 'pickup' => 'Pick-up location', 'dropoff' => 'Drop-off location', 'date' => 'Date of service', 'time' => 'Pick-up time', 'service' => 'Type of service', 'first_name' => 'First name', 'last_name' => 'Last name' ) as $k => $label ) {
		if ( $f[ $k ] === '' ) {
			$errors[ $k ] = $label . ' is required.';
		}
	}
	if ( $f['service'] !== '' && ! in_array( $f['service'], mecca_qf_services(), true ) ) {
		$errors['service'] = 'Please choose a type of service.';
	}
	if ( $f['date'] !== '' ) {
		$ts = strtotime( $f['date'] );
		if ( ! $ts || $ts < strtotime( 'today', current_time( 'timestamp' ) ) ) {
			$errors['date'] = 'Please choose today or a future date.';
		}
	}
	if ( $f['round_trip'] ) {
		if ( $f['return_date'] === '' ) {
			$errors['return_date'] = 'Return date is required for a round trip.';
		} elseif ( $f['date'] !== '' && strtotime( $f['return_date'] ) < strtotime( $f['date'] ) ) {
			$errors['return_date'] = 'Return date must be on or after the date of service.';
		}
		if ( $f['return_time'] === '' ) {
			$errors['return_time'] = 'Return time is required for a round trip.';
		}
	} else {
		$f['return_date'] = '';
		$f['return_time'] = '';
	}
	if ( 'Airport Transfer' !== $f['service'] ) {
		$f['flight'] = '';
	}
	if ( ! in_array( $f['service'], mecca_qf_hourly(), true ) ) {
		$f['hours'] = '';
	}
	$pax = (int) $f['passengers'];
	if ( ! preg_match( '/^\d{1,2}$/', trim( $f['passengers'] ) ) || $pax < 1 || $pax > 99 ) {
		$errors['passengers'] = 'Please enter the number of passengers (1-99).';
	}
	$email = mecca_qf_email( $f['email'] );
	if ( ! $email ) {
		$errors['email'] = 'Please enter a valid email address (for example name@gmail.com).';
	}
	$phone = mecca_qf_phone( $f['phone'] );
	if ( ! $phone ) {
		$errors['phone'] = 'Please enter a valid 10-digit US phone number (for example 843-804-1188).';
	}

	if ( $errors ) {
		return array( 'errors' => $errors, 'values' => $f );
	}

	$name     = $f['first_name'] . ' ' . $f['last_name'];
	$date_txt = date_i18n( 'l, F j, Y', strtotime( $f['date'] ) );
	$time_txt = date_i18n( 'g:i A', strtotime( '2000-01-01 ' . $f['time'] ) );
	$tel      = '+1' . preg_replace( '/\D/', '', $phone );
	$font     = 'font-family:Arial,Helvetica,sans-serif;color:#222;font-size:14px;line-height:1.2';
	$row      = function ( $label, $val ) {
		return '<tr><td style="padding:0 10px 1px 0;font-weight:bold;white-space:nowrap;vertical-align:top;width:120px">' . esc_html( $label ) . ':</td><td style="padding:0 0 1px;vertical-align:top">' . $val . '</td></tr>';
	};
	$section  = function ( $title, $rows ) {
		return '<h3 style="margin:8px 0 2px;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#9a7a2e;border-bottom:1px solid #e5e5e5;padding-bottom:1px;line-height:1.2">' . esc_html( $title ) . '</h3><table cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:100%;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.2;color:#222">' . $rows . '</table>';
	};
	$e = function ( $v ) {
		return nl2br( esc_html( (string) $v ) );
	};
	$signature = '<p style="margin:10px 0 0;font-family:Georgia,\'Times New Roman\',serif;font-size:14px;line-height:1.2;color:#222">Regards,<br><strong>Mecca Limo Chauffeur Service</strong><br>Charleston, SC<br>Email: <a href="mailto:info@meccalimo.com" style="color:#222">info@meccalimo.com</a><br>C: <a href="tel:+18438041188" style="color:#222">(843) 804-1188</a><br><a href="https://www.meccalimo.com" style="color:#7b3fe4;font-weight:bold">Meccalimo.com</a></p><p style="margin:4px 0 0"><img src="https://www.meccalimo.com/wp-content/uploads/2026/10/mecca-limo-logo-gold.png" alt="Mecca Limo Service" width="150" height="71" style="display:block;border:0;outline:none"></p>';
	$page = function ( $inner ) use ( $font ) {
		return '<div style="background:#ffffff;padding:8px"><div style="max-width:600px;margin:0 auto;' . $font . '">' . $inner . '</div></div>';
	};

	$line = function ( $label, $val ) {
		return '<div style="margin:0;padding:0"><span style="font-weight:bold">' . esc_html( $label ) . ':</span> ' . $val . '</div>';
	};
	$when = esc_html( date_i18n( 'l, m/d/y', strtotime( $f['date'] ) ) . ' ' . $time_txt );
	$admin_lines = $line( 'Registration type', $e( $f['service'] ) )
		. '<div style="height:8px"></div>'
		. $line( 'First Name', $e( $f['first_name'] ) )
		. $line( 'Last Name', $e( $f['last_name'] ) )
		. $line( 'Phone Number', '<a href="tel:' . esc_attr( $tel ) . '" style="color:#1a6dcc">' . esc_html( $phone ) . '</a>' )
		. $line( 'Email Address', '<a href="mailto:' . esc_attr( $email ) . '" style="color:#1a6dcc">' . esc_html( $email ) . '</a>' )
		. $line( 'How Many People', (int) $pax )
		. $line( 'Pickup Location', $e( $f['pickup'] ) )
		. $line( 'Drop Off Location', $e( $f['dropoff'] ) )
		. $line( 'Hourly Option', $f['hours'] ? $e( $f['hours'] ) : '' )
		. $line( 'Date & Time', $when )
		. $line( 'Stops', $f['stop'] ? $e( $f['stop'] ) : '' )
		. ( $f['flight'] ? $line( 'Flight Number', $e( $f['flight'] ) ) : '' )
		. ( $f['round_trip'] ? $line( 'Round Trip Return', esc_html( date_i18n( 'l, m/d/y', strtotime( $f['return_date'] ) ) . ' ' . date_i18n( 'g:i A', strtotime( '2000-01-01 ' . $f['return_time'] ) ) ) ) : '' )
		. $line( 'OK to Text', $f['sms_ok'] ? 'Yes' : 'No' )
		. '<div style="height:8px"></div>'
		. $line( 'Message', $f['notes'] ? $e( $f['notes'] ) : '' );
	$admin_body = '<div style="background:#ffffff;padding:8px"><div style="max-width:600px;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.35;color:#222">' . $admin_lines . '<div style="margin-top:12px"><img src="https://www.meccalimo.com/wp-content/uploads/2026/10/mecca-limo-logo-gold.png" alt="Mecca Limo Service" width="130" height="62" style="display:block;border:0;outline:none"></div></div></div>';
	$headers = array( 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' );
	$sent    = wp_mail( MECCA_QF_TO, 'New Quote Request: ' . $f['service'] . ' - ' . $name . ' (' . date_i18n( 'm/d/y', strtotime( $f['date'] ) ) . ')', $admin_body, $headers );

	if ( ! $sent ) {
		return array( 'errors' => array( 'form' => 'Sorry, we could not send your request. Please call us at (843) 804-1188.' ), 'values' => $f );
	}

	$customer_body = $page(
		'<p style="margin:0 0 4px;font-size:14px">Hello ' . esc_html( $f['first_name'] ) . ',</p>'
		. '<p style="margin:0 0 4px;font-size:14px">We have received your request and will be in contact with you shortly.</p>'
		. $signature
	);
	wp_mail( $email, 'We received your request - Mecca Limo', $customer_body, array( 'Content-Type: text/html; charset=UTF-8', 'Reply-To: Mecca Limo <' . MECCA_QF_TO . '>' ) );

	return array( 'sent' => true, 'values' => array() );
}

function mecca_qf_shortcode( $atts = array() ) {
	$atts = shortcode_atts( array( 'heading' => '1' ), $atts );
	$state = array( 'errors' => array(), 'values' => array() );
	if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && isset( $_POST['mecca_qf_submit'] ) ) {
		$state = array_merge( $state, mecca_qf_handle() );
	}
	$v = function ( $k, $d = '' ) use ( $state ) {
		return esc_attr( $state['values'][ $k ] ?? $d );
	};
	$err = function ( $k ) use ( $state ) {
		return isset( $state['errors'][ $k ] ) ? '<span class="mqf-err">' . esc_html( $state['errors'][ $k ] ) . '</span>' : '';
	};

	ob_start();
	?>
	<style>
	.mqf{max-width:640px;margin:0 auto;background:#1c1c1c;border:2px solid #DCAD4F;border-radius:14px;padding:28px;color:#fff;font-family:inherit;box-shadow:0 10px 30px rgba(0,0,0,.5)}
	.mqf h2{color:#DCAD4F;text-align:center;margin:0 0 6px;font-size:28px}
	.mqf .mqf-sub{text-align:center;color:#ccc;margin:0 0 22px}
	.mqf label{display:block;color:#DCAD4F;font-weight:600;margin:14px 0 6px}
	.mqf input[type=number],.mqf input[type=text],.mqf input[type=email],.mqf input[type=tel],.mqf input[type=date],.mqf input[type=time],.mqf select,.mqf textarea{width:100%;box-sizing:border-box;padding:12px 14px;border-radius:8px;border:1px solid #555;background:#fff;color:#111;font-size:16px;font-family:Arial,Helvetica,sans-serif}
	.mqf input:focus,.mqf select:focus,.mqf textarea:focus{outline:none;border-color:#DCAD4F;box-shadow:0 0 0 3px rgba(220,173,79,.35)}
	.mqf .mqf-row{display:flex;gap:14px}.mqf .mqf-row>div{flex:1}
	.mqf .mqf-check{display:flex;align-items:flex-start;gap:10px;margin:14px 0 0;color:#fff;font-weight:400}
	.mqf .mqf-check input{margin-top:4px;width:18px;height:18px;accent-color:#DCAD4F}
	.mqf .mqf-err{display:block;color:#ff7b7b;font-size:14px;margin-top:6px}
	.mqf .mqf-bad{border-color:#ff7b7b!important}
	.mqf button{display:block;width:100%;margin-top:24px;padding:16px;border:0;border-radius:30px;background:#DCAD4F;color:#111;font-size:18px;font-weight:700;letter-spacing:1px;cursor:pointer;text-transform:uppercase}
	.mqf button:hover{background:#e9c46a}
	.mqf .mqf-alert{background:#3a1d1d;border:1px solid #ff7b7b;color:#ffd0d0;padding:12px 14px;border-radius:8px;margin-bottom:12px}
	.mqf .mqf-ok{text-align:center;padding:30px 10px}.mqf .mqf-ok h2{margin-bottom:12px}
	.mqf .mqf-hp{position:absolute!important;left:-9999px!important}
	.mqf-trust{max-width:640px;margin:0 auto 18px;display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
	.mqf-trust div{background:#1c1c1c;border:1px solid #3a3a3a;border-radius:10px;padding:12px 6px;text-align:center;color:#fff;font-size:13px;line-height:1.3}
	.mqf-trust svg{display:block;margin:0 auto 6px;width:26px;height:26px;fill:none;stroke:#DCAD4F;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
	.mqf-trust b{display:block;color:#DCAD4F;font-size:14px}
	.mqf [hidden]{display:none!important}
	.mqf .mqf-cond{display:none}.mqf .mqf-cond.on{display:block}
	.mqf .mqf-addstop{display:inline-block;margin-top:10px;color:#DCAD4F;cursor:pointer;font-size:15px;background:none;border:0;padding:0;width:auto;text-transform:none;letter-spacing:0;font-weight:600}
	.mqf .mqf-addstop:hover{text-decoration:underline;background:none}
	@media(max-width:600px){.mqf-trust{gap:6px}.mqf-trust div{font-size:11px;padding:10px 4px}.mqf-trust b{font-size:12px}}
	.mqf .mqf-note{color:#aaa;font-size:13px;text-align:center;margin-top:14px}
	@media(max-width:600px){.mqf{padding:20px}.mqf .mqf-row{flex-direction:column;gap:0}}
	</style>
	<?php if ( empty( $state['sent'] ) ) : ?>
	<div class="mqf-trust" aria-label="Why choose Mecca Limo">
		<div><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><b>24/7</b>Always available</div>
		<div><svg viewBox="0 0 24 24"><path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-5h4v5"/></svg><b>Family-Owned</b>Local to Charleston</div>
		<div><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg><b>Professional</b>Chauffeurs</div>
	</div>
	<?php endif; ?>
	<div class="mqf" id="get-a-quote">
	<?php if ( ! empty( $state['sent'] ) ) : ?>
		<div class="mqf-ok"><h2>Thank you!</h2><p>We have received your request and will be in contact with you shortly.</p><p>Need us sooner? Call <a href="tel:+18438041188" style="color:#DCAD4F">(843) 804-1188</a>, available 24/7.</p></div>
	<?php else : ?>
		<?php if ( '0' !== $atts['heading'] ) : ?>
		<h2>Get a Quote</h2>
		<p class="mqf-sub">Tell us about your trip and we'll get back to you with prices and availability.</p>
		<?php endif; ?>
		<?php if ( $state['errors'] ) : ?>
			<div class="mqf-alert" role="alert"><?php echo isset( $state['errors']['form'] ) ? esc_html( $state['errors']['form'] ) : 'Please fix the highlighted fields below.'; ?></div>
		<?php endif; ?>
		<form method="post" action="#get-a-quote" novalidate>
			<input type="hidden" name="mecca_qf_t" value="<?php echo esc_attr( time() ); ?>">
			<div class="mqf-hp" aria-hidden="true"><label>Website<input type="text" name="mecca_qf_website" tabindex="-1" autocomplete="off"></label></div>

			<label for="mqf-pickup">Pick-up Location *</label>
			<input type="text" id="mqf-pickup" name="pickup" value="<?php echo $v( 'pickup' ); ?>" placeholder="Address, hotel, or airport (e.g. CHS)" required><?php echo $err( 'pickup' ); ?>

			<button type="button" class="mqf-addstop" data-target="mqf-stopwrap" <?php echo ( $state['values']['stop'] ?? '' ) ? 'hidden' : ''; ?>>+ Add a stop</button>
			<div id="mqf-stopwrap" class="mqf-cond<?php echo ( $state['values']['stop'] ?? '' ) ? ' on' : ''; ?>"><label for="mqf-stop">Extra Stop</label>
			<input type="text" id="mqf-stop" name="stop" value="<?php echo $v( 'stop' ); ?>" placeholder="Any stop along the way"></div>

			<label for="mqf-dropoff">Drop-off Location *</label>
			<input type="text" id="mqf-dropoff" name="dropoff" value="<?php echo $v( 'dropoff' ); ?>" placeholder="Address, hotel, or airport" required><?php echo $err( 'dropoff' ); ?>

			<div class="mqf-row">
				<div><label for="mqf-date">Date of Service *</label><input type="date" id="mqf-date" name="date" value="<?php echo $v( 'date' ); ?>" min="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>" required><?php echo $err( 'date' ); ?></div>
				<div><label for="mqf-time">Pick-up Time *</label><input type="time" id="mqf-time" name="time" value="<?php echo $v( 'time' ); ?>" required><?php echo $err( 'time' ); ?></div>
			</div>

			<label for="mqf-service">Type of Service *</label>
			<select id="mqf-service" name="service" required>
				<option value="">- Please Select -</option>
				<?php foreach ( mecca_qf_services() as $s ) : ?>
					<option <?php selected( $state['values']['service'] ?? '', $s ); ?>><?php echo esc_html( $s ); ?></option>
				<?php endforeach; ?>
			</select><?php echo $err( 'service' ); ?>

			<div id="mqf-flightwrap" class="mqf-cond"><label for="mqf-flight">Flight Number (optional)</label>
			<input type="text" id="mqf-flight" name="flight" value="<?php echo $v( 'flight' ); ?>" placeholder="e.g. DL 1234 - we track your flight"></div>

			<div class="mqf-row">
				<div id="mqf-hourswrap" class="mqf-cond"><label for="mqf-hours">Number of Hours</label>
					<select id="mqf-hours" name="hours"><option value="">Not sure / one-way</option>
					<?php for ( $h = 2; $h <= 12; $h++ ) : ?><option <?php selected( $state['values']['hours'] ?? '', $h . ' hours' ); ?>><?php echo esc_html( $h . ' hours' ); ?></option><?php endfor; ?>
					</select></div>
				<div><label for="mqf-pax">Number of Passengers *</label><input type="number" id="mqf-pax" name="passengers" value="<?php echo $v( 'passengers' ); ?>" min="1" max="99" step="1" inputmode="numeric" placeholder="e.g. 4" required><?php echo $err( 'passengers' ); ?></div>
			</div>

			<div class="mqf-row">
				<div><label for="mqf-first">First Name *</label><input type="text" id="mqf-first" name="first_name" value="<?php echo $v( 'first_name' ); ?>" autocomplete="given-name" required><?php echo $err( 'first_name' ); ?></div>
				<div><label for="mqf-last">Last Name *</label><input type="text" id="mqf-last" name="last_name" value="<?php echo $v( 'last_name' ); ?>" autocomplete="family-name" required><?php echo $err( 'last_name' ); ?></div>
			</div>

			<label for="mqf-email">Email Address *</label>
			<input type="email" id="mqf-email" name="email" value="<?php echo $v( 'email' ); ?>" autocomplete="email" placeholder="name@example.com" required><?php echo $err( 'email' ); ?>

			<label for="mqf-phone">Mobile Number *</label>
			<input type="tel" id="mqf-phone" name="phone" value="<?php echo $v( 'phone' ); ?>" autocomplete="tel" placeholder="843-804-1188" inputmode="tel" required><?php echo $err( 'phone' ); ?>

			<label class="mqf-check"><input type="checkbox" name="sms_ok" value="1" <?php checked( ! empty( $state['values']['sms_ok'] ) ); ?>> It's OK to text me about my quote. Standard messaging and data rates may apply.</label>
			<label class="mqf-check"><input type="checkbox" name="round_trip" value="1" <?php checked( ! empty( $state['values']['round_trip'] ) ); ?>> Round trip</label>
			<div id="mqf-returnwrap" class="mqf-cond<?php echo ! empty( $state['values']['round_trip'] ) ? ' on' : ''; ?>"><div class="mqf-row">
				<div><label for="mqf-rdate">Return Date *</label><input type="date" id="mqf-rdate" name="return_date" value="<?php echo $v( 'return_date' ); ?>" min="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>"><?php echo $err( 'return_date' ); ?></div>
				<div><label for="mqf-rtime">Return Pick-up Time *</label><input type="time" id="mqf-rtime" name="return_time" value="<?php echo $v( 'return_time' ); ?>"><?php echo $err( 'return_time' ); ?></div>
			</div></div>

			<label for="mqf-notes">Notes / Comments</label>
			<textarea id="mqf-notes" name="notes" rows="4" placeholder="Luggage, car seats, special requests..."><?php echo esc_textarea( $state['values']['notes'] ?? '' ); ?></textarea>

			<button type="submit" name="mecca_qf_submit" value="1">Get Prices &amp; Availability</button>
			<p class="mqf-note">* Required. We never share your information.</p>
		</form>
		<script>
		(function(){
			var f=document.querySelector('.mqf form'); if(!f) return;
			function digits(s){return (s||'').replace(/\D/g,'');}
			function phoneOk(s){var d=digits(s); if(d.length===11&&d[0]==='1') d=d.slice(1); return /^[2-9]\d{2}[2-9]\d{6}$/.test(d) && !/^(\d)\1{9}$/.test(d);}
			function emailOk(s){return /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/.test(s||'');}
			function mark(el,msg){var n=el.nextElementSibling; if(n&&n.classList.contains('mqf-err')&&n.classList.contains('js')) n.remove(); el.classList.toggle('mqf-bad',!!msg); if(msg){n=document.createElement('span'); n.className='mqf-err js'; n.textContent=msg; el.insertAdjacentElement('afterend',n);}}
			var ph=f.querySelector('[name=phone]'), em=f.querySelector('[name=email]');
			var hourly=<?php echo wp_json_encode( mecca_qf_hourly() ); ?>;
			var svc=f.querySelector('[name=service]'), rt=f.querySelector('[name=round_trip]');
			function show(id,on){var el=document.getElementById(id); if(el) el.classList.toggle('on',!!on);}
			function sync(){show('mqf-hourswrap',hourly.indexOf(svc.value)>-1); show('mqf-flightwrap',svc.value==='Airport Transfer'); show('mqf-returnwrap',rt.checked);
				['return_date','return_time'].forEach(function(n){var el=f.querySelector('[name='+n+']'); if(rt.checked) el.setAttribute('required',''); else el.removeAttribute('required');});}
			svc.addEventListener('change',sync); rt.addEventListener('change',sync); sync();
			f.querySelectorAll('.mqf-addstop').forEach(function(b){b.addEventListener('click',function(){show(b.dataset.target,true); b.hidden=true; var i=document.querySelector('#'+b.dataset.target+' input'); if(i) i.focus();});});
			var d1=f.querySelector('[name=date]'), d2=f.querySelector('[name=return_date]'); d1.addEventListener('change',function(){ if(d1.value) d2.min=d1.value; });
			ph.addEventListener('input',function(){var d=digits(ph.value); if(d.length===11&&d[0]==='1') d=d.slice(1); d=d.slice(0,10); if(d.length>6) ph.value='('+d.slice(0,3)+') '+d.slice(3,6)+'-'+d.slice(6); else if(d.length>3) ph.value='('+d.slice(0,3)+') '+d.slice(3);});
			f.addEventListener('submit',function(e){
				var bad=false;
				f.querySelectorAll('[required]').forEach(function(el){ if(el.closest('.mqf-cond') && !el.closest('.mqf-cond').classList.contains('on')) return; if(!el.value.trim()){mark(el,'This field is required.'); bad=true;} else mark(el,''); });
				if(em.value.trim() && !emailOk(em.value.trim())){mark(em,'Please enter a valid email address (for example name@gmail.com).'); bad=true;}
				if(ph.value.trim() && !phoneOk(ph.value)){mark(ph,'Please enter a valid 10-digit US phone number (for example 843-804-1188).'); bad=true;}
				if(bad){e.preventDefault(); var first=f.querySelector('.mqf-bad'); if(first) first.focus();}
			});
		})();
		</script>
	<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'mecca_quote_form', 'mecca_qf_shortcode' );



// Gold "Get a Quote" button in the main menu.
add_action( 'wp_head', function () {
	echo '<style>.mecca-quote-menu>a{color:#DCAD4F!important;border:1px solid #DCAD4F;border-radius:20px;padding:6px 14px!important;line-height:1!important}.mecca-quote-menu>a:hover{background:#DCAD4F;color:#111!important;opacity:1!important}</style>';
} );
