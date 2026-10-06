<?php
/**
 * Mecca Limo: show blog posts, blog listings, search results and the 404 page in
 * the same dark-and-gold design as the main pages (mecca-home plugin), instead of
 * the old Divi theme. Reuses mecca-home's template, so design changes apply here too.
 *
 * Live when the "mecca_extra_v2_live" option is on; preview any time with ?mecca_v2x=1.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mecca_x_active() {
	if ( ! function_exists( 'mecca_home_parse_page' ) || is_admin() || is_feed() || is_embed() ) {
		return false;
	}
	if ( ! ( is_singular( 'post' ) || is_404() || is_search() || is_home() || is_archive() ) ) {
		return false;
	}
	if ( is_singular() && post_password_required() ) {
		return false;
	}
	return get_option( 'mecca_extra_v2_live' ) || isset( $_GET['mecca_v2x'] );
}

// Divi accordions (FAQs) become plain headed text blocks the design can show.
function mecca_x_accordions( $content ) {
	return preg_replace_callback(
		'/\[et_pb_accordion_item((?:[^\]"]|"[^"]*")*)\](.*?)\[\/et_pb_accordion_item\]/s',
		function ( $m ) {
			$title = mecca_home_attr( $m[1], 'title' );
			return '[et_pb_text]<h3>' . esc_html( $title ) . '</h3>' . $m[2] . '[/et_pb_text]';
		},
		$content
	);
}

function mecca_x_post_list( $posts, $tag = 'h2' ) {
	$html = '';
	foreach ( $posts as $p ) {
		$html .= '<' . $tag . '><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a></' . $tag . '>';
		$html .= '<p>' . esc_html( wp_trim_words( wp_strip_all_tags( strip_shortcodes( mecca_x_text( $p->post_content ) ) ), 32 ) ) . '</p>';
	}
	return $html;
}

// Readable text of a Divi post, for excerpts.
function mecca_x_text( $content ) {
	return preg_replace( '/\[\/?et_pb_[^\]]*\]/', ' ', $content );
}

// The template reads the current post; give non-post views a stand-in post with their own content.
function mecca_x_virtual_post( $slug, $title, $content ) {
	$GLOBALS['post'] = new WP_Post( (object) array(
		'ID'           => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => $slug,
		'post_title'   => $title,
		'post_content' => $content,
		'filter'       => 'raw',
	) );
}

function mecca_x_prepare() {
	global $wp_query;
	$links = '<p><a href="' . esc_url( home_url( '/' ) ) . '">Home</a> · <a href="' . esc_url( home_url( '/airport/' ) ) . '">Airport transfers</a> · <a href="' . esc_url( home_url( '/wedding/' ) ) . '">Weddings</a> · <a href="' . esc_url( home_url( '/charleston-limo-fleet/' ) ) . '">Our fleet</a> · <a href="' . esc_url( home_url( '/contact/' ) ) . '">Contact</a></p>';

	if ( is_singular( 'post' ) ) {
		$p       = get_post();
		$content = mecca_x_accordions( $p->post_content );
		$date    = '[et_pb_text]<p>Published ' . esc_html( get_the_date( 'F j, Y', $p ) ) . '</p>[/et_pb_text]';
		// Use the featured image as the hero when the post has no image module.
		$hero = '';
		if ( false === strpos( $content, '[et_pb_image' ) && has_post_thumbnail( $p ) ) {
			$hero = '[et_pb_image src="' . esc_url( get_the_post_thumbnail_url( $p, 'full' ) ) . '"][/et_pb_image]';
		}
		$p->post_content = $hero . $date . $content;
		return;
	}

	if ( is_404() ) {
		$recent = get_posts( array( 'numberposts' => 6 ) );
		mecca_x_virtual_post(
			'page-not-found',
			'Page not found',
			'<h1>Sorry, we couldn\'t find that page</h1><p>The page may have moved. Here are some places to start, or call us 24/7 at (843) 804-1188.</p>' . $links . '<h2>Latest from our blog</h2>' . mecca_x_post_list( $recent, 'h3' )
		);
		return;
	}

	$title = is_search() ? 'Search results for “' . get_search_query() . '”' : ( is_home() ? 'Mecca Limo Blog' : wp_strip_all_tags( preg_replace( '/^[^:]+:\s*/', '', get_the_archive_title() ) ) );
	$list  = $wp_query->posts ? mecca_x_post_list( $wp_query->posts ) : '<p>No articles matched. Try one of these pages:</p>' . $links;
	$pages = '';
	$next  = get_next_posts_link( 'Older articles →' );
	$prev  = get_previous_posts_link( '← Newer articles' );
	if ( $next || $prev ) {
		$pages = '<p>' . $prev . ( $next && $prev ? ' · ' : '' ) . $next . '</p>';
	}
	mecca_x_virtual_post( is_search() ? 'search' : 'blog', $title, '<h1>' . esc_html( $title ) . '</h1>' . $list . $pages );
}

add_filter( 'template_include', function ( $template ) {
	if ( mecca_x_active() ) {
		if ( isset( $_GET['mecca_v2x'] ) && ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
		mecca_x_prepare();
		return WP_PLUGIN_DIR . '/mecca-home/home-template.php';
	}
	return $template;
}, 100 );

// Same clean-up the main pages get: no Divi CSS/JS, tidied HTML.
function mecca_x_dequeue() {
	if ( ! mecca_x_active() ) {
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
add_action( 'wp_enqueue_scripts', 'mecca_x_dequeue', 9999 );
add_action( 'wp_print_styles', 'mecca_x_dequeue', 1 );
add_action( 'wp_print_scripts', 'mecca_x_dequeue', 1 );
add_action( 'wp_print_footer_scripts', 'mecca_x_dequeue', 1 );

add_action( 'template_redirect', function () {
	if ( mecca_x_active() && function_exists( 'mecca_home_strip' ) ) {
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		ob_start( 'mecca_home_strip' );
	}
} );

// Keep the preview link out of search results.
add_filter( 'rank_math/frontend/robots', function ( $robots ) {
	if ( isset( $_GET['mecca_v2x'] ) && ! get_option( 'mecca_extra_v2_live' ) ) {
		$robots['index'] = 'noindex';
	}
	return $robots;
} );
