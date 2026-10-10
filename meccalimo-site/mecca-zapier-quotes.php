<?php
/**
 * Mecca Limo: send a plain-text copy of each website quote to Zapier (Email by Zapier),
 * which creates the quote request in Limo Anywhere.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'mecca_qf_sent', function ( $f, $email, $tel ) {
	$to = get_option( 'mecca_qf_zap_to' );
	if ( ! $to ) {
		return;
	}
	$click = '';
	if ( ! empty( $_COOKIE['mecca_click'] ) && preg_match( '/^(gclid|gbraid|wbraid):([\w.-]{10,200})\|/', sanitize_text_field( wp_unslash( $_COOKIE['mecca_click'] ) ), $m ) ) {
		$click = $m[1] . '=' . $m[2];
	}
	$vehicle = (int) $f['passengers'] > 6 ? 'Sprinter' : ( (int) $f['passengers'] > 3 ? 'Suburban' : 'Sedan' );
	$notes   = array_filter( array(
		'Service: ' . $f['service'],
		$f['hours'] ? 'Hours: ' . $f['hours'] : '',
		$f['stop'] ? 'Stop: ' . $f['stop'] : '',
		$f['flight'] ? 'Flight: ' . $f['flight'] : '',
		! empty( $f['round_trip'] ) ? 'Round trip return: ' . $f['return_date'] . ' ' . $f['return_time'] : '',
		'OK to text: ' . ( ! empty( $f['sms_ok'] ) ? 'Yes' : 'No' ),
		$f['notes'] ? 'Message: ' . $f['notes'] : '',
		$click ? 'Google Ads click: ' . $click : 'Source: website',
	) );
	$lines = array(
		'pickup_at' => $f['date'] . ' ' . $f['time'],
		'first_name' => $f['first_name'],
		'last_name' => $f['last_name'],
		'phone' => $tel,
		'email' => $email,
		'passengers' => (int) $f['passengers'],
		'vehicle' => $vehicle,
		'pickup' => $f['pickup'],
		'dropoff' => $f['dropoff'],
		'service' => $f['service'],
		'notes' => implode( ' | ', $notes ),
	);
	$body = '';
	foreach ( $lines as $k => $v ) {
		$body .= $k . ': ' . str_replace( array( "\r", "\n" ), ' ', (string) $v ) . "\n";
	}
	// Each field also goes in its own X-Mecca-* header, which Email by Zapier exposes as a separate raw__ field.
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	foreach ( $lines as $k => $v ) {
		$headers[] = 'X-Mecca-' . str_replace( '_', '-', ucwords( $k, '_' ) ) . ': ' . trim( preg_replace( '/[\r\n]+/', ' ', (string) $v ) );
	}
	wp_mail( $to, 'Quote ' . $f['first_name'] . ' ' . $f['last_name'] . ' ' . $f['date'], $body, $headers );
}, 10, 3 );
