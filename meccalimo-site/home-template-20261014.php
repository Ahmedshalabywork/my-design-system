<?php
/**
 * Mecca Limo homepage template.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$a     = 'mecca_home_asset';
$phone = '(843) 804-1188';
$tel   = 'tel:+18438041188';
$email = antispambot( 'info@meccalimo.com' );
$logo  = $a( 'logo.webp' );
$is_home = is_front_page();
$h     = $is_home ? '' : esc_url( home_url( '/' ) );
$is_quote = is_page( 'get-a-quote' );
$is_page  = ! $is_home && ! $is_quote;
$pg       = $is_page ? mecca_home_parse_page( get_post()->post_content ) : null;
if ( $is_page && ! $pg['h1'] ) {
	$pg['h1'] = get_the_title();
}

$services = array(
	array( 'Weddings', '/wedding/', 'On-time, on-theme limo service for the couple, the wedding party and guests.' ),
	array( 'Airport Transfers', '/airport/', 'Pickups and drop-offs at Charleston International (CHS), with flight tracking.' ),
	array( 'Nights Out & Bachelorette', '/night-out/', 'Birthdays, bachelorette weekends, concerts. Everyone gets home safely.' ),
	array( 'Corporate', '/corporate/', 'Discreet chauffeurs and clean billing for executives and clients.' ),
	array( 'Events & Prom', '/events/', 'Proms, galas and celebrations, arriving together in style.' ),
	array( 'Sightseeing & Tours', '/attractions/', 'Historic landmarks, plantations, museums and custom Charleston tours.' ),
	array( 'Beach Transportation', '/beach/', 'Folly Beach, Sullivan\'s Island, Kiawah and more.' ),
	array( 'Golf Courses', '/golf-courses/', 'Kiawah, Wild Dunes and the Lowcountry\'s best courses.' ),
	array( 'Hotel Transfers', '/hotels/', 'Door-to-door service to and from Charleston hotels and rentals.' ),
);

$svc_icons = array(
	'/wedding/' => '<path d="M12 21s-7-4.4-9.4-8.8A5.3 5.3 0 0 1 12 6.6a5.3 5.3 0 0 1 9.4 5.6C19 16.6 12 21 12 21z"/>',
	'/airport/' => '<path d="M21 15.5v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0v5l-8 5v2l8-2.5V18l-2 1.5V21l3.5-1 3.5 1v-1.5L13 18v-5z"/>',
	'/night-out/' => '<path d="M8 21h8M12 12v9M4 4h16l-8 8z"/><path d="M15 2l1 1"/>',
	'/corporate/' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 12h18"/>',
	'/events/' => '<path d="M12 3l2.6 5.5 6 .8-4.4 4.2 1.1 6L12 16.6 6.7 19.5l1.1-6L3.4 9.3l6-.8z"/>',
	'/attractions/' => '<path d="M3 21h18M5 21V11M9.5 21V11M14.5 21V11M19 21V11M2 11l10-7 10 7z"/>',
	'/beach/' => '<path d="M12 3v2M5.6 6.6 7 8M18.4 6.6 17 8M8 13a4 4 0 0 1 8 0"/><path d="M2 17c2 0 2-1.5 4-1.5s2 1.5 4 1.5 2-1.5 4-1.5 2 1.5 4 1.5 2-1.5 4-1.5M2 21c2 0 2-1.5 4-1.5s2 1.5 4 1.5 2-1.5 4-1.5 2 1.5 4 1.5 2-1.5 4-1.5"/>',
	'/golf-courses/' => '<path d="M7 21V3l10 4-10 4"/><path d="M3 21c2-2 6-2 8 0"/>',
	'/hotels/' => '<path d="M4 21V5l8-3 8 3v16M9.5 21v-4h5v4M8 8h2M14 8h2M8 12h2M14 12h2"/>',
	'/cruise-trips/' => '<path d="M2 19c2 1.2 4 1.2 6 0s4-1.2 6 0 4 1.2 6 0M4 15.5 5 10h14l1 5.5M12 3v7M9 6h6"/>',
);

$svc_imgs = array(
	'/wedding/' => 'e245e19a02c15876987ef60899ab2adb',
	'/airport/' => '86883af875361aeffb86fd99af01a01d',
	'/night-out/' => '2755b06a0cd8e5028fc072ddb34278a1',
	'/corporate/' => '23d95377a7dc6fc13c0b518eeece80ff',
	'/events/' => '228a2b491cffcaf59639b987953ea9b3',
	'/attractions/' => 'b2095bcfff4222eb944e4d3464e3f7cb',
	'/beach/' => '293e005a37083a6f852b579ca5878412',
	'/golf-courses/' => 'a5e7cc10d55a592bac4d03770fbf7b1d',
	'/hotels/' => '2b5a252430437b5e73424d4be8058c36',
	'/cruise-trips/' => 'b7db10a0ee9496bb87fbc312637b3aff',
);
$up_url = trailingslashit( wp_upload_dir()['baseurl'] ) . 'mecca-webp/';

$reviews = array(
	array( 'Tiffany Clark', 'Group Night Out', 'Mecca provided exceptional service at a price that blew the competition out of the water for our group of 10\'s trip into Charleston for dinner and dancing! Thank you, Mecca team!' ),
	array( 'Susan Gorsline', 'Medical Transport', 'When I unexpectedly needed a ride to Roper St. Francis after surgery, Mecca came to my aid! Richard was outstanding. He made sure I got there safely, waited with me, and even called later to see how things went.' ),
	array( 'Ann Cannady', 'Wedding', 'We used Mecca for our daughter\'s wedding weekend. They were amazing, always arrived early, very nice rides, and the drivers were so friendly and helpful. Thank you Moe and Mecca for making our weekend even more special.' ),
	array( 'Robert Ricker', 'Wedding', 'Our trip from Charleston to Kiawah Island for our son\'s wedding was made even more memorable thanks to Richard. Not only did he get us there comfortably, he turned the ride into a guided tour of the area.' ),
	array( 'Amanda Bagdonas', 'Corporate Event', 'Moe and the Mecca team provided exceptional service during our two-week event. They reviewed every flight itinerary so drivers arrived at the airport well ahead of schedule. Our guests felt cared for from arrival.' ),
	array( 'Michele Mavi', 'Corporate', 'I was planning a large event for a group of CEOs in Kiawah. The hotel\'s pricing was incredibly high. Mecca was outstanding and far more reasonable. I\'d recommend them without hesitation.' ),
	array( 'Sarah Parker', 'Group · Wedding', 'Our group of 14 was easily and happily transported to various wedding events in and around Charleston. Not only was the van beautiful and comfortable, but the driver was so great, she was like part of the group.' ),
	array( 'Jesse Kirchner', 'Loyal Client', 'My family and I have exclusively used Moe and his team for over 4 years now. From big parties to a simple date night on the town, they\'re always on time, friendly, and prompt with communication. Five stars all around!' ),
	array( 'Marvin Jackson', 'Private Aviation', 'I\'m in the private jet charter industry, so we frequently use ground limo transport. Mecca Limousine Service is easily one of the top limousine transport companies I\'ve worked with anywhere.' ),
);

$faqs = array(
	array( 'How far in advance should I book?', 'As early as you can for weddings and peak dates. Just phone ' . $phone . ', 24/7.' ),
	array( 'What areas do you serve?', 'Charleston and the wider Lowcountry, including Mount Pleasant, North Charleston, Kiawah Island, Seabrook Island, Isle of Palms, Folly Beach, Sullivan\'s Island, Summerville and Georgetown.' ),
	array( 'Which vehicles are in your fleet?', 'A Mercedes-Benz Sprinter for larger groups, luxury SUVs such as the Cadillac Escalade and Chevrolet Suburban, and an executive sedan for airport transfers and business travel. Tell us your group size and we will match the right vehicle.' ),
	array( 'Do you handle CHS airport transfers?', 'Yes. We pick up and drop off at Charleston International Airport (CHS) and the area\'s private aviation terminals. Share your flight number and your chauffeur will track your arrival.' ),
	array( 'Do you operate 24/7?', 'Yes. Mecca Limo runs around the clock, every day, including early-morning airport transfers and late nights out.' ),
	array( 'How do I get a quote?', 'Fill out the quote form on this page or phone ' . $phone . '. We will send a quote for your date and route, with no obligation.' ),
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0a0a0b">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo esc_url( $a( 'icon-96.png' ) ); ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url( $a( 'icon-192.png' ) ); ?>">
<?php if ( $is_home ) : ?><link rel="preload" as="image" href="<?php echo esc_url( $a( 'open3.webp' ) ); ?>" imagesrcset="<?php echo esc_url( $a( 'open3-480.webp' ) ); ?> 480w, <?php echo esc_url( $a( 'open3-s.webp' ) ); ?> 720w, <?php echo esc_url( $a( 'open3-828.webp' ) ); ?> 828w, <?php echo esc_url( $a( 'open3-m.webp' ) ); ?> 1080w, <?php echo esc_url( $a( 'open3-1440.webp' ) ); ?> 1440w, <?php echo esc_url( $a( 'open3.webp' ) ); ?> 1920w" imagesizes="100vw" fetchpriority="high"><?php endif; ?>
<link rel="preload" as="font" type="font/woff2" href="<?php echo esc_url( $a( 'fonts/cormorant.woff2' ) ); ?>" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="<?php echo esc_url( $a( 'fonts/jost.woff2' ) ); ?>" crossorigin>
<style>@font-face{font-family:'Cormorant Garamond';font-style:italic;font-weight:500 500;font-display:swap;src:url(<?php echo esc_url( plugins_url( 'assets/fonts', __FILE__ ) ); ?>/cormorant-italic.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD}
@font-face{font-family:'Cormorant Garamond';font-style:normal;font-weight:500 700;font-display:swap;src:url(<?php echo esc_url( plugins_url( 'assets/fonts', __FILE__ ) ); ?>/cormorant.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD}
@font-face{font-family:'Jost';font-style:normal;font-weight:300 600;font-display:swap;src:url(<?php echo esc_url( plugins_url( 'assets/fonts', __FILE__ ) ); ?>/jost.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD}.foot-addr{font-style:normal}
</style>
<?php if ( $is_page && ! empty( $pg['hero'] ) && preg_match( '#src="([^"]+)"(?:[^>]*srcset="([^"]+)")?#', mecca_home_img( $pg['hero'], '', array( 'sizes' => '(max-width: 900px) calc(100vw - 32px), 45vw' ) ), $lp ) ) : ?><link rel="preload" as="image" href="<?php echo esc_url( $lp[1] ); ?>"<?php if ( ! empty( $lp[2] ) ) : ?> imagesrcset="<?php echo esc_attr( $lp[2] ); ?>" imagesizes="(max-width: 900px) calc(100vw - 32px), 45vw"<?php endif; ?> fetchpriority="high"><?php endif; ?>
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo esc_url( add_query_arg( 'v', (string) filemtime( __DIR__ . '/assets/mecca-site.css' ), $a( 'mecca-site.css' ) ) ); ?>">
<?php if ( $is_home ) : ?><script type="application/ld+json"><?php
echo wp_json_encode( array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array_map( function ( $f ) {
		return array( '@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ) );
	}, $faqs ),
) );
?></script><?php endif; ?>
</head>
<body <?php body_class( $is_home ? 'mh' : ( $is_quote ? 'mh mh-page mh-quotepage' : 'mh mh-page mh-inner' ) ); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main">Skip to content</a>
<div id="progress" aria-hidden="true"></div>
<a href="#quote" id="stickyQuote">Get a Quote</a><a href="sms:+18438041188" id="stickyText" aria-label="Text us at (843) 804-1188"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2Z"/></svg>Text us</a><a href="https://wa.me/18438041188" id="stickyWa" target="_blank" rel="noopener" aria-label="WhatsApp us at (843) 804-1188"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.2-.2-.5-.3Z"/></svg>WhatsApp</a>

<nav class="mh-nav" id="nav" aria-label="Main">
	<div class="nav-links nav-left">
		<a href="sms:+18438041188" class="nav-sms"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2Zm3 6.5a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Z"/></svg>Text us</a>
		<a href="https://wa.me/18438041188" class="nav-sms nav-wa" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.2-.2-.5-.3Z"/></svg>WhatsApp</a>
		<a href="<?php echo $h; ?>#fleet">Fleet</a>
		<a href="<?php echo $h; ?>#services">Services</a>
		<a href="#reviews">Reviews</a>
		<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
	</div>
	<div class="nav-actions"><a href="<?php echo esc_attr( $tel ); ?>" class="nav-tel" aria-label="Call <?php echo esc_attr( $phone ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2Z"/></svg></a><a href="sms:+18438041188" class="nav-tel" aria-label="Text <?php echo esc_attr( $phone ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2Zm3 6.5a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Z"/></svg></a><a href="https://wa.me/18438041188" class="nav-tel" target="_blank" rel="noopener" aria-label="WhatsApp (843) 804-1188"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.2-.2-.5-.3Z"/></svg></a></div>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand"><img src="<?php echo esc_url( $a( 'logo-180.webp' ) ); ?>" srcset="<?php echo esc_url( $a( 'logo-180.webp' ) ); ?> 180w, <?php echo esc_url( $a( 'logo-270.webp' ) ); ?> 270w, <?php echo esc_url( $a( 'logo-360.webp' ) ); ?> 360w" sizes="(max-width: 900px) 135px, 236px" width="180" height="86" alt="Mecca Limo Chauffeur Service"></a>
	<div class="nav-links nav-right">
		<a href="<?php echo esc_attr( $tel ); ?>" class="nav-call"><?php echo esc_html( $phone ); ?></a>
	</div>
	<button class="burger" id="burger" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu"><span></span><span></span><span></span></button>
</nav>
<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
	<a href="<?php echo $h; ?>#fleet">Fleet</a>
	<a href="<?php echo $h; ?>#services">Services</a>
	<a href="<?php echo $h; ?>#how">How it works</a>
	<a href="#reviews">Reviews</a>
	<a href="#faq">FAQ</a>
	<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
	<a href="#quote" class="mm-cta">Get a quote</a>
	<a href="<?php echo esc_attr( $tel ); ?>" class="mm-call">Call <?php echo esc_html( $phone ); ?> · 24/7</a>
	<a href="https://wa.me/18438041188" class="mm-call" target="_blank" rel="noopener">WhatsApp us</a>
</div>

<?php if ( $is_home ) : ?>
<header class="hero hero-v" id="top">
	<div class="stage" aria-hidden="true">
		<img class="stage-img" src="<?php echo esc_url( $a( 'open3.webp' ) ); ?>" srcset="<?php echo esc_url( $a( 'open3-480.webp' ) ); ?> 480w, <?php echo esc_url( $a( 'open3-s.webp' ) ); ?> 720w, <?php echo esc_url( $a( 'open3-828.webp' ) ); ?> 828w, <?php echo esc_url( $a( 'open3-m.webp' ) ); ?> 1080w, <?php echo esc_url( $a( 'open3-1440.webp' ) ); ?> 1440w, <?php echo esc_url( $a( 'open3.webp' ) ); ?> 1920w" sizes="100vw" width="1920" height="1080" alt="Mecca Limo black Mercedes sedan, Sprinter and Cadillac Escalade at sunset in Charleston, SC" fetchpriority="high">
		<video id="heroVideo" muted playsinline autoplay preload="auto" data-d="<?php echo esc_url( $a( 'open3-1080.mp4' ) ); ?>" data-m="<?php echo esc_url( $a( 'open3-540.mp4' ) ); ?>" data-w="<?php echo esc_url( $a( 'open3-540.webm' ) ); ?>"></video><script>(function(v){var c=navigator.connection;if(!v||matchMedia('(prefers-reduced-motion: reduce)').matches||(c&&(c.saveData||/2g|3g/.test(c.effectiveType||''))))return;function start(){var cut=setTimeout(function(){if(!v.classList.contains('on')){v.dataset.off='1';v.pause();v.removeAttribute('src');v.load();}},2500);v.addEventListener('playing',function(){if(v.dataset.off)return;clearTimeout(cut);v.classList.add('on');});v.addEventListener('ended',function(){var st=v.closest('.stage');if(st)st.classList.add('hold');});v.src=v.canPlayType('video/mp4; codecs="avc1.42E01E"')?(innerWidth<900?v.dataset.m:v.dataset.d):v.dataset.w;var p=v.play();if(p&&p.catch)p.catch(function(){});}var go=false,ready=false,im=v.parentNode.querySelector('.stage-img');function tryStart(){if(go&&ready){go=ready=false;start();}}function act(){go=true;tryStart();}['mousemove','touchstart','pointerdown','wheel','keydown'].forEach(function(x){addEventListener(x,act,{once:true,passive:true});});function loaded(){ready=true;tryStart();}if(im&&!im.complete){im.addEventListener('load',loaded,{once:true});im.addEventListener('error',loaded,{once:true});}else{loaded();}})(document.getElementById('heroVideo'));</script>
	</div>
	<div class="hero-aurora" aria-hidden="true"></div>
	<div class="wrap hero-inner">
		<span class="eyebrow hero-eyebrow">Charleston, SC · Open 24/7</span>
		<h1>Charleston limo service with comfort, safety &amp; <em>luxury</em>.</h1>
		<p class="lede">Family-owned chauffeur service for airport transfers, weddings, corporate travel and nights out across Charleston and the Lowcountry.</p>
		<div>
			<div class="hero-quote" id="heroQuote" role="form" aria-label="Start your quote">
				<div class="hq-field"><label for="hqPickup">Pick up</label><input id="hqPickup" type="text" placeholder="Address, hotel or airport" autocomplete="off"></div>
				<div class="hq-field"><label for="hqDrop">Drop off</label><input id="hqDrop" type="text" placeholder="Destination" autocomplete="off"></div>
				<div class="hq-field"><label for="hqDate">Date</label><input id="hqDate" type="date" min="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>"></div>
				<div class="hq-field"><label for="hqPax">Passengers</label><input id="hqPax" type="number" min="1" max="99" inputmode="numeric" placeholder="e.g. 4"></div>
				<button class="hq-btn" type="button">Get a Quote</button>
			</div>
			<div class="hero-mcta"><a href="<?php echo esc_attr( $tel ); ?>" class="btn btn-ghost">Call <?php echo esc_html( $phone ); ?></a></div>
		</div>
	</div>
</header>
<?php endif; ?>

<main id="main">
<?php if ( ! $is_page ) : ?>
<section id="quote" class="quote-white">
	<div class="wrap">
		<?php if ( $is_quote ) : ?><h1 class="quote-title">Get a Quote</h1><?php else : ?><h2 class="quote-title">Get a Quote</h2><?php endif; ?>
		<p class="quote-sub">Tell us about your trip and we'll get back to you with a quote and availability.</p>
		<?php echo do_shortcode( '[mecca_quote_form heading="0"]' ); ?>
	</div>
</section>
<?php endif; ?>
<?php if ( $is_page ) : ?>
<header class="pg-hero">
	<div class="wrap pg-hero-grid<?php echo $pg['hero'] ? '' : ' no-img'; ?>">
	<div class="pg-hero-inner">
		<span class="eyebrow">Mecca Limo · Charleston, SC</span>
		<h1><?php echo esc_html( $pg['h1'] ); ?></h1>
		<div class="pg-hero-cta"><a href="#quote" class="btn btn-gold">Get a Quote</a><a href="<?php echo esc_attr( $tel ); ?>" class="btn btn-ghost">Call <?php echo esc_html( $phone ); ?></a></div>
	</div>
	<?php if ( $pg['hero'] ) : ?><div class="pg-hero-photo"><?php echo mecca_home_img( $pg['hero'], $pg['h1'], array( 'fetchpriority' => 'high', 'sizes' => '(max-width: 900px) calc(100vw - 32px), 45vw' ) ); ?></div><?php endif; ?>
	</div>
</header>
<section class="pg-body">
	<div class="wrap pg-wrap"><?php echo $pg['html']; // Built from the page's own content. ?></div>
</section>
<?php $pg_faqs = mecca_seo_faqs( get_post()->post_name ); if ( $pg_faqs ) : ?>
<section class="alt" id="faq">
	<div class="wrap">
		<div class="sec-head center"><span class="eyebrow">Quick answers</span><h2>Common questions</h2></div>
		<div class="faq-list">
			<?php foreach ( $pg_faqs as $i => $f ) : ?>
			<details class="faq-item"<?php echo 0 === $i ? ' open' : ''; ?>><summary><h3><?php echo esc_html( $f[0] ); ?></h3></summary><p><?php echo esc_html( $f[1] ); ?></p></details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
<section class="alt pg-more">
	<div class="wrap">
		<div class="sec-head"><span class="eyebrow">Our Services</span><h2>More ways to ride with Mecca Limo.</h2></div>
		<div class="svc-marquee" aria-label="Our services">
		<div class="svc-track">
			<?php foreach ( $services as $i => $s ) : ?>
			<a class="svc" href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $up_url . $svc_imgs[ $s[1] ] . '-t480.webp' ); ?>" width="480" height="330" alt="<?php echo esc_attr( $s[0] . ' limo service in Charleston, SC' ); ?>"><span class="svc-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><?php echo isset( $svc_icons[ $s[1] ] ) ? $svc_icons[ $s[1] ] : ''; ?></svg></span><h3><?php echo esc_html( $s[0] ); ?></h3><span class="go">Book now →</span></a>
			<?php endforeach; ?>
		</div>
		</div>
	</div>
</section>
<?php else : ?>
<div class="strip">
	<div class="marquee"><div class="marquee-track"><div class="stat"><b>5.0 ★</b><span>Google rating</span></div><i class="stat-sep">◆</i><div class="stat"><b>24/7</b><span>Always open</span></div><i class="stat-sep">◆</i><div class="stat"><b>Family</b><span>Owned &amp; operated</span></div><i class="stat-sep">◆</i><div class="stat"><b>Licensed</b><span>&amp; fully insured</span></div><i class="stat-sep">◆</i><div class="stat"><b>Pro</b><span>Chauffeurs</span></div><i class="stat-sep">◆</i><div class="stat"><b>CHS</b><span>Airport transfers</span></div><i class="stat-sep">◆</i></div></div>
</div>
<?php endif; ?>

<?php if ( $is_home ) : ?>
<section id="fleet">
	<div class="wrap">
		<div class="sec-head">
			<span class="eyebrow">Our Fleet</span>
			<h2>Black car and limo service in Charleston, SC.</h2>
			<p>Late-model, professionally maintained and detailed before every pickup. Choose the vehicle that fits your group and your occasion. <a class="inline-link" href="<?php echo esc_url( home_url( '/charleston-limo-fleet/' ) ); ?>">See the full fleet →</a></p>
		</div>
		<span class="fleet-swipe-hint">Swipe to see the fleet &rarr;</span><div class="fleet-wrap"><button type="button" class="fleet-nav prev" aria-label="Previous vehicle" onclick="var r=this.parentNode.querySelector('.fleet-pop');r.scrollBy({left:-r.clientWidth*.45,behavior:'smooth'})">&lsaquo;</button><button type="button" class="fleet-nav next" aria-label="Next vehicle" onclick="var r=this.parentNode.querySelector('.fleet-pop');r.scrollBy({left:r.clientWidth*.45,behavior:'smooth'})">&rsaquo;</button><div class="grid3 fleet-pop">
			<div class="car">
				<div class="car-img"><span class="car-tag">Sprinter</span><img class="car-bg" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sprinter-wide.webp' ) ); ?>" alt="Mercedes Sprinter limo van background" aria-hidden="true" width="800" height="450"><img class="car-main" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sprinter-wide.webp' ) ); ?>" width="800" height="450" alt="Black Mercedes-Benz Sprinter limo van by Mecca Limo in Charleston"></div>
				<div class="car-body"><h3>Mercedes Sprinter</h3><h4 class="seats">Seats up to 14 with luggage</h4><p>The choice for wedding parties, corporate groups and bachelorette parties.</p></div>
			</div>
			<div class="car">
				<div class="car-img"><span class="car-tag">SUV</span><img class="car-bg" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-suv.webp' ) ); ?>" alt="Cadillac Escalade SUV limo background" aria-hidden="true" width="800" height="450"><img class="car-main" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-suv.webp' ) ); ?>" width="800" height="446" alt="Black Cadillac Escalade SUV limo in Charleston SC"></div>
				<div class="car-body"><h3>Luxury SUV</h3><h4 class="seats">Seats up to 5 with luggage</h4><p>Cadillac Escalade, Chevrolet Suburban and GMC Yukon Denali. Room for the group and every bag.</p></div>
			</div>
			<div class="car">
				<div class="car-img"><span class="car-tag">Sedan</span><img class="car-bg" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sedan.webp' ) ); ?>" alt="Executive sedan background" aria-hidden="true" width="800" height="450"><img class="car-main" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sedan.webp' ) ); ?>" width="800" height="437" alt="Black executive sedan for airport and business travel in Charleston"></div>
				<div class="car-body"><h3>Executive Sedan</h3><h4 class="seats">Seats up to 2 with luggage</h4><p>Quiet and private for airport transfers and business travel. Always on time.</p></div>
			</div>
		</div></div>
	</div>
</section>

<section class="alt" id="services">
	<div class="wrap">
		<div class="sec-head">
			<span class="eyebrow">What We Do</span>
			<h2>Limo service for every occasion.</h2>
		</div>
		<div class="svc-marquee" aria-label="Our services">
		<div class="svc-track">
			<?php foreach ( $services as $i => $s ) : ?>
			<a class="svc" href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $up_url . $svc_imgs[ $s[1] ] . '-t480.webp' ); ?>" width="480" height="330" alt="<?php echo esc_attr( $s[0] . ' limo service in Charleston, SC' ); ?>"><span class="svc-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><?php echo isset( $svc_icons[ $s[1] ] ) ? $svc_icons[ $s[1] ] : ''; ?></svg></span><h3><?php echo esc_html( $s[0] ); ?></h3><span class="go">Book now →</span></a>
			<?php endforeach; ?>
		</div>
		</div>
	</div>
</section>

<section id="how">
	<div class="wrap">
		<div class="sec-head">
			<span class="eyebrow">How it works</span>
			<h2>Booked in three easy steps.</h2>
		</div>
		<div class="tl">
			<div class="tl-s"><div class="tl-m"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v12H5.2L4 17.2z"/><path d="M8 9h8M8 12h5"/></svg><i>1</i></div><h3>Request a quote</h3><p>Pickup, destination, date and group size. It takes under a minute.</p></div>
			<div class="tl-s"><div class="tl-m"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg><i>2</i></div><h3>We confirm your booking</h3><p>Your quote, the right vehicle and your professional chauffeur, fast.</p></div>
			<div class="tl-s"><div class="tl-m"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 17h14l-1.5-5h-11z"/><circle cx="8" cy="17" r="1.6"/><circle cx="16" cy="17" r="1.6"/><path d="M7 12l1.5-4h7l1.5 4"/></svg><i>3</i></div><h3>Arrive in style</h3><p>Early pickup, flight tracking and a safe, on-time arrival.</p></div>
		</div>
		<div class="tl-cta"><a class="btn btn-gold" href="#quote">Get a free quote</a></div>
	</div>
</section>

<section class="alt story" id="story">
	<div class="wrap story-grid">
		<div class="story-copy">
			<span class="eyebrow">Our Story</span>
			<h2>Mecca Limo: a family-owned business built on trust.</h2>
			<p><span class="initial">M</span>ecca Limo was created with a simple mission: to give Charleston a more convenient, reliable and luxurious way to get around. As a family-owned business, our clients are at the center of everything we do.</p>
			<p>From 4 a.m. airport transfers to weddings and corporate events across the Lowcountry, we show up early, drive clean vehicles, and get you there safely. That is the whole promise.</p>
			<div class="sig">Moe &amp; the Mecca Limo family</div>
			<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Learn more about Mecca Limo</a>
		</div>
		<div class="story-photo"><img loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'story-sprinter2.webp' ) ); ?>" width="1000" height="943" alt="Black Mercedes-Benz Sprinter limo van by Mecca Limo at sunset"></div>
	</div>
</section>
<?php endif; ?>

<?php if ( ! $is_page ) : ?>
<section id="reviews">
	<div class="wrap">
		<div class="sec-head center">
			<h2>What our clients say</h2>
		</div>
		<div class="rev-badge"><span class="g-stars">★★★★★</span><span><b>5.0</b> from <b>140+</b> Google reviews</span></div>
		<div class="rev-marquee" aria-label="Client reviews">
			<div class="rev-track">
			<?php foreach ( $reviews as $r ) :
				$ini = implode( '', array_map( function ( $w ) { return mb_substr( $w, 0, 1 ); }, explode( ' ', $r[0] ) ) );
				?>
			<div class="rev-card">
				<div class="rev-stars" aria-label="5 out of 5 stars">★★★★★</div>
				<p class="rev-text">“<?php echo esc_html( $r[2] ); ?>”</p>
				<div class="rev-person"><div class="rev-avatar" aria-hidden="true"><?php echo esc_html( $ini ); ?></div><div><div class="rev-name"><?php echo esc_html( $r[0] ); ?></div><div class="rev-tag"><?php echo esc_html( $r[1] ); ?></div></div></div>
			</div>
			<?php endforeach; ?>
			</div>
		</div>
		<div class="rev-cta">
			<a href="https://www.google.com/maps/place/?q=place_id:ChIJBZcVgzl5_ogRVn5LmaHjB3s" target="_blank" rel="noopener" class="btn btn-gold">Read all reviews on Google</a>
			<a href="https://g.page/r/CVZ-S5mh4wd7EBM/review" target="_blank" rel="noopener" class="btn btn-ghost">Leave a review</a>
			<a href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>" class="rev-more">More client stories →</a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $is_page ) : ?>
<section id="quote" class="quote-white">
	<div class="wrap">
		<?php if ( $is_quote ) : ?><h1 class="quote-title">Get a Quote</h1><?php else : ?><h2 class="quote-title">Get a Quote</h2><?php endif; ?>
		<p class="quote-sub">Tell us about your trip and we'll get back to you with a quote and availability.</p>
		<?php echo do_shortcode( '[mecca_quote_form heading="0"]' ); ?>
	</div>
</section>
<?php endif; ?>

<?php if ( ! $is_page ) : ?>
<section class="alt" id="faq">
	<div class="wrap">
		<div class="sec-head center">
			<span class="eyebrow">Good to know</span>
			<h2>Frequently asked questions.</h2>
		</div>
		<div class="faq-list">
			<?php foreach ( $faqs as $i => $f ) : ?>
			<details class="faq-item"<?php echo 0 === $i ? ' open' : ''; ?>><summary><h3><?php echo esc_html( $f[0] ); ?></h3></summary><p><?php echo esc_html( $f[1] ); ?></p></details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
</main>

<footer class="mh-foot">
	<div class="wrap">
		<div class="foot-grid">
			<div class="foot-brand">
				<img src="<?php echo esc_url( $a( 'logo-180.webp' ) ); ?>" srcset="<?php echo esc_url( $a( 'logo-180.webp' ) ); ?> 180w, <?php echo esc_url( $a( 'logo-270.webp' ) ); ?> 270w, <?php echo esc_url( $a( 'logo-360.webp' ) ); ?> 360w" sizes="148px" width="180" height="86" alt="Mecca Limo" loading="lazy">
				<p>A family-owned chauffeur service with our clients at the center of it all. Luxury vehicles and professional drivers, 24/7.</p>
				<div class="foot-social">
					<a href="https://www.facebook.com/profile.php?id=100090948969233" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H17V3.6c-.29-.04-1.28-.12-2.43-.12-2.4 0-4.07 1.47-4.07 4.17V9.9H7.8V13h2.7v8h3Z"/></svg></a>
					<a href="https://www.instagram.com/meccalimo/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s0 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.65.07-4.9.07s-3.63 0-4.9-.07c-3.26-.15-4.77-1.7-4.92-4.92C2.2 15.6 2.2 15.2 2.2 12s0-3.58.07-4.85C2.42 3.92 3.93 2.38 7.2 2.27 8.4 2.2 8.8 2.2 12 2.2Zm0 4.86A4.94 4.94 0 1 0 12 17a4.94 4.94 0 0 0 0-9.94Zm0 8.14a3.2 3.2 0 1 1 0-6.4 3.2 3.2 0 0 1 0 6.4Zm5.14-9.4a1.15 1.15 0 1 0 0 2.3 1.15 1.15 0 0 0 0-2.3Z"/></svg></a>
					<a href="https://x.com/meccalimo" target="_blank" rel="noopener" aria-label="X"><svg viewBox="0 0 24 24"><path d="M17.8 3h3.1l-6.8 7.7L22 21h-6.2l-4.9-6.3L5.3 21H2.2l7.2-8.3L1.8 3h6.4l4.4 5.8L17.8 3Zm-1.1 16.2h1.7L7.4 4.7H5.6l11.1 14.5Z"/></svg></a>
					<a href="https://www.tiktok.com/@meccalimo" target="_blank" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24"><path d="M16.5 3c.3 2.03 1.44 3.24 3.5 3.5v2.5c-1.2.12-2.25-.27-3.48-1.01v4.66c0 5.92-6.45 7.77-9.04 3.52-1.67-2.74-.64-7.55 4.73-7.74v2.63c-.41.07-.85.17-1.25.31-1.2.4-1.88 1.16-1.69 2.5.36 2.57 5.08 3.33 4.68-1.7V3h2.55Z"/></svg></a>
					<a href="https://www.linkedin.com/company/87738135/" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24"><path d="M6.94 8.5H3.56V20h3.38V8.5zM5.25 3.5a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92zM20.44 13.1c0-3.1-1.65-4.85-4.2-4.85-1.9 0-2.75 1.05-3.22 1.78V8.5H9.65c.04 1 0 11.5 0 11.5h3.37v-6.42c0-.34.03-.68.13-.92.27-.68.9-1.38 1.94-1.38 1.37 0 1.92 1.04 1.92 2.57V20h3.38l.05-6.9z" fill="currentColor"/></svg></a>
					<a href="https://www.youtube.com/channel/UClskMeHN_YUk3bRdJgpF95A" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.76-1.77C18.27 5 12 5 12 5s-6.27 0-7.84.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.76 1.77C5.73 19 12 19 12 19s6.27 0 7.84-.43a2.5 2.5 0 0 0 1.76-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3L10 15z" fill="currentColor"/></svg></a>
				</div>
			</div>
			<div class="foot-col"><h2 class="foot-h">Services</h2>
				<?php foreach ( array_slice( $services, 0, 6 ) as $s ) : ?><a href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><?php echo esc_html( $s[0] ); ?></a><?php endforeach; ?>
			</div>
			<div class="foot-col"><h2 class="foot-h">Quick links</h2>
				<?php foreach ( array_slice( $services, 6 ) as $s ) : ?><a href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><?php echo esc_html( $s[0] ); ?></a><?php endforeach; ?>
				<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>">All services</a>
				<a href="<?php echo esc_url( home_url( '/charleston-limo-fleet/' ) ); ?>">Our fleet</a>
				<a href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>">Reviews</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About us</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
				<a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>">Booking policy</a>
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy policy</a>
			</div>
			<div class="foot-col"><h2 class="foot-h">Contact</h2>
				<h3 class="foot-sub">Phone &amp; text 24/7</h3>
				<a href="<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $phone ); ?></a>
				<a href="https://wa.me/18438041188" target="_blank" rel="noopener">WhatsApp us</a>
				<a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
				<h4 class="foot-sub">Office</h4>
				<address class="foot-addr">4380 Dorchester Rd<br>North Charleston, SC 29405</address>
				<h5 class="foot-sub">Hours</h5>
				<span>Open 24 hours, 7 days a week</span>
				<h6 class="foot-sub">Directions &amp; quotes</h6>
				<a href="https://www.google.com/maps/dir/?api=1&amp;destination=Mecca+Limo+Charleston&amp;destination_place_id=ChIJBZcVgzl5_ogRVn5LmaHjB3s" target="_blank" rel="noopener">Get directions →</a>
				<a href="<?php echo esc_url( home_url( '/get-a-quote/' ) ); ?>">Get a quote</a>
			</div>
		</div>
		<div class="foot-bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Mecca Limo Chauffeur Service. All rights reserved.</span><span>Serving Charleston, Mount Pleasant, Kiawah, Folly Beach &amp; the Lowcountry</span></div>
	</div>
</footer>

<script>
(function(){
	var nav=document.getElementById('nav'),bar=document.getElementById('progress'),sticky=document.getElementById('stickyQuote'),stx=document.getElementById('stickyText'),swa=document.getElementById('stickyWa');
	var docH=0,tick=false;function measure(){docH=document.documentElement.scrollHeight-innerHeight;}
	function onScroll(){tick=false;var y=window.scrollY;nav.classList.toggle('scrolled',y>40);bar.style.transform='scaleX('+(docH>0?Math.min(y/docH,1):0)+')';sticky.classList.toggle('show',y>innerHeight*.7);stx.classList.toggle('show',y>innerHeight*.7);if(swa)swa.classList.toggle('show',y>innerHeight*.7);}
	function req(){if(!tick){tick=true;requestAnimationFrame(onScroll);}}
	addEventListener('scroll',req,{passive:true});addEventListener('resize',function(){measure();req();},{passive:true});addEventListener('load',function(){measure();req();});measure();onScroll();


	(function(){
		var v=document.getElementById('heroVideo'),st=document.querySelector('.stage');
		if(!v||matchMedia('(prefers-reduced-motion: reduce)').matches)return;
		if(v.paused&&!v.ended&&!v.dataset.off&&v.readyState>2){var p=v.play();if(p&&p.catch)p.catch(function(){});}
	})();
	var hq=document.getElementById('heroQuote');function hqGo(){
		var map={hqPickup:'mqf-pickup',hqDrop:'mqf-dropoff',hqDate:'mqf-date',hqPax:'mqf-pax'};
		for(var k in map){var s=document.getElementById(k),t=document.getElementById(map[k]);if(s&&t&&s.value)t.value=s.value;}
		document.getElementById('quote').scrollIntoView({behavior:'smooth'});
		setTimeout(function(){var el=document.getElementById('mqf-time');if(el)el.focus({preventScroll:true});},700);
	}
	if(hq){hq.querySelector('.hq-btn').addEventListener('click',hqGo);hq.addEventListener('keydown',function(e){if(e.key==='Enter'&&e.target.tagName==='INPUT'){e.preventDefault();hqGo();}});}

	if('IntersectionObserver' in window){
		var io=new IntersectionObserver(function(es){es.forEach(function(en){if(en.isIntersecting){en.target.classList.add('is-in');io.unobserve(en.target);}});},{threshold:.12,rootMargin:'0px 0px -8% 0px'});
		['.sec-head','.rev-badge','.story-copy','.story-photo','.grid3','.svc-marquee','.tl','.faq-list','.quote-title','.rev-cta'].forEach(function(sel){
			document.querySelectorAll(sel).forEach(function(c){
				var kids=c.matches('.grid3,.tl')?[].slice.call(c.children):[c];
				kids.forEach(function(el,i){el.classList.add('will-reveal');el.style.transitionDelay=(c.classList.contains('fleet-pop')?i*180:i%5*90)+'ms';io.observe(el);});
			});
		});
	}

	(function(){
		function dupe(t){if(t.getAttribute('data-d'))return;t.setAttribute('data-d','1');[].slice.call(t.children).forEach(function(c){var k=c.cloneNode(true);k.setAttribute('aria-hidden','true');k.setAttribute('inert','');if(k.tagName==='A')k.tabIndex=-1;[].slice.call(k.querySelectorAll('h3')).forEach(function(h){var d=document.createElement('div');d.className=(h.className?h.className+' ':'')+'h3';d.innerHTML=h.innerHTML;h.parentNode.replaceChild(d,h);});t.appendChild(k);});t.classList.add('dup');}
		function dupeStrip(m){if(m.getAttribute('data-d'))return;m.setAttribute('data-d','1');var t=m.querySelector('.marquee-track');if(!t)return;var k=t.cloneNode(true);k.setAttribute('aria-hidden','true');k.setAttribute('inert','');m.appendChild(k);m.classList.add('dup');}
		var ts=[].slice.call(document.querySelectorAll('.rev-track,.svc-track')),armed=false,pend=[];
		function arm(){if(armed)return;armed=true;pend.forEach(dupe);pend=[];[].slice.call(document.querySelectorAll('.marquee')).forEach(dupeStrip);}
		['wheel','touchstart','pointerdown','keydown','mousemove'].forEach(function(x){addEventListener(x,arm,{once:true,passive:true});});
		function want(t){if(armed)dupe(t);else pend.push(t);}
		if('IntersectionObserver' in window){var dio=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){want(e.target);dio.unobserve(e.target);}});},{rootMargin:'200px'});ts.forEach(function(t){dio.observe(t);});}else{ts.forEach(want);}
	})();


	// Sliding strips: keep auto-scrolling, but visitors can also drag/swipe them both ways; touching pauses them.
	(function(){
		var reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
		[].slice.call(document.querySelectorAll('.svc-marquee,.rev-marquee,.marquee')).forEach(function(box){
			var strip=box.classList.contains('marquee'),x=0,last=0,vis=true,hold=0,drag=null,moved=0;
			var speed=strip?(innerWidth<700?55:45):(box.classList.contains('rev-marquee')?(innerWidth<700?45:40):(innerWidth<700?42:38));
			function tracks(){return strip?[].slice.call(box.querySelectorAll('.marquee-track')):[box.querySelector('.svc-track,.rev-track')];}
			function ready(){return strip?box.classList.contains('dup'):(tracks()[0]&&tracks()[0].classList.contains('dup'));}
			function span(){var t=tracks()[0];if(!t)return 0;return strip?t.getBoundingClientRect().width:t.scrollWidth/2;}
			function paint(){var w=span();if(w>0){while(x<=-w)x+=w;while(x>0)x-=w;}tracks().forEach(function(t){t.classList.add('mq-drive');t.style.transform='translate3d('+x+'px,0,0)';});}
			function frame(now){var dt=last?Math.min((now-last)/1000,.05):0;last=now;
				if(vis&&ready()&&!drag&&!reduce&&now>hold){x-=speed*dt;paint();}
				requestAnimationFrame(frame);}
			if('IntersectionObserver' in window)new IntersectionObserver(function(es){vis=es[0].isIntersecting;}).observe(box);
			box.addEventListener('pointerdown',function(e){if(e.pointerType==='mouse'&&e.button!==0)return;drag={sx:e.clientX,sy:e.clientY,x0:x,lock:null,id:e.pointerId};moved=0;hold=Infinity;});
			box.addEventListener('pointermove',function(e){if(!drag||e.pointerId!==drag.id)return;var dx=e.clientX-drag.sx,dy=e.clientY-drag.sy;
				if(drag.lock===null&&(Math.abs(dx)>6||Math.abs(dy)>6)){drag.lock=Math.abs(dx)>Math.abs(dy);if(drag.lock){box.classList.add('mq-dragging');try{box.setPointerCapture(e.pointerId);}catch(_){}}}
				if(drag.lock){moved=Math.abs(dx);x=drag.x0+dx;paint();}});
			function end(){if(!drag)return;drag=null;box.classList.remove('mq-dragging');hold=performance.now()+2500;}
			box.addEventListener('pointerup',end);box.addEventListener('pointercancel',end);box.addEventListener('lostpointercapture',end);
			box.addEventListener('click',function(e){if(moved>6){e.preventDefault();e.stopPropagation();moved=0;}},true);
			box.addEventListener('mouseenter',function(){hold=Infinity;});box.addEventListener('mouseleave',function(){if(!drag)hold=performance.now()+800;});
			box.addEventListener('wheel',function(e){if(Math.abs(e.deltaX)>Math.abs(e.deltaY)){e.preventDefault();x-=e.deltaX;paint();hold=performance.now()+2500;}},{passive:false});
			requestAnimationFrame(frame);
		});
	})();

	if('IntersectionObserver' in window){
		var po=new IntersectionObserver(function(es){es.forEach(function(en){en.target.classList.toggle('anim-off',!en.isIntersecting);});});
		document.querySelectorAll('.strip,.marquee,.rev-marquee,.svc-marquee,.story-photo,.pg-hero-photo,.hero-quote,.stats').forEach(function(el){po.observe(el);});
	}

	var burger=document.getElementById('burger'),mm=document.getElementById('mobileMenu');
	function setMenu(o){mm.classList.toggle('open',o);burger.classList.toggle('is-open',o);burger.setAttribute('aria-expanded',o);mm.setAttribute('aria-hidden',!o);document.body.classList.toggle('menu-open',o);burger.setAttribute('aria-label',o?'Close menu':'Open menu');}
	burger.addEventListener('click',function(){setMenu(!mm.classList.contains('open'));});
	mm.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){setMenu(false);});});
	addEventListener('keydown',function(e){if(e.key==='Escape')setMenu(false);});
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
