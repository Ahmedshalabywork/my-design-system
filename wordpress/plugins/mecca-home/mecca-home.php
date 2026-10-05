<?php
/**
 * Plugin Name: Mecca Limo Homepage
 * Description: Dark-and-gold homepage for Mecca Limo. Preview with ?mecca_v2=1; goes live when the "mecca_home_v2_live" option is on.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/seo.php';

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
	// Divi adds a second viewport tag that blocks pinch-zoom; ours already sets the viewport.
	$html = preg_replace( '#<meta name="viewport"[^>]*user-scalable[^>]*>\s*#i', '', $html );
	// Hosting monitoring scripts are not needed on these pages and slow them down.
	$html = preg_replace( "#<script[^>]*src=['\"][^'\"]*wsimg\.com/traffic-assets[^'\"]*['\"][^>]*></script>#", '', $html );
	$html = preg_replace( '#<script[^>]*>[^<]*_trfq[^<]*</script>#', '', $html );
	// Hide email addresses from spam bots.
	$parts = preg_split( '#(<script\b.*?</script>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	foreach ( $parts as $k => $part ) {
		if ( 0 !== stripos( $part, '<script' ) ) {
			$parts[ $k ] = preg_replace_callback( '#(?<![\w.@/-])([\w.+-]+@meccalimo\.com)#i', function ( $m ) {
				return antispambot( $m[1] );
			}, $part );
		}
	}
	$html = implode( '', $parts );
	$html = preg_replace( '#<style[^>]*id="et-divi-customizer-global-cached-inline-styles"[^>]*>.*?</style>#s', '', $html );
	$html = preg_replace( '#<script[^>]*>(?:(?!</script>).)*et-divi-dynamic(?:(?!</script>).)*</script>#s', '', $html );
	return $html;
}

// Pages that use the new design instead of their Divi layout.
function mecca_home_page_slugs() {
	return array( 'about', 'airport', 'attractions', 'beach', 'contact', 'corporate', 'cruise-trips', 'events', 'golf-courses', 'hotels', 'night-out', 'policy', 'service', 'wedding', 'charleston-limo-fleet', 'kiawah-island-car-service', 'mount-pleasant-limo-service', 'reviews', 'charleston-hourly-limo-charter' );
}

function mecca_home_attr( $attrs, $name ) {
	return preg_match( '/(?<![\w-])' . preg_quote( $name, '/' ) . '="([^"]*)"/', $attrs, $m ) ? html_entity_decode( $m[1], ENT_QUOTES ) : '';
}

function mecca_home_clean( $html ) {
	$html = preg_replace( '/\s(style|class|id)="[^"]*"/i', '', $html );
	// Keep heading levels in order: pages that jump from H1 to H3 get their H3s promoted.
	if ( false === stripos( $html, '<h2' ) && false !== stripos( $html, '<h3' ) ) {
		$html = preg_replace( array( '#<(/?)h3>#i', '#<(/?)h4>#i' ), array( '<$1h2>', '<$1h3>' ), $html );
	}
	// Third-party shortcodes from removed Divi add-ons would otherwise show as raw text.
	$html = preg_replace( '/\[\/?dsm_[^\]]*\]/', '', $html );
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
				$html .= '<div class="pg-card-img">' . mecca_home_img( $c['img'], $c['alt'] ? $c['alt'] : $c['title'], array( 'loading' => 'lazy', 'sizes' => '(max-width: 900px) 100vw, 300px' ) ) . '</div>';
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
	if ( false === strpos( $content, '[et_pb_' ) ) {
		$c = do_shortcode( mecca_home_clean( $content ) );
		if ( preg_match( '#<h1[^>]*>(.*?)</h1>#s', $c, $h ) ) {
			$out['h1'] = trim( wp_strip_all_tags( $h[1] ) );
			$c         = str_replace( $h[0], '', $c );
		}
		if ( preg_match( '#<img[^>]+src="([^"]+)"[^>]*>#', $c, $im ) ) {
			$out['hero'] = $im[1];
			$c           = str_replace( $im[0], '', $c );
		}
		$c = preg_replace_callback( '#<img[^>]+src="([^"]+)"[^>]*?(?:alt="([^"]*)")?[^>]*>#', function ( $im ) {
			return mecca_home_img( $im[1], isset( $im[2] ) ? html_entity_decode( $im[2] ) : '', array( 'loading' => 'lazy' ) );
		}, $c );
		$out['html'] = '<div class="pg-text">' . $c . '</div>';
		return $out;
	}
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
	// Keep heading levels in order after the page H1 (no jumps like H1 -> H3).
	$prev = 1;
	$html = preg_replace_callback( '#<h([1-6])>(.*?)</h\1>#s', function ( $m ) use ( &$prev ) {
		$lv   = min( (int) $m[1], $prev + 1 );
		$prev = $lv;
		return '<h' . $lv . '>' . $m[2] . '</h' . $lv . '>';
	}, $html );
	$out['html'] = $html;
	return $out;
}

// Responsive <img> with real width/height for a media-library URL, so photos load small and never shift the page.
function mecca_home_img( $url, $alt, $extra = array() ) {
	$path = preg_replace( '#^https?://(www\.)?meccalimo\.com#', '', $url );
	$id   = attachment_url_to_postid( home_url( $path ) );
	if ( ! $id ) {
		$id = attachment_url_to_postid( 'https://meccalimo.com' . $path );
	}
	$attr = array_merge( array( 'alt' => $alt, 'decoding' => 'async' ), $extra );
	$file = ABSPATH . ltrim( wp_parse_url( $path, PHP_URL_PATH ), '/' );
	$webp = mecca_home_webp( $file );
	if ( $webp ) {
		unset( $attr['sizes'] );
		$html = '<img src="' . esc_url( $webp['url'] ) . '" width="' . (int) $webp['w'] . '" height="' . (int) $webp['h'] . '"';
		foreach ( $attr as $k => $v ) {
			$html .= ' ' . $k . '="' . esc_attr( $v ) . '"';
		}
		return $html . '>';
	}
	if ( $id ) {
		return wp_get_attachment_image( $id, 'large', false, $attr );
	}
	$dim  = file_exists( $file ) ? @getimagesize( $file ) : false;
	$html = '<img src="' . esc_url( $url ) . '"';
	if ( $dim ) {
		$html .= ' width="' . (int) $dim[0] . '" height="' . (int) $dim[1] . '"';
	}
	foreach ( $attr as $k => $v ) {
		$html .= ' ' . $k . '="' . esc_attr( $v ) . '"';
	}
	return $html . '>';
}

// Light WebP copy (max 1200px wide) of a local JPG/PNG, made once and reused.
function mecca_home_webp( $file ) {
	if ( ! preg_match( '/\.(jpe?g|png)$/i', $file ) || ! file_exists( $file ) ) {
		return false;
	}
	$up   = wp_upload_dir();
	$dir  = $up['basedir'] . '/mecca-webp';
	$name = md5( $file . filemtime( $file ) ) . '.webp';
	$out  = $dir . '/' . $name;
	if ( ! file_exists( $out ) ) {
		$ed = wp_get_image_editor( $file );
		if ( is_wp_error( $ed ) ) {
			return false;
		}
		$sz = $ed->get_size();
		if ( $sz['width'] > 1200 ) {
			$ed->resize( 1200, null );
		}
		$ed->set_quality( 72 );
		wp_mkdir_p( $dir );
		$saved = $ed->save( $out, 'image/webp' );
		if ( is_wp_error( $saved ) || ! file_exists( $out ) ) {
			return false;
		}
	}
	$dim = @getimagesize( $out );
	if ( ! $dim ) {
		return false;
	}
	return array( 'url' => $up['baseurl'] . '/mecca-webp/' . $name, 'w' => $dim[0], 'h' => $dim[1] );
}
