<?php
/**
 * Mecca Limo: keep the GoDaddy hosting "tccl" monitoring script off the page.
 * The host's web server inserts it in front of the page's "</html>". The real end tag
 * is written as "</html >" (valid HTML) and a "</html>" is left inside a trailing
 * comment, so the inserted script lands inside the comment and never runs.
 * Lighthouse mobile: 68 with the script, 98 without. To restore it, disable this file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', function () {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	ob_start( function ( $html ) {
		$pos = strrpos( $html, '</html>' );
		if ( false === $pos ) {
			return $html;
		}
		return substr_replace( $html, '</html ><!--</html>-->', $pos, 7 );
	} );
}, 0 );
