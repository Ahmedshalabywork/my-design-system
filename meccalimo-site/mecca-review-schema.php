<?php
/**
 * Mecca Limo: review-snippet structured data for the homepage, so Google can show
 * star ratings next to the search result. It describes the Google rating and the
 * reviews already visible in the homepage "What our clients say" section.
 *
 * Update the rating and count with:
 * update_option( 'mecca_review_stats', array( 'rating' => 5.0, 'count' => 153 ) );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', function () {
	if ( ! is_front_page() ) {
		return;
	}
	$stats  = wp_parse_args( (array) get_option( 'mecca_review_stats', array() ), array( 'rating' => 5.0, 'count' => 153 ) );
	$quotes = array(
		array( 'Tiffany Clark', "Mecca provided exceptional service at a price that blew the competition out of the water for our group of 10's trip into Charleston for dinner and dancing! Thank you, Mecca team!" ),
		array( 'Susan Gorsline', 'When I unexpectedly needed a ride to Roper St. Francis after surgery, Mecca came to my aid! Richard was outstanding. He made sure I got there safely, waited with me, and even called later to see how things went.' ),
		array( 'Ann Cannady', "We used Mecca for our daughter's wedding weekend. They were amazing, always arrived early, very nice rides, and the drivers were so friendly and helpful. Thank you Moe and Mecca for making our weekend even more special." ),
		array( 'Robert Ricker', "Our trip from Charleston to Kiawah Island for our son's wedding was made even more memorable thanks to Richard. Not only did he get us there comfortably, he turned the ride into a guided tour of the area." ),
		array( 'Amanda Bagdonas', 'Moe and the Mecca team provided exceptional service during our two-week event. They reviewed every flight itinerary so drivers arrived at the airport well ahead of schedule. Our guests felt cared for from arrival.' ),
		array( 'Michele Mavi', "I was planning a large event for a group of CEOs in Kiawah. The hotel's pricing was incredibly high. Mecca was outstanding and far more reasonable. I'd recommend them without hesitation." ),
		array( 'Sarah Parker', 'Our group of 14 was easily and happily transported to various wedding events in and around Charleston. Not only was the van beautiful and comfortable, but the driver was so great, she was like part of the group.' ),
		array( 'Jesse Kirchner', "My family and I have exclusively used Moe and his team for over 4 years now. From big parties to a simple date night on the town, they're always on time, friendly, and prompt with communication. Five stars all around!" ),
	);
	$reviews = array();
	foreach ( $quotes as $q ) {
		$reviews[] = array(
			'@type'        => 'Review',
			'author'       => array( '@type' => 'Person', 'name' => $q[0] ),
			'reviewBody'   => $q[1],
			'reviewRating' => array( '@type' => 'Rating', 'ratingValue' => 5, 'bestRating' => 5, 'worstRating' => 1 ),
		);
	}
	$data = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'Product',
		'@id'             => home_url( '/#limo-service' ),
		'name'            => 'Mecca Limo Charleston Limo & Car Service',
		'description'     => 'Private limo, black car and Sprinter service in Charleston, SC: CHS airport transfers, weddings, corporate travel and nights out, 24/7.',
		'image'           => home_url( '/wp-content/uploads/2026/10/chevrolet-suburban-limo-charleston-sc.jpg' ),
		'url'             => home_url( '/' ),
		'brand'           => array( '@type' => 'Brand', 'name' => 'Mecca Limo' ),
		'aggregateRating' => array(
			'@type'       => 'AggregateRating',
			'ratingValue' => (float) $stats['rating'],
			'reviewCount' => (int) $stats['count'],
			'bestRating'  => 5,
			'worstRating' => 1,
		),
		'review'          => $reviews,
	);
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}, 30 );
