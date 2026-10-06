<?php
/**
 * Search and AI-search signals for Mecca Limo: schema, page FAQs, llms.txt, robots.txt.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mecca_seo_areas() {
	return array( 'Charleston', 'Mount Pleasant', 'North Charleston', 'Daniel Island', 'Kiawah Island', 'Seabrook Island', 'Isle of Palms', 'Folly Beach', "Sullivan's Island", 'Summerville', 'Georgetown' );
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
		'charleston-bachelorette-party-transportation' => array( 'Bachelorette Party Transportation', 'Party bus rental' ),
		'daniel-island-car-service'      => array( 'Daniel Island Car Service', 'Car service' ),
		'summerville-limo-service'       => array( 'Summerville Limo Service', 'Limo service' ),
		'charleston-airport-pickup-guide' => array( 'Charleston Airport Pickup', 'Airport transfer' ),
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
			array( 'Do you serve Charleston Executive Airport (JZI) and Mount Pleasant (LRO)?', 'Yes. We meet private flights at Charleston Executive Airport on Johns Island, including the Atlantic Aviation FBO, and at Mount Pleasant Regional Airport, and drive you to Kiawah, Wild Dunes, downtown or anywhere in the Lowcountry.' ),
			array( 'How much is a car service from CHS to downtown Charleston or Kiawah?', 'Airport rides are quoted as a flat price based on your route, vehicle and time, and you get the price before you book. Send your flight details through our quote form or call for an exact price.' ),
			array( 'Can you pick up very early or late at night?', 'Yes. We run 24/7, including first flights of the morning and late-night arrivals.' ),
			array( 'Do you drive to airports outside Charleston?', 'Yes. We offer one-way and round-trip transfers to airports outside the Charleston area. Ask for a quote.' ),
		),
		'wedding'      => array(
			array( 'How many wedding guests fit in one vehicle?', 'Our Mercedes-Benz Sprinter carries up to 14 passengers, which suits most wedding parties. Luxury SUVs carry up to 6, and our sedans are ideal for the couple. Many weddings book a mix so everyone arrives together.' ),
			array( 'How far ahead should we book wedding transportation?', 'As early as possible. Spring and fall weekends in Charleston fill quickly, so reserve as soon as you have your date, then send your timeline, pickup times and venues through our quote form.' ),
			array( 'Can you shuttle guests between the ceremony and reception?', 'Yes. We handle pickup and drop-off for the couple, wedding party and guests, rides between the ceremony and reception, the send-off at the end of the night, and airport rides for out-of-town guests.' ),
			array( 'How much does a wedding limo cost in Charleston?', 'Most weddings book by the hour. Single rides can be quoted as a flat price. You get your price before you book; request a free quote or call (843) 804-1188.' ),
			array( 'Which wedding venues do you drive to?', 'Venues across the Lowcountry, from downtown Charleston churches and historic houses to plantations on the Ashley River, Mount Pleasant waterfront venues and resorts on Kiawah, Seabrook, Wild Dunes and Isle of Palms.' ),
			array( 'Do you handle bachelor and bachelorette parties too?', 'Yes. We drive bachelor and bachelorette nights, rehearsal dinners and welcome parties, so the whole wedding weekend is covered.' ),
		),
		'night-out'    => array(
			array( 'Can we make several stops during the night?', 'Yes. For a night out with several stops, an hourly booking is usually the best value. Your chauffeur stays with your group and is ready whenever you are, from dinner to rooftop bars and the ride home.' ),
			array( 'How many people fit in the party bus?', 'Our Mercedes-Benz Sprinter carries up to 14 passengers, ideal for birthdays, bachelor and bachelorette parties, and concerts. Smaller groups can ride in a luxury SUV for up to 6.' ),
			array( 'Do you drive late at night?', "Yes. Mecca Limo runs 24/7, so late pickups and early-morning rides home are no problem. Call or text $p to book." ),
			array( 'How much does a party bus cost in Charleston?', 'Most nights out are booked by the hour with a 3-hour minimum. You get your price before you book; request a free quote or call (843) 804-1188.' ),
			array( 'Is there a cleaning fee?', 'Yes. If the vehicle needs unusual cleaning, such as spills, food or sickness, a cleaning fee applies. See our booking policy for details.' ),
			array( 'Do you do bachelorette parties?', 'Yes. Bachelorette weekends are one of our most popular bookings. See our Charleston bachelorette transportation guide for a sample itinerary and pricing.' ),
		),
		'corporate'    => array(
			array( 'Do you handle conventions and multi-day events?', 'Yes. We move executives, clients and event guests for meetings, conventions and multi-day events, including coordinating airport arrivals from flight itineraries so guests are met on time.' ),
			array( 'Which airports do you serve for business travel?', 'Charleston International Airport (CHS), Charleston Executive Airport (JZI) and Mount Pleasant Regional Airport (LRO), with chauffeured sedans, SUVs and Sprinters available 24/7.' ),
			array( 'How do I get a corporate quote?', "Send your dates, pickup points and group size through our quote form, or call $p. We reply with pricing and availability." ),
			array( 'How much does corporate car service cost?', 'Airport and point-to-point rides are quoted as a flat price. You get your price before you book; request a free quote or call (843) 804-1188.' ),
			array( 'Can a chauffeur wait between meetings?', 'Yes. Book by the hour and your chauffeur stays with you between appointments across downtown, North Charleston, Daniel Island and Mount Pleasant.' ),
		),
		'charleston-bachelorette-party-transportation' => array(
			array( 'How many people fit in your bachelorette party bus?', 'Our Mercedes-Benz Sprinter seats up to 14 passengers. Smaller groups of up to 6 can ride in a luxury SUV.' ),
			array( 'How many hours should we book for a bachelorette night?', 'Most bachelorette nights run four to six hours from the first pickup to the last drop-off. Hourly bookings have a 3-hour minimum and you can add time on the night.' ),
			array( 'Can you pick our group up from the airport?', 'Yes. We pick up groups at Charleston International Airport (CHS) and drop everyone at their rental or hotel, then handle departures at the end of the weekend.' ),
			array( 'How much does a bachelorette party bus cost in Charleston?', 'Most bachelorette nights are booked by the hour with a 3-hour minimum. You get your price before you book; request a free quote or call (843) 804-1188. A cleaning fee applies if the vehicle needs unusual cleaning.' ),
		),
		'events'       => array(
			array( 'What events do you provide transportation for?', 'Proms, galas, birthdays, anniversaries, concerts and other celebrations across Charleston and the Lowcountry, in Sprinters, luxury SUVs and sedans with professional chauffeurs.' ),
			array( 'How early should we book prom transportation?', 'Book as soon as you know the date. Prom nights and spring weekends are busy, and booking early gives your group the best choice of vehicles.' ),
			array( 'How much does a prom limo cost in Charleston?', 'Most prom and event bookings are by the hour with a 3-hour minimum. You get your price before you book; request a free quote or call (843) 804-1188.' ),
		),
		'attractions'  => array(
			array( 'Can you create a custom Charleston tour?', 'Yes. We build private sightseeing routes around what you want to see, such as the historic downtown, Patriots Point, plantations like Magnolia and Drayton Hall, and the beaches. Your chauffeur waits while you explore.' ),
			array( 'How do I book a sightseeing car service?', "Tell us your start time, the places you want to visit and how many people are in your group through the quote form, or call $p. Hourly bookings work best for tours." ),
			array( 'How much is a private sightseeing tour?', 'Tours are booked by the hour with a 3-hour minimum. You get your price before you book; request a free quote or call (843) 804-1188.' ),
		),
		'beach'        => array(
			array( 'Which beaches do you drive to?', "Folly Beach, Isle of Palms, Sullivan's Island, Kiawah Island and other Charleston-area beaches, from downtown, your hotel or the airport." ),
			array( 'Can you pick us up at the end of the day?', 'Yes. Choose round trip on the quote form and add your return time, and your chauffeur will be back to bring your group home.' ),
			array( 'How much is beach transportation from Charleston?', 'Point-to-point beach rides are quoted as a flat price. You get your price before you book; request a free quote or call (843) 804-1188.' ),
		),
		'golf-courses' => array(
			array( 'Do you drive to golf courses on Kiawah Island and around Charleston?', 'Yes. We take golfers to courses across the Charleston area, including Kiawah Island and Wild Dunes, from the airport, your hotel or your rental, and bring you back after your round.' ),
			array( 'Will our golf bags fit?', 'Tell us how many players and bags you have on the quote form and we will send a vehicle with enough room, such as a luxury SUV or the Mercedes-Benz Sprinter for larger groups.' ),
			array( 'How much is golf transportation?', 'Point-to-point and airport rides are quoted as a flat price. You get your price before you book; request a free quote or call (843) 804-1188.' ),
		),
		'hotels'       => array(
			array( 'Do you provide rides from the airport to downtown Charleston hotels?', 'Yes. We offer private rides between Charleston International Airport (CHS) and hotels across Charleston, 24 hours a day. Your chauffeur meets you on arrival and takes you straight to your hotel.' ),
			array( 'Is this a shared shuttle?', 'No. Every ride is private for your group only, in a sedan, luxury SUV or Mercedes-Benz Sprinter.' ),
			array( 'How much is a ride from CHS to my hotel?', 'Hotel transfers are quoted as a flat price based on your hotel, vehicle and time, and you know the price before you book.' ),
		),
		'cruise-trips' => array(
			array( 'Do you drive to and from the Charleston cruise terminal?', 'Yes. We provide pickups and drop-offs at the Charleston cruise port from the airport, your hotel or your home, with room for your luggage.' ),
			array( 'Can you meet us when the ship returns?', 'Yes. Book your return date and time on the quote form and your chauffeur will be waiting when you come off the ship.' ),
			array( 'How much is a ride to the Charleston cruise terminal?', 'Cruise transfers are quoted as a flat price based on your pickup point, vehicle and time. Request a free quote with your sailing date.' ),
		),
		'kiawah-island-car-service' => array(
			array( 'How long is the drive from Charleston airport to Kiawah Island?', 'Roughly an hour from Charleston International Airport (CHS), depending on traffic. Share your flight number and your chauffeur will track your arrival and meet you on time.' ),
			array( 'Do you drive Kiawah wedding guests and golf groups?', 'Yes. We move wedding parties and guests between hotels and venues, and take golfers and their clubs to and from Kiawah Island courses, in sedans, luxury SUVs and a Mercedes-Benz Sprinter for up to 14.' ),
		),
		'mount-pleasant-limo-service' => array(
			array( 'Do you serve Mount Pleasant, SC 24/7?', "Yes. Mecca Limo serves Mount Pleasant 24 hours a day, 7 days a week, including airport rides, weddings, nights out on Shem Creek and trips downtown. Call or text $p." ),
			array( 'Which airports do you serve from Mount Pleasant?', 'Charleston International Airport (CHS) and Mount Pleasant Regional Airport (LRO), with flight tracking on airport pickups.' ),
		),
		'daniel-island-car-service' => array(
			array( 'How far is Daniel Island from Charleston airport?', 'Usually about 15 to 20 minutes from Charleston International Airport (CHS) by I-526, depending on traffic. Share your flight number and your chauffeur will track your arrival.' ),
			array( 'Can you drive us to a concert or tennis event on Daniel Island?', "Yes. We drop your group at the stadium and pick you up when the event ends, in a sedan, luxury SUV or a Mercedes-Benz Sprinter for up to 14. Call or text $p to book." ),
		),
		'summerville-limo-service' => array(
			array( 'Do you serve Summerville, SC 24/7?', "Yes. Mecca Limo serves Summerville, Nexton and Cane Bay 24 hours a day, 7 days a week, including airport rides, weddings, prom and nights out in downtown Charleston. Call or text $p." ),
			array( 'How long is the drive from Summerville to Charleston airport?', 'Usually about 25 to 35 minutes to Charleston International Airport (CHS), depending on traffic.' ),
		),
		'charleston-airport-pickup-guide' => array(
			array( 'Where will my chauffeur meet me at CHS?', 'Your chauffeur contacts you when your flight lands and tells you exactly where to meet. Share your flight number when you book so we can track your arrival.' ),
			array( 'How much wait time is included on airport pickups?', 'Airport pickups include 20 minutes of wait time from landing, enough time for most baggage claims.' ),
			array( 'How long is the drive from Charleston airport to downtown?', 'About 20 to 25 minutes by car, depending on traffic. Kiawah Island is about 50 to 60 minutes and Mount Pleasant about 25 to 30 minutes.' ),
		),
		'charleston-hourly-limo-charter' => array(
			array( 'Is there a minimum number of hours?', 'Yes. Every vehicle has a 3-hour minimum: executive sedans, luxury SUVs and the Mercedes Sprinter. See our booking and cancellation policy for details. Your chauffeur stays with you for the whole booking and drives you to every stop.' ),
			array( 'Which vehicles can I charter by the hour?', 'Executive sedans for up to 3, luxury SUVs for up to 6 and the Mercedes-Benz Sprinter for up to 14 passengers.' ),
		),
		'service'      => array(
			array( 'What is a black car service?', 'A black car service is private, pre-booked transportation in a luxury vehicle with a professional chauffeur. The ride is reserved for you alone and the price is agreed before you get in. Mecca Limo provides black car service in Charleston, SC 24/7 for airport transfers, weddings, corporate travel and nights out.' ),
			array( 'Can I book a black car from Charleston International Airport (CHS)?', "Yes. We pick up and drop off at CHS, Mount Pleasant Regional (LRO) and Charleston Executive Airport (JZI) 24/7. Share your flight number and your chauffeur tracks your arrival; 20 minutes of wait time from landing is included. Call or text $p to book." ),
			array( 'How much does black car service cost in Charleston?', 'Airport and point-to-point rides are quoted as a flat price, and hourly bookings have a 3-hour minimum. You get your price before you book; request a free quote or call (843) 804-1188.' ),
			array( 'Which vehicle should I book?', 'An executive sedan fits up to 3 passengers with 2 bags, a luxury SUV fits up to 6 with luggage, and the Mercedes-Benz Sprinter fits up to 14. Tell us your group size and luggage and we will match the right vehicle.' ),
			array( 'Do you offer corporate black car service?', 'Yes. We handle executive airport pickups, rides between meetings, client dinners and group shuttles for conferences and events, with one point of contact for every ride.' ),
			array( 'Can I use a black car for a wedding or event?', 'Yes. We transport couples, wedding parties and guests between hotels, ceremonies and receptions, plus proms, birthdays, concerts and nights out.' ),
			array( 'How far in advance should I book?', 'As early as possible for weddings, holidays and busy Charleston weekends. A few days ahead is ideal for airport and business rides. We also take same-day requests when a vehicle is free.' ),
			array( 'What areas do you serve?', 'Charleston and the wider Lowcountry, including Mount Pleasant, North Charleston, Kiawah Island, Seabrook Island, Isle of Palms, Folly Beach, Sullivan\'s Island, Summerville and Georgetown, plus longer trips such as Edisto Beach, Pawleys Island, Bluffton and out of town.' ),
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
			$e['@type']        = 'LocalBusiness';
			$e['name']         = 'Mecca Limo';
			$e['alternateName'] = 'Mecca Limo Chauffeur Service';
			$e['url']          = $home;
			unset( $e['email'] );
			$e['hasMap']       = 'https://www.google.com/maps/place/?q=place_id:ChIJBZcVgzl5_ogRVn5LmaHjB3s';
			$e['sameAs']       = array_values( array_unique( array_merge( isset( $e['sameAs'] ) ? (array) $e['sameAs'] : array(), array( 'https://www.facebook.com/profile.php?id=100090948969233', 'https://x.com/meccalimo', 'https://www.instagram.com/meccalimo/', 'https://www.tiktok.com/@meccalimo', 'https://www.linkedin.com/in/moe-shalaby-60a8602aa/', 'https://www.youtube.com/channel/UClskMeHN_YUk3bRdJgpF95A' ) ) ) );
			$e['address']      = array( '@type' => 'PostalAddress', 'streetAddress' => '1914 Weeping Cypress Dr', 'addressLocality' => 'Charleston', 'addressRegion' => 'SC', 'postalCode' => '29412', 'addressCountry' => 'US' );
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
			'@type'      => 'LocalBusiness',
			'@id'        => $home . '#organization',
			'name'       => 'Mecca Limo',
			'alternateName' => 'Mecca Limo Chauffeur Service',
			'url'        => $home,
			'logo'       => array( '@type' => 'ImageObject', 'url' => 'https://www.meccalimo.com/wp-content/uploads/2026/10/mecca-limo-logo-gold.png' ),
			'image'      => plugins_url( 'assets/open-first.webp', __FILE__ ),
			'telephone'  => '+1-843-804-1188',
			'priceRange' => '$$$',
			'address'    => array( '@type' => 'PostalAddress', 'streetAddress' => '1914 Weeping Cypress Dr', 'addressLocality' => 'Charleston', 'addressRegion' => 'SC', 'postalCode' => '29412', 'addressCountry' => 'US' ),
			'areaServed' => array_map( function ( $c ) {
				return array( '@type' => 'City', 'name' => $c . ', SC' );
			}, mecca_seo_areas() ),
			'openingHoursSpecification' => array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				'opens'     => '00:00',
				'closes'    => '23:59',
			),
			'sameAs'     => array( 'https://www.facebook.com/profile.php?id=100090948969233', 'https://x.com/meccalimo', 'https://www.instagram.com/meccalimo/', 'https://www.tiktok.com/@meccalimo', 'https://www.linkedin.com/in/moe-shalaby-60a8602aa/', 'https://www.youtube.com/channel/UClskMeHN_YUk3bRdJgpF95A' ),
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
	// Freshness signal on the page entity.
	if ( is_page() ) {
		foreach ( $data as $k => $e ) {
			if ( is_array( $e ) && isset( $e['@type'] ) && array_intersect( (array) $e['@type'], array( 'WebPage', 'AboutPage', 'ContactPage' ) ) ) {
				$data[ $k ]['datePublished'] = get_the_date( 'c' );
				$data[ $k ]['dateModified']  = get_the_modified_date( 'c' );
			}
		}
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
		. "- [Party Bus & Bachelorette]({$u}night-out/): Sprinter party bus for birthdays, concerts, bachelor and bachelorette parties\n"
		. "- [Corporate Car Service]({$u}corporate/): meetings, conventions and executive travel\n"
		. "- [Events & Prom]({$u}events/): prom and special event rides\n"
		. "- [Sightseeing & Tours]({$u}attractions/): private car service to Charleston landmarks\n"
		. "- [Beach Trips]({$u}beach/): Folly Beach, Isle of Palms, Sullivan's Island and Kiawah\n"
		. "- [Golf Courses]({$u}golf-courses/): transportation to Charleston-area golf courses\n"
		. "- [Hotel Transfers]({$u}hotels/): private rides between CHS and Charleston hotels\n"
		. "- [Cruise Port]({$u}cruise-trips/): rides to and from the Charleston cruise terminal\n"
		. "- [Hourly Charter]({$u}charleston-hourly-limo-charter/): Sprinters, SUVs and sedans by the hour\n"
		. "- [Bachelorette Party Transportation]({$u}charleston-bachelorette-party-transportation/): party bus guide, itinerary and pricing\n"
		. "- [Kiawah Island Car Service]({$u}kiawah-island-car-service/)\n"
		. "- [Mount Pleasant Limo Service]({$u}mount-pleasant-limo-service/)\n"
		. "- [Daniel Island Car Service]({$u}daniel-island-car-service/)\n"
		. "- [Summerville Limo Service]({$u}summerville-limo-service/)\n"
		. "- [Charleston Airport (CHS) Pickup Guide]({$u}charleston-airport-pickup-guide/): how pickups work and drive times\n\n"
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

// Meta (Facebook) Pixel. Set the ID with update_option( 'mecca_fb_pixel_id', '...' ).
// fbevents.js loads on the first interaction, or shortly after the page has loaded,
// so it never slows the first paint but every visit is still counted.
add_action( 'wp_head', function () {
	$id = preg_replace( '/\D/', '', (string) get_option( 'mecca_fb_pixel_id' ) );
	if ( ! $id || is_admin() ) {
		return;
	}
	?>
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];
var load=function(){if(t)return;t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s);},go=function(){setTimeout(function(){(f.requestIdleCallback||setTimeout)(load,{timeout:3000});},800);};
['scroll','touchstart','pointerdown','mousemove','keydown','click','wheel'].forEach(function(x){f.addEventListener(x,go,{once:true,passive:true});});
f.addEventListener('load',function(){setTimeout(load,7000);});
}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init','<?php echo esc_js( $id ); ?>');fbq('track','PageView');
document.addEventListener('click',function(ev){var a=ev.target.closest&&ev.target.closest('a[href^="tel:"],a[href^="sms:"]');if(a)fbq('track','Contact');});
document.addEventListener('submit',function(ev){if(ev.target.closest&&ev.target.closest('.mqf,form[id*="quote"],#mqf'))fbq('track','Lead');});
</script>
<noscript><img height="1" width="1" alt="Meta Pixel" src="https://www.facebook.com/tr?id=<?php echo esc_attr( $id ); ?>&amp;ev=PageView&amp;noscript=1"></noscript>
	<?php
}, 20 );

// Meta (Facebook) domain verification for meccalimo.com.
add_action( 'wp_head', function () {
	echo '<meta name="facebook-domain-verification" content="9hvv9tal1zgi0653vyqvwee0zrjxrf" />' . "\n";
}, 1 );
