<?php
/**
 * Plugin Name: Mecca Limo Homepage
 * Description: Dark-and-gold homepage for Mecca Limo. Preview with ?mecca_v2=1; goes live when the "mecca_home_v2_live" option is on.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mecca_home_active() {
	if ( ! is_front_page() && ! is_page( 'get-a-quote' ) ) {
		return false;
	}
	return get_option( 'mecca_home_v2_live' ) || isset( $_GET['mecca_v2'] );
}

function mecca_home_asset( $file ) {
	return plugins_url( 'assets/' . $file, __FILE__ );
}

add_filter( 'template_include', function ( $template ) {
	if ( mecca_home_active() ) {
		if ( isset( $_GET['mecca_v2'] ) && ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
		return __DIR__ . '/home-template.php';
	}
	return $template;
}, 99 );

// Preview URL stays out of search results.
add_filter( 'rank_math/frontend/robots', function ( $robots ) {
	if ( isset( $_GET['mecca_v2'] ) && ! get_option( 'mecca_home_v2_live' ) ) {
		$robots['index'] = 'noindex';
	}
	return $robots;
} );

// The new homepage carries its own header, footer and styles, so drop Divi's front-end assets there.
function mecca_home_dequeue() {
	if ( ! mecca_home_active() ) {
		return;
	}
	$drop = '/^(divi|et[-_]|et$|css-divi|js-divi|dap-|diviarea|dsm|supreme|popups|dcfh|divi-contact|magnific|wp-block-library|classic-theme|global-styles|font-awesome|fontawesome|jquery|moment|fitvids|wp-mediaelement|mediaelement)/i';
	foreach ( wp_styles()->queue as $h ) {
		if ( preg_match( $drop, $h ) ) {
			wp_dequeue_style( $h );
		}
	}
	foreach ( wp_scripts()->queue as $h ) {
		if ( preg_match( $drop, $h ) ) {
			wp_dequeue_script( $h );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'mecca_home_dequeue', 9999 );
add_action( 'wp_print_styles', 'mecca_home_dequeue', 1 );
add_action( 'wp_print_scripts', 'mecca_home_dequeue', 1 );
add_action( 'wp_print_footer_scripts', 'mecca_home_dequeue', 1 );

add_action( 'template_redirect', function () {
	if ( mecca_home_active() ) {
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		ob_start( 'mecca_home_strip' );
	}
} );

// Remove Divi's leftover inline CSS and its late-CSS loader, which only apply to Divi layouts.
function mecca_home_strip( $html ) {
	$html = preg_replace( '#<style[^>]*id="et-divi-customizer-global-cached-inline-styles"[^>]*>.*?</style>#s', '', $html );
	$html = preg_replace( '#<script[^>]*>(?:(?!</script>).)*et-divi-dynamic(?:(?!</script>).)*</script>#s', '', $html );
	return $html;
}
