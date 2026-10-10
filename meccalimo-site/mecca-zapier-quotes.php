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
	$vehicle = (int) $f['passengers'] > 5 ? 'Sprinter' : ( (int) $f['passengers'] > 2 ? 'Suburban' : 'Sedan' );
	$notes   = array_filter( array(
		'Pickup: ' . date( 'l, M j, Y \\a\\t g:i A', strtotime( $f['date'] . ' ' . $f['time'] ) ),
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
	// Limo Anywhere needs map coordinates for each address.
	foreach ( array( 'pickup', 'dropoff' ) as $k ) {
		list( $lat, $lng ) = mecca_zap_geocode( $f[ $k ] );
		$lines[ $k . '_lat' ] = $lat;
		$lines[ $k . '_lng' ] = $lng;
	}
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

/**
 * Look up latitude/longitude for an address (OpenStreetMap, cached 30 days).
 * Falls back to downtown Charleston so the quote is never rejected; the typed
 * address is still sent as the address name.
 */
function mecca_zap_geocode( $q ) {
	$fallback = array( '32.7765', '-79.9311' );
	$q        = trim( (string) $q );
	if ( '' === $q ) {
		return $fallback;
	}
	if ( preg_match( '/\\b(CHS|charleston (international )?airport)\\b/i', $q ) ) {
		return array( '32.8986', '-80.0405' );
	}
	$key = 'mecca_geo_' . md5( strtolower( $q ) );
	$hit = get_transient( $key );
	if ( $hit ) {
		return $hit;
	}
	// Try the full text, then drop leading parts ("The Sanctuary, Kiawah Island" -> "Kiawah Island").
	$parts = array_map( 'trim', explode( ',', $q ) );
	$out   = null;
	while ( $parts && ! $out ) {
		$try    = implode( ', ', $parts );
		$search = preg_match( '/\\b(SC|South Carolina|GA|NC)\\b/i', $try ) ? $try : $try . ', South Carolina';
		$res    = wp_remote_get( 'https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=us&q=' . rawurlencode( $search ), array( 'timeout' => 5, 'headers' => array( 'User-Agent' => 'MeccaLimo/1.0 (info@meccalimo.com)' ) ) );
		$data   = json_decode( wp_remote_retrieve_body( $res ), true );
		if ( ! empty( $data[0]['lat'] ) ) {
			$out = array( (string) round( (float) $data[0]['lat'], 6 ), (string) round( (float) $data[0]['lon'], 6 ) );
		}
		array_shift( $parts );
		if ( $parts && ! $out ) {
			sleep( 1 ); // OpenStreetMap allows 1 lookup per second.
		}
	}
	if ( ! $out ) {
		set_transient( $key, $fallback, DAY_IN_SECONDS );
		return $fallback;
	}
	set_transient( $key, $out, 30 * DAY_IN_SECONDS );
	return $out;
}
