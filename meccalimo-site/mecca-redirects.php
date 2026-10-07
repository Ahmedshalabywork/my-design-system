<?php
/**
 * Mecca Limo: permanent redirects for retired pages.
 * Cruise port transfers were discontinued in October 2026; send old links to private tours.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', function () {
	$path = untrailingslashit( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) );
	$map  = array( '/cruise-trips' => '/attractions/' );
	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
}, 1 );
