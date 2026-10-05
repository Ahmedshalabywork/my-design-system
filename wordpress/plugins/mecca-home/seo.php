<?php
/**
 * Search and AI-search signals for Mecca Limo: schema, page FAQs, llms.txt, robots.txt.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mecca_seo_areas() {
	return array( 'Charleston', 'Mount Pleasant', 'North Charleston', 'Kiawah Island', 'Seabrook Island', 'Isle of Palms', 'Folly Beach', "Sullivan's Island", 'Summerville', 'Georgetown' );
}

// Service name and type for each service page.
function mecca_seo_services() {
	return array(
		'airport'      => array( 'Charleston Airport Car Service', 'Airport transfer' ),
		'wedding'      => array( 'Wedding Limo and Transportation', 'Wedding transportation' ),
		'night-out'    => array( 'Party Bus and Night Out Limo', 'Party bus rental' ),
		'corporate'    => array( 'Corporate Car Service', 'Corporate transportation' ),
		'events'       => array( 'Prom and Event Limo', 'Event transportation' ),
		'attractions'  => array( 'Charleston Sightseeing Car Service', 'Sightseeing tour' ),
		'beach'        => array( 'Beach Transportation', 'Beach transfer' ),
		'golf-courses' => array( 'Golf Course Transportation', 'Golf transportation' ),
		'hotels'       => array( 'Airport to Hotel Shuttle', 'Hotel transfer' ),
		'cruise-trips' => array( 'Cruise Port Transportation', 'Cruise port transfer' ),
		'service'      => array( 'Black Car Service', 'Black car service' ),
		'charleston-hourly-limo-charter' => array( 'Hourly Limo and Sprinter Charter', 'Hourly charter' ),
		'kiawah-island-car-service'      => array( 'Kiawah Island Car Service', 'Car service' ),
		'mount-pleasant-limo-service'    => array( 'Mount Pleasant Limo Service', 'Limo service' ),
	);
}

// Short, direct answers shown on each service page (and marked up as FAQPage).
function mecca_seo_faqs( $slug ) {
	$p = '(843) 804-1188';
	$f = array(
		'airport'      => array(
			array( 'Do you pick up at Charleston International Airport (CHS)?', "Yes. Mecca Limo picks up and drops off at Charleston International Airport (CHS) 24 hours a day, 7 days a week. Your chauffeur meets you on arrival and takes you straight to your hotel, home or venue anywhere in the Charleston area. Call or text $p for a quote." ),
			array( 'What happens if my flight is delayed?', 'Share your flight number when you book and your chauffeur will track your arrival. Airport pickups include 20 minutes of wait time from the moment your flight lands, so a delay does not mean a missed ride.' ),
			array( 'Which vehicle should I book for my group and luggage?', 'An executive sedan fits up to 3 passengers with 2 bags, a luxury SUV fits up to 6 with luggage, and the Mercedes-Benz Sprinter fits up to 14 passengers with room for bags. Tell us your group size and we will match the right vehicle.' ),
		),
		'wedding'      => array(
			array( 'How many wedding guests fit in one vehicle?', 'Our Mercedes-Benz Sprinter carries up to 14 passengers, which suits most wedding parties. Luxury SUVs carry up to 6, and our sedans are ideal for the couple. Many weddings book a mix so everyone arrives together.' ),
			array( 'How far ahead should we book wedding transportation?', 'As early as possible. Spring and fall weekends in Charleston fill quickly. A deposit holds your date, and you can send your timeline, pickup times and venues through our quote form.' ),
			array( 'Can you shuttle guests between the ceremony and reception?', 'Yes. We handle pickup and drop-off for the couple, wedding party and guests, rides between the ceremony and reception, the send-off at the end of the night, and airport rides for out-of-town guests.' ),
		),
		'night-out'    => array(
			array( 'Can we make several stops during the night?', 'Yes. For a night out with several stops, an hourly booking is usually the best value. Your chauffeur stays with your group and is ready whenever you are, from dinner to rooftop bars and the ride home.' ),
			array( 'How many people fit in the party bus?', 'Our Mercedes-Benz Sprinter carries up to 14 passengers, ideal for birthdays, bachelor and bachelorette parties, and concerts. Smaller groups can ride in a luxury SUV for up to 6.' ),
			array( 'Do you drive late at night?', "Yes. Mecca Limo runs 24/7, so late pickups and early-morning rides home are no problem. Call or text $p to book." ),
		),
		'corporate'    => array(
			array( 'Do you handle conventions and multi-day events?', 'Yes. We move executives, clients and event guests for meetings, conventions and multi-day events, including coordinating airport arrivals from flight itineraries so guests are met on time.' ),
			array( 'Which airports do you serve for business travel?', 'Charleston International Airport (CHS), Charleston Executive Airport (JZI) and Mount Pleasant Regional Airport (MPR), with chauffeured sedans, SUVs and Sprinters available 24/7.' ),
			array( 'How do I get a corporate quote?', "Send your dates, pickup points and group size through our quote form, or call $p. We reply with pricing and availability." ),
		),
		'events'       => array(
			array( 'What events do you provide transportation for?', 'Proms, galas, birthdays, anniversaries, concerts and other celebrations across Charleston and the Lowcountry, in Sprinters, luxury SUVs and sedans with professional chauffeurs.' ),
			array( 'How early should we book prom transportation?', 'Book as soon as you know the date. Prom nights and spring weekends are busy, and booking early gives your group the best choice of vehicles.' ),
		),
		'attractions'  => array(
			array( 'Can you create a custom Charleston tour?', 'Yes. We build private sightseeing routes around what you want to see, such as the historic downtown, Patriots Point, plantations like Magnolia and Drayton Hall, and the beaches. Your chauffeur waits while you explore.' ),
			array( 'How do I book a sightseeing car service?', "Tell us your start time, the places you want to visit and how many people are in your group through the quote form, or call $p. Hourly bookings work best for tours." ),
		),
		'beach'        => array(
			array( 'Which beaches do you drive to?', "Folly Beach, Isle of Palms, Sullivan's Island, Kiawah Island and other Charleston-area beaches, from downtown, your hotel or the airport." ),
			array( 'Can you pick us up at the end of the day?', 'Yes. Choose round trip on the quote form and add your return time, and your chauffeur will be back to bring your group home.' ),
		),
		'golf-courses' => array(
			array( 'Do you drive to golf courses on Kiawah Island and around Charleston?', 'Yes. We take golfers to courses across the Charleston area, including Kiawah Island and Wild Dunes, from the airport, your hotel or your rental, and bring you back after your round.' ),
			array( 'Will our golf bags fit?', 'Tell us how many players and bags you have on the quote form and we will send a vehicle with enough room, such as a luxury SUV or the Mercedes-Benz Sprinter for larger groups.' ),
		),
		'hotels'       => array(
			array( 'Do you provide rides from the airport to downtown Charleston hotels?', 'Yes. We offer private rides between Charleston International Airport (CHS) and hotels across Charleston, 24 hours a day. Your chauffeur meets you on arrival and takes you straight to your hotel.' ),
			array( 'Is this a shared shuttle?', 'No. Every ride is private for your group only, in a sedan, luxury SUV or Mercedes-Benz Sprinter.' ),
		),
		'cruise-trips' => array(
			array( 'Do you drive to and from the Charleston cruise terminal?', 'Yes. We provide pickups and drop-offs at the Charleston cruise port from the airport, your hotel or your home, with room for your luggage.' ),
			array( 'Can you meet us when the ship returns?', 'Yes. Book your return date and time on the quote form and your chauffeur will be waiting when you come off the ship.' ),
		),
		'kiawah-island-car-service' => array(
			array( 'How long is the drive from Charleston airport to Kiawah Island?', 'Roughly an hour from Charleston International Airport (CHS), depending on traffic. Share your flight number and your chauffeur will track your arrival and meet you on time.' ),
			array( 'Do you drive Kiawah wedding guests and golf groups?', 'Yes. We move wedding parties and guests between hotels and venues, and take golfers and their clubs to and from Kiawah Island courses, in sedans, luxury SUVs and a Mercedes-Benz Sprinter for up to 14.' ),
		),
		'mount-pleasant-limo-service' => array(
			array( 'Do you serve Mount Pleasant, SC 24/7?', "Yes. Mecca Limo serves Mount Pleasant 24 hours a day, 7 days a week, including airport rides, weddings, nights out on Shem Creek and trips downtown. Call or text $p." ),
			array( 'Which airports do you serve from Mount Pleasant?', 'Charleston International Airport (CHS) and Mount Pleasant Regional Airport (MPR), with flight tracking on airport pickups.' ),
		),
		'charleston-hourly-limo-charter' => array(
			array( 'How many hours can I book?', 'You can choose from 2 up to 12 hours on our quote form. Your chauffeur stays with you for the whole booking and drives you to every stop.' ),
			array( 'Which vehicles can I charter by the hour?', 'Executive sedans for up to 3, luxury SUVs for up to 6 and the Mercedes-Benz Sprinter for up to 14 passengers.' ),
		),
		'service'      => array(
			array( 'What is a black car service?', 'A black car service is a private, pre-booked ride in a professionally maintained luxury vehicle with a professional chauffeur. Mecca Limo provides black car service in Charleston, SC 24/7 for airport transfers, weddings, corporate travel and nights out.' ),
			array( 'What areas do you serve?', 'Charleston and the wider Lowcountry, including Mount Pleasant, North Charleston, Kiawah Island, Seabrook Island, Isle of Palms, Folly Beach, Sullivan\'s Island, Summerville and Georgetown.' ),
		),
	);
	return isset( $f[ $slug ] ) ? $f[ $slug ] : array();
}

function mecca_seo_slug() {
	$p = get_queried_object();
	return ( $p instanceof WP_Post ) ? $p->post_name : '';
}

// Rank Math schema: complete business entity, no Article on pages, plus Service and breadcrumbs.
add_filter( 'rank_math/json_ld', function ( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}
	$home = home_url( '/' );
	foreach ( $data as $k => $e ) {
		if ( ! is_array( $e ) || empty( $e['@type'] ) ) {
			continue;
		}
		$types = (array) $e['@type'];
		if ( is_page() && array_intersect( $types, array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
			unset( $data[ $k ] );
			continue;
		}
		if ( array_intersect( $types, array( 'LocalBusiness', 'Organization', 'LimousineService' ) ) ) {
			$e['@type']        = array( 'LimousineService', 'Organization' );
			$e['name']         = 'Mecca Limo';
			$e['alternateName'] = 'Mecca Limo Chauffeur Service';
			$e['url']          = $home;
			$e['email']        = 'info@meccalimo.com';
			$e['telephone']    = '+1-843-804-1188';
			$e['priceRange']   = '$$$';
			$e['image']        = plugins_url( 'assets/open-first.webp', __FILE__ );
			$e['areaServed']   = array_map( function ( $c ) {
				return array( '@type' => 'City', 'name' => $c . ', SC' );
			}, mecca_seo_areas() );
			$e['openingHoursSpecification'] = array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				'opens'     => '00:00',
				'closes'    => '23:59',
			);
			unset( $e['openingHours'] );
			$e['knowsAbout'] = array( 'Airport transfers', 'Wedding transportation', 'Party bus rental', 'Corporate car service', 'Black car service', 'Chauffeur service' );
			$data[ $k ]      = $e;
		}
	}
	$has_org = false;
	$has_page = false;
	foreach ( $data as $e ) {
		$ty = is_array( $e ) && isset( $e['@type'] ) ? (array) $e['@type'] : array();
		if ( array_intersect( $ty, array( 'LimousineService', 'Organization', 'LocalBusiness' ) ) ) {
			$has_org = true;
		}
		if ( array_intersect( $ty, array( 'WebPage', 'AboutPage', 'ContactPage', 'CollectionPage' ) ) ) {
			$has_page = true;
		}
	}
	if ( ! $has_org ) {
		$data['MeccaOrg'] = array(
			'@type'      => array( 'LimousineService', 'Organization' ),
			'@id'        => $home . '#organization',
			'name'       => 'Mecca Limo',
			'alternateName' => 'Mecca Limo Chauffeur Service',
			'url'        => $home,
			'logo'       => array( '@type' => 'ImageObject', 'url' => 'https://www.meccalimo.com/wp-content/uploads/2026/10/mecca-limo-logo-gold.png' ),
			'image'      => plugins_url( 'assets/open-first.webp', __FILE__ ),
			'telephone'  => '+1-843-804-1188',
			'email'      => 'info@meccalimo.com',
			'priceRange' => '$$$',
			'address'    => array( '@type' => 'PostalAddress', 'addressLocality' => 'Charleston', 'addressRegion' => 'SC', 'addressCountry' => 'US' ),
			'areaServed' => array_map( function ( $c ) {
				return array( '@type' => 'City', 'name' => $c . ', SC' );
			}, mecca_seo_areas() ),
			'openingHoursSpecification' => array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				'opens'     => '00:00',
				'closes'    => '23:59',
			),
			'sameAs'     => array( 'https://www.facebook.com/profile.php?id=100090948969233', 'https://x.com/meccalimo', 'https://www.instagram.com/meccalimo/', 'https://www.tiktok.com/@meccalimo' ),
		);
	}
	if ( is_singular() && ! $has_page ) {
		$data['MeccaWebPage'] = array(
			'@type'      => 'WebPage',
			'@id'        => get_permalink() . '#webpage',
			'url'        => get_permalink(),
			'name'       => wp_get_document_title(),
			'isPartOf'   => array( '@id' => $home . '#website' ),
			'about'      => array( '@id' => $home . '#organization' ),
			'inLanguage' => 'en-US',
		);
	}
	$slug = mecca_seo_slug();
	if ( is_page() && ! is_front_page() ) {
		$data['MeccaBreadcrumb'] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => get_permalink() . '#breadcrumb',
			'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => wp_strip_all_tags( get_the_title() ), 'item' => get_permalink() ),
			),
		);
	}
	$svc = mecca_seo_services();
	if ( is_page() && isset( $svc[ $slug ] ) ) {
		$data['MeccaService'] = array(
			'@type'       => 'Service',
			'@id'         => get_permalink() . '#service',
			'name'        => $svc[ $slug ][0],
			'serviceType' => $svc[ $slug ][1],
			'url'         => get_permalink(),
			'provider'    => array( '@id' => $home . '#organization' ),
			'areaServed'  => array_map( function ( $c ) {
				return array( '@type' => 'City', 'name' => $c . ', SC' );
			}, mecca_seo_areas() ),
			'availableChannel' => array( '@type' => 'ServiceChannel', 'servicePhone' => '+1-843-804-1188', 'serviceUrl' => home_url( '/get-a-quote/' ) ),
		);
	}
	$faqs = mecca_seo_faqs( $slug );
	if ( is_page() && $faqs ) {
		$data['MeccaFAQ'] = array(
			'@type'      => 'FAQPage',
			'@id'        => get_permalink() . '#faq',
			'mainEntity' => array_map( function ( $q ) {
				return array( '@type' => 'Question', 'name' => $q[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $q[1] ) );
			}, $faqs ),
		);
	}
	return $data;
}, 99 );

// Hand-written llms.txt for AI assistants (replaces the auto-generated blog list).
add_action( 'init', function () {
	$path = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
	if ( '/llms.txt' !== $path ) {
		return;
	}
	$u = home_url( '/' );
	$t = "# Mecca Limo\n\n> Mecca Limo is a family-owned limo and black car service in Charleston, SC, available 24/7. We provide chauffeured airport transfers to and from Charleston International Airport (CHS), wedding transportation, nights out and party bus rentals, corporate travel, events and prom, sightseeing tours, beach trips, golf course transfers, hotel transfers, and cruise port rides.\n\n"
		. "- Location: Charleston, SC\n- Hours: 24 hours a day, 7 days a week\n- Phone (call or text): (843) 804-1188\n- Email: info@meccalimo.com\n- Free quote: {$u}get-a-quote/\n- Service area: " . implode( ', ', mecca_seo_areas() ) . "\n\n"
		. "## Fleet\n- Mercedes-Benz Sprinter: up to 14 passengers with luggage, for wedding parties, corporate groups and nights out\n- Luxury SUVs (such as Cadillac Escalade, Chevrolet Suburban and GMC Yukon Denali): up to 6 passengers with luggage\n- Executive sedan: up to 3 passengers with 2 bags, for airport runs and business travel\n- Full fleet: {$u}charleston-limo-fleet/\n\n"
		. "## Services\n"
		. "- [Airport Transfers]({$u}airport/): 24/7 car service to and from Charleston International Airport (CHS), with flight tracking\n"
		. "- [Wedding Transportation]({$u}wedding/): rides for the couple, wedding party and guests\n"
		. "- [Night Out & Party Bus]({$u}night-out/): Sprinter party bus for birthdays, concerts, bachelor and bachelorette parties\n"
		. "- [Corporate Car Service]({$u}corporate/): meetings, conventions and executive travel\n"
		. "- [Events & Prom]({$u}events/): prom and special event rides\n"
		. "- [Sightseeing & Tours]({$u}attractions/): private car service to Charleston landmarks\n"
		. "- [Beach Trips]({$u}beach/): Folly Beach, Isle of Palms, Sullivan's Island and Kiawah\n"
		. "- [Golf Courses]({$u}golf-courses/): transportation to Charleston-area golf courses\n"
		. "- [Hotel Transfers]({$u}hotels/): private rides between CHS and Charleston hotels\n"
		. "- [Cruise Port]({$u}cruise-trips/): rides to and from the Charleston cruise terminal\n"
		. "- [Hourly Charter]({$u}charleston-hourly-limo-charter/): Sprinters, SUVs and sedans by the hour\n"
		. "- [Kiawah Island Car Service]({$u}kiawah-island-car-service/)\n"
		. "- [Mount Pleasant Limo Service]({$u}mount-pleasant-limo-service/)\n\n"
		. "## Company\n"
		. "- [All Services]({$u}service/)\n- [About]({$u}about/): our family-owned Charleston chauffeur company\n- [Reviews]({$u}reviews/): 5.0 rating on Google\n- [Contact]({$u}contact/)\n- [Get a Quote]({$u}get-a-quote/)\n- [Booking & Cancellation Policy]({$u}policy/)\n";
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	echo $t; // phpcs:ignore
	exit;
}, 0 );

// Keep internal search results out of the crawl.
add_filter( 'robots_txt', function ( $out ) {
	if ( false === strpos( $out, 'Disallow: /?s=' ) ) {
		$out = preg_replace( '#(Disallow: /wp-admin/\s*\n)#', "$1Disallow: /?s=\nDisallow: /search/\n", $out, 1 );
	}
	return $out;
}, 99 );
