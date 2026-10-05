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
	if ( is_front_page() || is_page( 'get-a-quote' ) ) {
		return get_option( 'mecca_home_v2_live' ) || isset( $_GET['mecca_v2'] );
	}
	if ( is_page( mecca_home_page_slugs() ) && ! post_password_required() ) {
		return get_option( 'mecca_pages_v2_live' ) || isset( $_GET['mecca_v2'] );
	}
	return false;
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

// Pages that use the new design instead of their Divi layout.
function mecca_home_page_slugs() {
	return array( 'about', 'airport', 'attractions', 'beach', 'contact', 'corporate', 'cruise-trips', 'events', 'golf-courses', 'hotels', 'night-out', 'policy', 'service', 'wedding' );
}

function mecca_home_attr( $attrs, $name ) {
	return preg_match( '/(?<![\w-])' . preg_quote( $name, '/' ) . '="([^"]*)"/', $attrs, $m ) ? html_entity_decode( $m[1], ENT_QUOTES ) : '';
}

function mecca_home_clean( $html ) {
	$html = preg_replace( '/\s(style|class|id)="[^"]*"/i', '', $html );
	$html = str_replace( array( '&nbsp;', '<p></p>' ), ' ', $html );
	return trim( wpautop( $html ) );
}

// Turn a page's Divi modules into plain blocks: hero image, H1, and body HTML.
function mecca_home_parse_page( $content ) {
	$out   = array( 'hero' => '', 'h1' => '', 'html' => '' );
	$cards = array();
	$html  = '';
	$flush = function () use ( &$cards, &$html ) {
		if ( ! $cards ) {
			return;
		}
		$html .= '<div class="pg-cards">';
		foreach ( $cards as $c ) {
			$html .= '<div class="pg-card">';
			if ( $c['img'] ) {
				$html .= '<div class="pg-card-img"><img loading="lazy" decoding="async" src="' . esc_url( $c['img'] ) . '" alt="' . esc_attr( $c['alt'] ?: $c['title'] ) . '"></div>';
			}
			$html .= '<div class="pg-card-body"><h3>' . esc_html( $c['title'] ) . '</h3>' . $c['body'];
			if ( $c['url'] ) {
				$html .= '<a class="pg-card-link" href="' . esc_url( $c['url'] ) . '" target="_blank" rel="noopener">' . esc_html( $c['btn'] ?: 'Learn more' ) . ' →</a>';
			}
			$html .= '</div></div>';
		}
		$html .= '</div>';
		$cards = array();
	};
	preg_match_all( '/\[(et_pb_[a-z_]+)((?:[^\]"]|"[^"]*")*)\]/', $content, $m, PREG_OFFSET_CAPTURE );
	foreach ( $m[1] as $i => $t ) {
		$tag = $t[0];
		$a   = $m[2][ $i ][0];
		$st  = $m[0][ $i ][1] + strlen( $m[0][ $i ][0] );
		$end = strpos( $content, '[/' . $tag . ']', $st );
		$in  = false !== $end ? substr( $content, $st, $end - $st ) : '';
		switch ( $tag ) {
			case 'et_pb_image':
				$src = mecca_home_attr( $a, 'src' );
				if ( ! $out['hero'] ) {
					$out['hero'] = $src;
				}
				break;
			case 'et_pb_text':
				$flush();
				$t2 = mecca_home_clean( $in );
				if ( ! $out['h1'] && preg_match( '#<h1[^>]*>(.*?)</h1>#s', $t2, $h ) ) {
					$out['h1'] = trim( wp_strip_all_tags( $h[1] ) );
					$t2        = str_replace( $h[0], '', $t2 );
				}
				$t2 = preg_replace( '#<(/?)h1>#', '<$1h2>', $t2 );
				if ( trim( wp_strip_all_tags( $t2 ) ) ) {
					$html .= '<div class="pg-text">' . $t2 . '</div>';
				}
				break;
			case 'et_pb_blurb':
				if ( 'on' === mecca_home_attr( $a, 'use_icon' ) && ! trim( $in ) ) {
					$ti = mecca_home_attr( $a, 'title' );
					$ur = mecca_home_attr( $a, 'url' );
					if ( $ti && false === strpos( $ti, ';' ) ) {
						$flush();
						$html .= '<a class="pg-contact" href="' . esc_url( $ur ? $ur : '#quote' ) . '">' . esc_html( $ti ) . '</a>';
					}
					break;
				}
				$img = mecca_home_attr( $a, 'image' );
				$cards[] = array(
					'title' => mecca_home_attr( $a, 'title' ),
					'img'   => preg_match( '#^https?://#', $img ) ? $img : '',
					'alt'   => mecca_home_attr( $a, 'alt' ),
					'url'   => mecca_home_attr( $a, 'url' ),
					'btn'   => '',
					'body'  => mecca_home_clean( $in ),
				);
				break;
			case 'et_pb_button':
				$u  = mecca_home_attr( $a, 'button_url' );
				$bt = mecca_home_attr( $a, 'button_text' );
				if ( $cards ) {
					$cards[ count( $cards ) - 1 ]['url'] = $u;
					$cards[ count( $cards ) - 1 ]['btn'] = $bt;
				}
				break;
			case 'et_pb_contact_form':
				$flush();
				$html .= '<p class="pg-text"><a href="#quote">Send us your trip details with the form below →</a></p>';
				break;
			case 'et_pb_gallery':
				$flush();
				$ids = array_filter( array_map( 'intval', explode( ',', mecca_home_attr( $a, 'gallery_ids' ) ) ) );
				if ( $ids ) {
					$html .= '<div class="pg-gallery">';
					foreach ( $ids as $gid ) {
						$html .= wp_get_attachment_image( $gid, 'large', false, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
					}
					$html .= '</div>';
				}
				break;
		}
	}
	$flush();
	$out['html'] = $html;
	return $out;
}
