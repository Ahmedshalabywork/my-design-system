<?php
/**
 * Mecca Limo: AI-search discovery files.
 *
 * - /.well-known/ai.txt   AI crawler permissions
 * - /ai/summary.json      who we are, in one object
 * - /ai/service.json      services, fleet and service area
 * - /ai/faq.json          every service-page FAQ (same answers shown on the site)
 * - /llms-full.txt        llms.txt plus every FAQ, for assistants that read one file
 * - robots.txt            explicit Allow blocks for the AI citation bots
 *
 * No prices are published anywhere here: quotes are given before booking.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mecca_ai_all_faqs() {
	if ( ! function_exists( 'mecca_seo_services' ) || ! function_exists( 'mecca_seo_faqs' ) ) {
		return array();
	}
	$out = array();
	foreach ( mecca_seo_services() as $slug => $s ) {
		foreach ( mecca_seo_faqs( $slug ) as $qa ) {
			$out[] = array(
				'question' => $qa[0],
				'answer'   => $qa[1],
				'url'      => home_url( "/$slug/" ),
			);
		}
	}
	return $out;
}

function mecca_ai_summary() {
	return array(
		'name'         => 'Mecca Limo',
		'legalName'    => 'Mecca Limo Chauffeur Service',
		'url'          => home_url( '/' ),
		'description'  => 'Family-owned limo and black car service in Charleston, SC, open 24/7: CHS airport transfers, weddings, corporate travel, nights out and party bus rentals, tours, beach, golf, hotel and cruise port rides.',
		'type'         => 'LocalBusiness / Limousine service',
		'phone'        => '+1-843-804-1188',
		'email'        => 'info@meccalimo.com',
		'hours'        => '24 hours a day, 7 days a week',
		'location'     => 'Charleston, SC, United States',
		'serviceArea'  => function_exists( 'mecca_seo_areas' ) ? mecca_seo_areas() : array(),
		'rating'       => (array) get_option( 'mecca_review_stats', array( 'rating' => 5.0, 'count' => 153 ) ),
		'pricing'      => 'Quoted before booking. Flat price for airport and point-to-point rides; hourly bookings have a 3-hour minimum.',
		'quote'        => home_url( '/get-a-quote/' ),
		'llms'         => home_url( '/llms.txt' ),
		'lastModified' => gmdate( 'c', strtotime( get_lastpostmodified( 'gmt' ) ?: 'now' ) ),
	);
}

function mecca_ai_service() {
	$services = array();
	if ( function_exists( 'mecca_seo_services' ) ) {
		foreach ( mecca_seo_services() as $slug => $s ) {
			$services[] = array( 'name' => $s[0], 'type' => $s[1], 'url' => home_url( "/$slug/" ) );
		}
	}
	return array(
		'provider' => 'Mecca Limo',
		'services' => $services,
		'fleet'    => array(
			array( 'vehicle' => 'Mercedes-Benz Sprinter', 'passengers' => 14, 'notes' => 'Wedding parties, corporate groups, nights out; room for luggage' ),
			array( 'vehicle' => 'Luxury SUV (Cadillac Escalade, Chevrolet Suburban, GMC Yukon Denali)', 'passengers' => 5, 'notes' => 'Room for luggage' ),
			array( 'vehicle' => 'Executive sedan', 'passengers' => 2, 'notes' => 'Room for luggage; airport runs and business travel' ),
		),
		'airports' => array( 'Charleston International Airport (CHS)', 'Charleston Executive Airport (JZI)', 'Mount Pleasant Regional Airport (LRO)' ),
		'booking'  => array( 'quote' => home_url( '/get-a-quote/' ), 'phone' => '+1-843-804-1188', 'policy' => home_url( '/policy/' ) ),
	);
}

add_action( 'init', function () {
	$path = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
	$json = array(
		'/ai/summary.json' => 'mecca_ai_summary',
		'/ai/service.json' => 'mecca_ai_service',
		'/ai/faq.json'     => function () {
			return array( 'source' => home_url( '/' ), 'faqs' => mecca_ai_all_faqs() );
		},
	);
	if ( isset( $json[ $path ] ) ) {
		status_header( 200 );
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'X-Robots-Tag: noindex' );
		echo wp_json_encode( call_user_func( $json[ $path ] ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); // phpcs:ignore
		exit;
	}
	if ( '/.well-known/ai.txt' === $path ) {
		$u = home_url( '/' );
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo "# AI access policy for Mecca Limo ({$u})\n"
			. "User-Agent: *\nAllow: /\nDisallow: /wp-admin/\n\n"
			. "# Citation and answers are welcome. Please link to the page you quote.\n"
			. "Contact: info@meccalimo.com\n"
			. "Summary: {$u}ai/summary.json\nServices: {$u}ai/service.json\nFAQ: {$u}ai/faq.json\nLLMs: {$u}llms.txt\nLLMs-Full: {$u}llms-full.txt\n"; // phpcs:ignore
		exit;
	}
	if ( '/llms-full.txt' === $path ) {
		$base = wp_remote_retrieve_body( wp_remote_get( home_url( '/llms.txt' ), array( 'timeout' => 5 ) ) );
		$t    = rtrim( $base ) . "\n\n## Frequently asked questions\n";
		foreach ( mecca_ai_all_faqs() as $f ) {
			$t .= "\n### {$f['question']}\n{$f['answer']}\nSource: {$f['url']}\n";
		}
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'X-Robots-Tag: noindex' );
		echo $t; // phpcs:ignore
		exit;
	}
}, 0 );

// Name the AI citation bots explicitly so no crawler has to rely on the wildcard.
add_filter( 'robots_txt', function ( $out ) {
	if ( false !== strpos( $out, 'OAI-SearchBot' ) ) {
		return $out;
	}
	$bots = array( 'OAI-SearchBot', 'ChatGPT-User', 'GPTBot', 'ClaudeBot', 'Claude-SearchBot', 'Claude-User', 'PerplexityBot', 'Perplexity-User', 'Google-Extended', 'Applebot-Extended', 'Bingbot' );
	$add  = "\n# AI search and answer engines: welcome\n";
	foreach ( $bots as $b ) {
		$add .= "User-agent: $b\n";
	}
	$add .= "Allow: /\nDisallow: /wp-admin/\n";
	return preg_replace( '#\n(Sitemap:)#', $add . "\n$1", $out, 1 );
}, 100 );
