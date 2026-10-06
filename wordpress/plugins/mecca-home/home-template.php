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
	array( 'Night Out & Party Bus', '/night-out/', 'Birthdays, bachelorette weekends, concerts. Everyone gets home safely.' ),
	array( 'Corporate', '/corporate/', 'Discreet chauffeurs and clean billing for executives and clients.' ),
	array( 'Events & Prom', '/events/', 'Proms, galas and celebrations, arriving together in style.' ),
	array( 'Sightseeing & Tours', '/attractions/', 'Historic landmarks, plantations, museums and custom Charleston tours.' ),
	array( 'Beach Transportation', '/beach/', 'Folly Beach, Sullivan\'s Island, Kiawah and more.' ),
	array( 'Golf Courses', '/golf-courses/', 'Kiawah, Wild Dunes and the Lowcountry\'s best courses.' ),
	array( 'Hotel Transfers', '/hotels/', 'Door-to-door service to and from Charleston hotels and rentals.' ),
	array( 'Cruise Port', '/cruise-trips/', 'Transfers to and from the Charleston cruise terminal.' ),
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
	array( 'How far in advance should I book?', 'As early as you can for weddings and peak weekends. We also handle last-minute and same-day requests whenever a vehicle is free. Just phone ' . $phone . ', 24/7.' ),
	array( 'What areas do you serve?', 'Charleston and the wider Lowcountry, including Mount Pleasant, North Charleston, Kiawah Island, Seabrook Island, Isle of Palms, Folly Beach, Sullivan\'s Island, Summerville and Georgetown.' ),
	array( 'Which vehicles are in your fleet?', 'A Mercedes-Benz Sprinter for larger groups, luxury SUVs such as the Cadillac Escalade and Chevrolet Suburban, and an executive sedan for airport transfers and business travel. Tell us your group size and we will match the right vehicle.' ),
	array( 'Do you handle airport transfers?', 'Yes. We pick up and drop off at Charleston International Airport (CHS) and the area\'s private aviation terminals. Share your flight number and your chauffeur will track your arrival.' ),
	array( 'Do you operate 24/7?', 'Yes. Mecca Limo runs around the clock, every day, including early-morning airport transfers and late nights out.' ),
	array( 'How do I get a price?', 'Fill out the quote form on this page or phone ' . $phone . '. We will send a price for your date and route, with no obligation.' ),
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
<style>
:root{--ink:#0a0a0b;--ink-soft:#141416;--panel:#1a1a1d;--gold:#e3b84f;--gold-bright:#f8da78;--gold-deep:#a8711f;--gold-grad:linear-gradient(135deg,#a8711f 0%,#e3b84f 28%,#fff0a8 50%,#e3b84f 72%,#a8711f 100%);--line:rgba(227,184,79,.24);--paper:#f3efe6;--muted:#c2bcae}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body.mh{background:var(--ink);color:var(--paper);font-family:'Jost',sans-serif;font-weight:300;line-height:1.6;-webkit-font-smoothing:antialiased;overflow-x:hidden;font-size:16px}
.mh h1,.mh h2,.mh h3{font-family:'Cormorant Garamond',serif;font-weight:500;line-height:1.08;letter-spacing:.01em;padding:0}
.mh a{color:inherit;text-decoration:none}
.mh img{max-width:100%;display:block}
.mh a:focus-visible,.mh button:focus-visible,.mh input:focus-visible,.mh select:focus-visible,.mh textarea:focus-visible,.mh summary:focus-visible{outline:2px solid var(--gold-bright);outline-offset:3px;border-radius:3px}
.wrap{max-width:1180px;margin:0 auto;padding:0 32px}
.eyebrow{display:inline-flex;align-items:center;gap:14px;margin-bottom:22px;font-size:.72rem;letter-spacing:.32em;text-transform:uppercase;color:var(--gold)}
.eyebrow::before{content:"";width:42px;height:1px;background:var(--gold);transition:width .5s ease}
.btn{display:inline-block;padding:16px 34px;border-radius:2px;font-size:.8rem;letter-spacing:.16em;text-transform:uppercase;cursor:pointer;transition:all .3s;border:1px solid transparent}
.btn-gold{position:relative;overflow:hidden;background:var(--gold-grad);color:var(--ink)!important;font-weight:500}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(227,184,79,.3)}
.btn-ghost{border-color:rgba(243,239,230,.3);color:var(--paper)}
.btn-ghost:hover{border-color:var(--gold);color:var(--gold-bright)}
.btn-gold::before,.hq-btn::before{content:"";position:absolute;top:0;left:-120%;width:60%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.55),transparent);transform:skewX(-18deg)}
.btn-gold:hover::before,.hq-btn:hover::before{animation:sheen .9s ease}
@keyframes sheen{from{left:-120%}to{left:150%}}

/* nav */
.mh-page .mh-nav{background:transparent}
.mh-quotepage #quote{padding-top:150px}
.mh-quotepage main{display:flex;flex-direction:column}
.mh-quotepage main>#quote{order:-1}

.anim-off,.anim-off *,.anim-off::before,.anim-off::after,.anim-off *::before,.anim-off *::after{animation-play-state:paused!important}
#progress{position:fixed;top:0;left:0;height:2px;width:100%;transform:scaleX(0);transform-origin:0 50%;will-change:transform;z-index:100;background:linear-gradient(90deg,var(--gold),var(--gold-bright),#fff6df);box-shadow:0 0 12px rgba(248,218,120,.7)}
.mh-nav{position:fixed;top:0;left:0;right:0;z-index:50;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:24px;padding:14px 40px;background:transparent;transition:background .4s,padding .4s}
.nav-links a,.burger{filter:drop-shadow(0 1px 6px rgba(0,0,0,.7))}
.mh-nav.scrolled{background:rgba(10,10,11,.6);backdrop-filter:blur(14px) saturate(120%);-webkit-backdrop-filter:blur(14px);padding:8px 40px;border-bottom:1px solid var(--line)}
.brand{grid-column:2;justify-self:center}
.brand img{height:112px;width:auto;transition:height .4s;filter:drop-shadow(0 2px 10px rgba(0,0,0,.6))}
.nav-left{grid-column:1;justify-self:start}
.nav-right{grid-column:3;justify-self:end}
.nav-actions{display:none;grid-column:1;justify-self:start;gap:10px}
.nav-sms{display:inline-flex!important;white-space:nowrap;align-items:center;gap:8px;border:1px solid var(--gold);color:var(--gold-bright)!important;padding:8px 14px;border-radius:2px}
.nav-sms svg{width:16px;height:16px;fill:currentColor}
.nav-sms:hover{background:var(--gold);color:var(--ink)!important}
.nav-links a.nav-sms::after{display:none}
.nav-tel{display:flex;width:42px;height:42px;border:1px solid var(--line);border-radius:50%;align-items:center;justify-content:center;color:var(--gold-bright)}
.nav-tel svg{width:18px;height:18px;fill:currentColor}
.burger{grid-column:3;justify-self:end}
.mh-nav.scrolled .brand img{height:72px}
.nav-links{display:flex;gap:clamp(14px,2vw,30px);align-items:center}
.nav-links a{position:relative;font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:#cfc9bc;transition:color .25s}
.nav-links a:hover{color:var(--gold-bright)}
.nav-links a:not(.nav-call)::after{content:"";position:absolute;left:0;bottom:-4px;width:0;height:1px;background:linear-gradient(90deg,var(--gold),var(--gold-bright));transition:width .35s ease}
.nav-links a:not(.nav-call):hover::after{width:100%}
.nav-call{border:1px solid var(--gold);color:var(--gold-bright)!important;padding:9px 18px;border-radius:2px}
.nav-call:hover{background:var(--gold);color:var(--ink)!important}
.burger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:0;padding:8px}
.burger span{width:24px;height:1.5px;background:var(--gold);transition:transform .3s,opacity .3s}
.burger.is-open span:nth-child(1){transform:translateY(6.5px) rotate(45deg)}
.burger.is-open span:nth-child(2){opacity:0}
.burger.is-open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg)}
.mobile-menu{position:fixed;top:0;left:0;right:0;z-index:49;background:rgba(10,10,11,.98);border-bottom:1px solid var(--line);padding:96px 28px 28px;display:flex;flex-direction:column;gap:4px;transform:translateY(-110%);transition:transform .45s cubic-bezier(.2,.7,.2,1);visibility:hidden}
.mobile-menu.open{transform:none;visibility:visible}
.mobile-menu a{font-family:'Cormorant Garamond',serif;font-size:1.8rem;color:var(--paper);padding:10px 4px;border-bottom:1px solid rgba(255,255,255,.05)}
.mobile-menu .mm-cta{margin-top:14px;text-align:center;background:var(--gold-grad);color:var(--ink);border:0;border-radius:10px;padding:16px;font-family:'Jost',sans-serif;font-size:.9rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase}
.mobile-menu .mm-call{text-align:center;font-family:'Jost',sans-serif;font-size:.95rem;color:var(--gold-bright);border:0}
body.menu-open{overflow:hidden}
.skip{position:absolute;left:-999px;top:12px;z-index:200;background:var(--gold);color:var(--ink);padding:10px 16px;border-radius:4px}
.skip:focus{left:12px}

/* hero */
.hero{min-height:100vh;min-height:100svh;position:relative;display:flex;align-items:flex-end;padding:130px 0 9vh;overflow:hidden;background:#0a0a0b}
.hero-slides{position:absolute;inset:0;z-index:0;overflow:hidden}
.hero-slides img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transform:scale(1.06)}
.hero-slides img:first-child{opacity:1}
.hero-slides.run img{animation:fleetfade 24s ease-in-out infinite}
.hero-slides.run img:nth-child(2){animation-delay:8s}
.hero-slides.run img:nth-child(3){animation-delay:16s}
@keyframes fleetfade{0%{opacity:0;transform:scale(1.05)}8%{opacity:1}33%{opacity:1}41%{opacity:0;transform:scale(1.15)}100%{opacity:0;transform:scale(1.05)}}
.hero-overlay{position:absolute;inset:0;z-index:1;background:linear-gradient(to top,rgba(8,8,10,.95) 0%,rgba(8,8,10,.45) 48%,rgba(8,8,10,.65) 100%)}
.hero-aurora{position:absolute;inset:-25%;z-index:2;pointer-events:none;mix-blend-mode:screen;filter:blur(22px);background:radial-gradient(38% 42% at 28% 72%,rgba(227,184,79,.28),transparent 60%),radial-gradient(30% 34% at 74% 38%,rgba(248,218,120,.18),transparent 60%);transform:translate3d(-2%,2%,0) scale(1.06)}
@keyframes aurora{0%{transform:translate3d(-4%,3%,0) scale(1)}50%{transform:translate3d(5%,-3%,0) scale(1.14)}100%{transform:translate3d(-2%,5%,0) scale(1.06)}}
.hero-inner{position:relative;z-index:3;width:100%}
.hero.hero-v{display:block;min-height:0;padding:0 0 64px}
.stage{position:relative;height:min(56.25vw,82vh);overflow:hidden;background:#0a0a0b;margin-top:124px}
.stage-img{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;object-position:50% 50%;-webkit-mask-image:linear-gradient(to bottom,transparent 0,#000 10%,#000 86%,transparent 100%),linear-gradient(to right,transparent 0,#000 6%,#000 94%,transparent 100%);-webkit-mask-composite:source-in;mask-image:linear-gradient(to bottom,transparent 0,#000 10%,#000 86%,transparent 100%),linear-gradient(to right,transparent 0,#000 6%,#000 94%,transparent 100%);mask-composite:intersect;transform-origin:50% 62%;transition:opacity 1s ease;will-change:transform,opacity,filter}
.stage-img.pop{animation:popcars 7s cubic-bezier(.22,.8,.25,1) forwards}
.stage video{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;-webkit-mask-image:linear-gradient(to bottom,transparent 0,#000 10%,#000 86%,transparent 100%),linear-gradient(to right,transparent 0,#000 6%,#000 94%,transparent 100%);-webkit-mask-composite:source-in;mask-image:linear-gradient(to bottom,transparent 0,#000 10%,#000 86%,transparent 100%),linear-gradient(to right,transparent 0,#000 6%,#000 94%,transparent 100%);mask-composite:intersect;opacity:0;transition:opacity .12s linear;z-index:1}
.stage video.on{opacity:1}
.stage-logo{position:absolute;left:50%;top:9%;z-index:3;width:clamp(150px,24vw,360px);height:auto;transform:translateX(-50%);pointer-events:none;filter:drop-shadow(0 6px 24px rgba(0,0,0,.75)) drop-shadow(0 0 16px rgba(248,218,120,.35));animation:logoin 1s cubic-bezier(.34,1.56,.64,1) both,logoglow 4s ease-in-out 1s infinite}
@keyframes logoin{from{opacity:0;transform:translate(-50%,-14px) scale(.85)}to{opacity:1;transform:translateX(-50%) scale(1)}}
@keyframes logoglow{0%,100%{filter:drop-shadow(0 6px 24px rgba(0,0,0,.75)) drop-shadow(0 0 12px rgba(248,218,120,.25))}50%{filter:drop-shadow(0 6px 24px rgba(0,0,0,.75)) drop-shadow(0 0 26px rgba(248,218,120,.6))}}


@keyframes popcars{
0%{transform:translateY(7%) scale(.9);filter:blur(6px) brightness(.6)}
14%{transform:translateY(-1%) scale(1.02);filter:blur(0) brightness(1.08)}
20%{transform:translateY(0) scale(1);filter:brightness(1)}
100%{transform:scale(1.025);filter:brightness(1)}}
.stage-shine{position:absolute;inset:0;z-index:1;pointer-events:none;mix-blend-mode:screen;background:linear-gradient(105deg,transparent 40%,rgba(255,236,170,.35) 50%,transparent 60%);background-size:250% 100%;opacity:0;z-index:2}
.stage-shine.go{opacity:1;animation:shinepass 7s ease-in-out forwards}
@keyframes shinepass{0%,18%{background-position:130% 0}45%,100%{background-position:-30% 0}}
.stage::after{content:"";position:absolute;left:0;right:0;bottom:0;height:34%;background:linear-gradient(rgba(10,10,11,0),#0a0a0b);z-index:2}
.hero-v .hero-inner{margin-top:-2vw}
.mh .hero-v h1{font-size:clamp(2.4rem,4.6vw,4.2rem);max-width:20ch}
.hero-inner>*{animation:riseT .9s cubic-bezier(.2,.7,.2,1) both}
.hero-inner>:nth-child(2){animation-delay:.12s}
.hero-inner>:nth-child(3){animation-delay:.24s}
.hero-inner>:nth-child(4){animation-delay:.36s}
@keyframes rise{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:none}}
@keyframes riseT{from{transform:translateY(18px)}to{transform:none}}
.mh .hero h1{font-size:clamp(2.6rem,6.2vw,5.2rem);color:var(--paper);margin-bottom:18px;max-width:15ch}
.hero h1 em{font-style:italic;background:linear-gradient(100deg,var(--gold) 0%,#fff7e0 28%,var(--gold-bright) 50%,var(--gold) 78%);background-size:220% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent;background-position:40% center}
@keyframes shine{to{background-position:-220% center}}
.lede{max-width:520px;font-size:1.06rem;color:#d8d2c6;margin-bottom:28px}
.hero-quote{position:relative;overflow:hidden;display:grid;grid-template-columns:1fr 1fr;gap:14px;align-items:end;max-width:860px;background:rgba(12,12,14,.62);backdrop-filter:blur(12px) saturate(120%);-webkit-backdrop-filter:blur(12px);border:1px solid var(--line);border-radius:16px;padding:20px;box-shadow:0 26px 60px -30px rgba(0,0,0,.75)}
.hero-quote::after{content:"";position:absolute;top:0;left:-45%;width:45%;height:1px;will-change:transform;background:linear-gradient(90deg,transparent,var(--gold-bright),transparent);animation:qscan 5s linear infinite}
@keyframes qscan{0%{transform:translateX(0)}100%{transform:translateX(322%)}}
.hq-field:nth-child(1),.hq-field:nth-child(2),.hq-btn{grid-column:1/-1}
.hq-field{display:flex;flex-direction:column;gap:7px;min-width:0}
.hq-field label{font-size:.62rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-bright)}
.hq-field input{width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(248,218,120,.28);border-radius:8px;padding:11px 12px;color:var(--paper);font-family:'Jost',sans-serif;font-size:16px;color-scheme:dark}
.hq-field input::placeholder{color:#8f897b}
.hq-field input:focus{outline:none;border-color:var(--gold-bright);box-shadow:0 0 0 3px rgba(248,218,120,.16)}
.hq-btn{position:relative;overflow:hidden;white-space:nowrap;background:var(--gold-grad);color:var(--ink);border:none;border-radius:9px;padding:0 26px;height:46px;font-family:'Jost',sans-serif;font-weight:600;letter-spacing:.08em;text-transform:uppercase;font-size:.82rem;cursor:pointer;transition:transform .3s,box-shadow .3s}
.hq-btn:hover{transform:translateY(-2px);box-shadow:0 16px 34px -12px rgba(248,218,120,.55)}
@media(min-width:860px){.hero-quote{grid-template-columns:1.25fr 1.25fr 1fr .8fr auto;gap:12px;padding:18px}.hq-field:nth-child(1),.hq-field:nth-child(2),.hq-btn{grid-column:auto}}
.hero-links{margin-top:18px;display:flex;gap:22px;flex-wrap:wrap;font-size:.86rem;color:#d8d2c6}
.hero-links a:hover{color:var(--gold-bright)}
.mh main{display:flex;flex-direction:column}
.mh:not(.mh-inner) main>#quote{order:-1}
.hero-quote{display:none}
.hero-mcta{display:flex;flex-wrap:wrap;gap:12px;max-width:520px}
.hero-mcta .btn{text-align:center;padding:17px 26px;font-size:.85rem;border-radius:10px;flex:1 1 220px}
.hero-mcta .btn-ghost{background:rgba(10,10,11,.45)}

/* strip */
.strip{position:relative;border-top:1px solid var(--line);border-bottom:1px solid var(--line);background:var(--ink-soft)}
.strip::before{content:"";position:absolute;top:-1px;left:0;height:1px;width:100%;background:linear-gradient(90deg,transparent,var(--gold),transparent);background-size:50% 100%;background-repeat:no-repeat;animation:scan 6s linear infinite}
@keyframes scan{0%{background-position:-60% 0}100%{background-position:160% 0}}
.marquee{display:flex;overflow:hidden;padding:28px 0;-webkit-mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent);mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent)}
.marquee-track{display:flex;align-items:center;gap:56px;padding-right:56px;flex:none;animation:marquee 26s linear infinite}
@keyframes marquee{to{transform:translateX(-100%)}}
.marquee .stat{flex:none;text-align:left}
.stat-sep{font-style:normal;color:var(--gold);font-size:.9rem;display:inline-block;animation:spin 6s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.stat b{font-family:'Cormorant Garamond',serif;font-size:2.2rem;color:var(--gold);background:var(--gold-grad);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;display:block;line-height:1;font-weight:600;transition:text-shadow .4s}
.strip:hover .stat b{text-shadow:0 0 22px rgba(248,218,120,.45)}
.stat span{font-size:.74rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}

/* sections */
.mh section{padding:110px 0}
.sec-head{max-width:680px;margin-bottom:56px}
.sec-head.center{text-align:center;margin-left:auto;margin-right:auto}
.sec-head:hover .eyebrow::before{width:70px}
.mh .sec-head h2{font-size:clamp(2.1rem,4.4vw,3.3rem);color:var(--paper)}
.inline-link{color:var(--gold-bright);white-space:nowrap}
.sec-head p{color:var(--muted);margin-top:16px;font-size:1.02rem}
.alt{background:var(--ink-soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}

/* fleet */
.grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.car,.rev-card,.step{background:var(--panel);border:1px solid rgba(255,255,255,.05);border-radius:4px;overflow:hidden;transition:border-color .4s,transform .4s,box-shadow .4s}
.car:hover,.rev-card:hover,.step:hover{border-color:var(--gold);transform:translateY(-6px);box-shadow:0 24px 60px -20px rgba(0,0,0,.7),0 0 40px -12px rgba(227,184,79,.35)}
.car-img{aspect-ratio:16/9;overflow:hidden;position:relative;background:#0a0a0b}
.car-img img{position:absolute;inset:0;width:100%;height:100%}
.car-img img.car-main{object-fit:contain;z-index:1;transition:filter .5s,transform 1.2s}
.car-img img.car-bg{object-fit:cover;filter:blur(16px) brightness(.5) saturate(1.1);transform:scale(1.2)}
.car:nth-child(2) .car-img img{animation-duration:18s}
.car:nth-child(3) .car-img img{animation-duration:13s}
@keyframes kenburns{0%{transform:scale(1.05)}100%{transform:scale(1.12) translate3d(-2.5%,-1.5%,0)}}
.car-img::before{content:"";position:absolute;inset:0;z-index:3;pointer-events:none;background:linear-gradient(115deg,transparent 42%,rgba(255,255,255,.4) 50%,transparent 58%);transform:translateX(-130%)}
.car:hover .car-img::before{animation:sweep .85s ease}
@keyframes sweep{to{transform:translateX(130%)}}
.car:hover .car-img img.car-main{filter:brightness(1.09) contrast(1.03)}
.car-tag{position:absolute;top:14px;left:14px;z-index:2;font-size:.66rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-bright);background:rgba(10,10,11,.7);border:1px solid var(--line);padding:5px 11px;border-radius:2px}
.car-body{padding:26px}
.mh .car-body h3{font-size:1.7rem;color:var(--paper);margin-bottom:6px}
.seats{font-size:.76rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:12px}
.car-body p,.step p{font-size:.95rem;color:var(--muted)}

/* services */

/* services: photo tiles (desktop) / swipe strip (phone) */
.svc-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.svc{position:relative;display:flex;flex-direction:column;justify-content:flex-end;gap:8px;aspect-ratio:16/11;padding:14px;border-radius:14px;overflow:hidden;background:#141417;border:1px solid rgba(227,184,79,.22);isolation:isolate;transition:transform .45s cubic-bezier(.2,.7,.2,1),border-color .45s,box-shadow .45s}
.svc img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2;transition:transform .8s cubic-bezier(.2,.7,.2,1)}
.svc::before{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(180deg,rgba(0,0,0,.05) 25%,rgba(0,0,0,.88))}
.svc:hover{transform:translateY(-5px);border-color:rgba(248,218,120,.75);box-shadow:0 22px 50px -22px rgba(0,0,0,.8),0 0 36px -12px rgba(227,184,79,.45)}
.svc:hover img{transform:scale(1.07)}
.svc-ico{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:rgba(14,14,16,.55);border:1px solid rgba(248,218,120,.6);color:var(--gold-bright);transition:background .45s,color .45s}
.svc-ico svg{width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round}
.svc:hover .svc-ico{background:var(--gold-grad);color:#141416;border-color:transparent}
.mh .svc h3{font-size:1.25rem;line-height:1.1;color:#fff;margin:0;text-shadow:0 2px 12px rgba(0,0,0,.6)}
.svc .go{display:none;font-size:.68rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-bright)}
@media(max-width:1100px){.svc-grid{grid-template-columns:repeat(3,1fr)}}
@media(min-width:701px) and (max-width:1100px){.svc-grid>.svc:nth-child(10){display:none}}
.svc-hint{display:none;margin-top:10px;font-size:.75rem;letter-spacing:.18em;text-transform:uppercase;color:var(--muted)}
@media(max-width:700px){.svc-grid{display:flex;overflow-x:auto;scroll-snap-type:x mandatory;scroll-padding:0 16px;gap:12px;margin:0 -16px;padding:0 16px 6px;scrollbar-width:none;-webkit-overflow-scrolling:touch}.svc-grid::-webkit-scrollbar{display:none}.svc{flex:0 0 46%;aspect-ratio:auto;height:230px;scroll-snap-align:start;padding:14px}.svc .go{display:block}.mh .svc h3{font-size:1.2rem}.svc-hint{display:block}}
/* steps: gold timeline */
.tl{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;position:relative;text-align:center}
.tl::before{content:"";position:absolute;top:36px;left:16%;right:16%;height:2px;background:linear-gradient(90deg,transparent,#e3b84f 15%,#fff0a8 50%,#e3b84f 85%,transparent)}
.tl-m{position:relative;width:72px;height:72px;margin:0 auto 16px;border-radius:50%;background:#141417;border:2px solid var(--gold);display:flex;align-items:center;justify-content:center;color:var(--gold-bright);box-shadow:0 0 30px rgba(227,184,79,.3)}
.tl-m svg{width:30px;height:30px;fill:none;stroke:currentColor;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round}
.tl-m i{position:absolute;top:-6px;right:-6px;width:24px;height:24px;border-radius:50%;background:var(--gold-grad);color:#111;font-style:normal;font-size:.8rem;font-weight:500;display:flex;align-items:center;justify-content:center}
.mh .tl h3{font-size:1.5rem;color:var(--paper);margin:0 0 4px}
.mh .tl p{color:var(--muted);font-size:1rem;line-height:1.55;max-width:300px;margin:0 auto}
.tl-cta{text-align:center;margin-top:30px}
@media(max-width:700px){.tl{grid-template-columns:1fr;text-align:left;gap:20px}.tl::before{left:35px;right:auto;top:20px;bottom:20px;width:2px;height:auto;background:linear-gradient(180deg,#e3b84f,#fff0a8,#e3b84f)}.tl-s{display:grid;grid-template-columns:72px 1fr;column-gap:16px;align-items:center}.tl-m{margin:0;grid-row:span 2}.mh .tl p{margin:0}}
/* steps */
.step{padding:40px 34px}
.step-num{font-family:'Cormorant Garamond',serif;font-size:2.8rem;color:var(--gold);background:var(--gold-grad);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;display:block;line-height:1;margin-bottom:16px}
.mh .step h3{font-size:1.6rem;color:var(--paper);margin-bottom:8px}

/* story */
.story-grid{display:grid;grid-template-columns:1.1fr 1fr;gap:60px;align-items:center}
.mh .story h2{font-size:clamp(2.1rem,4.2vw,3.1rem);color:var(--paper);margin-bottom:22px}
.story p{color:#c9c4b8;font-size:1.05rem;margin-bottom:16px}
.story .initial{font-family:'Cormorant Garamond',serif;color:var(--gold);float:left;font-size:4.2rem;line-height:.8;margin:6px 14px 0 0}
.story .sig{color:var(--gold-bright);font-family:'Cormorant Garamond',serif;font-size:1.5rem;margin:6px 0 22px}
.story-photo{perspective:1200px}
.story-photo img{width:100%;height:auto;border:1px solid var(--line);border-radius:8px;transform-origin:50% 60%;animation:carturn 9s ease-in-out infinite;box-shadow:0 40px 70px -30px rgba(0,0,0,.9),0 0 50px -20px rgba(227,184,79,.35)}
@keyframes carturn{0%,100%{transform:rotateY(-14deg) rotateX(3deg) translateY(0)}50%{transform:rotateY(14deg) rotateX(-2deg) translateY(-10px)}}

/* reviews */
.rev-badge{display:flex;align-items:center;justify-content:center;gap:12px;margin:-30px auto 50px;color:var(--muted);font-size:.92rem;flex-wrap:wrap}
.rev-badge .g-stars,.rev-stars{color:var(--gold-bright);letter-spacing:2px}
.rev-badge b{color:var(--paper);font-weight:500}
.rev-card{padding:30px;display:flex;flex-direction:column}
.rev-stars{font-size:.95rem;margin-bottom:14px}
.rev-text{color:#d3cec2;font-size:.96rem;line-height:1.65;flex:1;margin-bottom:22px}
.rev-person{display:flex;align-items:center;gap:14px}
.rev-avatar{width:44px;height:44px;flex:none;border-radius:50%;background:var(--gold-grad);color:var(--ink);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-weight:600;font-size:1.15rem;transition:transform .4s,box-shadow .4s}
.rev-card:hover .rev-avatar{transform:scale(1.08);box-shadow:0 0 22px rgba(248,218,120,.5)}
.rev-name{color:var(--paper);font-size:.94rem}
.rev-tag{color:var(--gold);font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;margin-top:2px}
.rev-cta{display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:14px 16px;margin-top:34px}
.mh .rev-cta a.btn{margin:0;flex:0 0 auto;padding:15px 30px}
.mh .rev-more{flex-basis:100%;text-align:center;color:var(--gold);font-size:.82rem;letter-spacing:.14em;text-transform:uppercase;margin-top:4px}
.rev-more:hover{color:var(--gold-bright)}
.rev-marquee{overflow:hidden;width:100vw;margin-left:calc(50% - 50vw);padding:14px 0 18px;-webkit-mask-image:linear-gradient(90deg,transparent,#000 6%,#000 94%,transparent);mask-image:linear-gradient(90deg,transparent,#000 6%,#000 94%,transparent)}
.rev-track{display:flex;width:max-content;animation:revscroll 70s linear infinite}
.rev-marquee:hover .rev-track,.rev-marquee:focus-within .rev-track{animation-play-state:paused}
@keyframes revscroll{to{transform:translateX(-50%)}}
.rev-track .rev-card{flex:none;width:340px;margin-right:20px;padding:24px 24px 22px}
.rev-track .rev-card:hover{transform:translateY(-4px)}
.rev-track .rev-text{display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;font-size:.95rem;line-height:1.6;margin-bottom:18px;flex:none}
.rev-track .rev-stars{margin-bottom:10px}
@media (prefers-reduced-motion:reduce){.rev-track{animation:none}.rev-marquee{overflow-x:auto}}

/* quote (white) */
.mh section.quote-white{position:relative;background:radial-gradient(900px 520px at 50% 0%,rgba(227,184,79,.10),transparent 65%),var(--ink-soft);color:var(--paper);padding:clamp(90px,12vw,150px) 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.mh .quote-title{font-weight:600;text-align:center;font-size:clamp(3.2rem,9.5vw,7.4rem);line-height:.92;margin-bottom:18px;background:linear-gradient(100deg,var(--gold) 0%,#fff7e0 28%,var(--gold-bright) 50%,var(--gold) 78%);background-size:220% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:var(--gold-bright)}
.quote-sub{text-align:center;max-width:560px;margin:0 auto 50px;color:#d8d2c6;font-size:1.08rem;font-weight:300}
#quote .mqf>h2,#quote .mqf>.mqf-sub{display:none}
#quote .mqf-trust{max-width:920px;margin:0 auto 22px;gap:14px}
#quote .mqf-trust div{background:var(--panel);border:1px solid var(--line);border-radius:12px;color:var(--muted);font-family:'Jost',sans-serif;font-size:.95rem;padding:16px 8px}
#quote .mqf-trust b{color:var(--gold-bright);font-size:1.05rem;font-weight:500}
#quote .mqf-trust svg{stroke:var(--gold-bright);width:30px;height:30px}
#quote .mqf{max-width:920px;background:rgba(20,20,22,.92);border:1px solid var(--gold);border-radius:16px;padding:clamp(26px,5vw,56px);color:var(--paper);box-shadow:0 34px 80px -34px rgba(0,0,0,.8),0 0 50px -18px rgba(227,184,79,.35);font-family:'Jost',sans-serif}
#quote .mqf label{color:var(--gold-bright);font-weight:500;font-size:clamp(.98rem,1.5vw,1.08rem);letter-spacing:.02em;margin:20px 0 9px}
#quote .mqf input[type=number],#quote .mqf input[type=text],#quote .mqf input[type=email],#quote .mqf input[type=tel],#quote .mqf input[type=date],#quote .mqf input[type=time],#quote .mqf select,#quote .mqf textarea{background:rgba(255,255,255,.04);color:var(--paper);border:1px solid rgba(248,218,120,.35);border-radius:10px;padding:14px 16px;font-family:'Jost',sans-serif;font-size:1.05rem;font-weight:400;color-scheme:dark}
#quote .mqf select option{background:#141416;color:var(--paper)}
#quote .mqf .mqf-row>div{min-width:0}
#quote .mqf input[type=date],#quote .mqf input[type=time]{-webkit-appearance:none;appearance:none;display:block;width:100%;min-width:0;max-width:100%;min-height:58px;line-height:1.3;text-align:left;background-repeat:no-repeat;background-position:right 16px center;padding-right:48px}
#quote .mqf input[type=date]{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none' stroke='%23e2c274' stroke-width='1.8' stroke-linecap='round' viewBox='0 0 24 24'%3E%3Crect x='3' y='5' width='18' height='16' rx='2'/%3E%3Cpath d='M16 3v4M8 3v4M3 10h18'/%3E%3C/svg%3E")}
#quote .mqf input[type=time]{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none' stroke='%23e2c274' stroke-width='1.8' stroke-linecap='round' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='12' r='9'/%3E%3Cpath d='M12 7v5l3 2'/%3E%3C/svg%3E")}
#quote .mqf input::-webkit-date-and-time-value{text-align:left;margin:0}
#quote .mqf input::-webkit-calendar-picker-indicator{opacity:0;cursor:pointer}
#quote .mqf input::placeholder,#quote .mqf textarea::placeholder{color:#8f897b;font-weight:300}
#quote .mqf input:focus,#quote .mqf select:focus,#quote .mqf textarea:focus{border-color:var(--gold-bright);box-shadow:0 0 0 3px rgba(248,218,120,.18)}
#quote .mqf .mqf-row{gap:26px}
#quote .mqf .mqf-check{font-weight:300;color:#d8d2c6;font-size:1rem;margin-top:16px}
#quote .mqf .mqf-check input{accent-color:var(--gold);width:20px;height:20px}
#quote .mqf .mqf-addstop{color:var(--gold-bright);text-decoration:underline;text-underline-offset:3px;font-size:1rem}
#quote .mqf button[type=submit]{position:relative;overflow:hidden;background:var(--gold-grad);color:var(--ink);border-radius:10px;padding:22px;font-family:'Jost',sans-serif;font-size:clamp(1.05rem,2vw,1.3rem);font-weight:600;letter-spacing:.1em;margin-top:28px;transition:transform .3s,box-shadow .3s}
#quote .mqf button[type=submit]::before{content:"";position:absolute;top:0;left:-120%;width:60%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.55),transparent);transform:skewX(-18deg)}
#quote .mqf button[type=submit]:hover{background:var(--gold-grad);transform:translateY(-2px);box-shadow:0 18px 40px -12px rgba(248,218,120,.55)}
#quote .mqf button[type=submit]:hover::before{animation:sheen .9s ease}
#quote .mqf .mqf-err{color:#ff8a80}
#quote .mqf .mqf-bad{border-color:#ff8a80!important}
#quote .mqf .mqf-alert{background:#3a1d1d;border-color:#ff8a80;color:#ffd0d0}
#quote .mqf .mqf-note{color:var(--muted)}
#quote .mqf .mqf-ok h2{color:var(--gold-bright);font-family:'Cormorant Garamond',serif;font-size:2.6rem}
#quote .mqf .mqf-ok p{color:#d8d2c6;font-size:1.1rem}
#quote .mqf .mqf-ok a{color:var(--gold-bright)!important;font-weight:500}

/* faq */
.faq-list{max-width:820px;margin:0 auto;border-top:1px solid var(--line)}
.faq-item{border-bottom:1px solid var(--line)}
.faq-item summary{list-style:none;cursor:pointer;padding:24px 4px;display:flex;justify-content:space-between;align-items:center;gap:20px;transition:color .25s}
.faq-item summary h3{font-size:1.5rem;color:inherit;font-weight:500}
.faq-item summary{color:var(--paper)}
.faq-item summary::-webkit-details-marker{display:none}
.faq-item summary::after{content:"+";color:var(--gold);font-size:1.7rem;line-height:1;transition:transform .3s}
.faq-item[open] summary,.faq-item summary:hover{color:var(--gold-bright)}
.faq-item[open] summary::after{transform:rotate(45deg)}
.faq-item p{color:var(--muted);font-size:1rem;line-height:1.65;padding:0 4px 24px;max-width:72ch}

/* footer */
.mh-foot{background:var(--ink);border-top:1px solid var(--line);padding:66px 0 32px}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1.2fr;gap:40px;margin-bottom:46px}
.foot-brand img{height:70px;width:auto}
.foot-brand p{color:var(--muted);max-width:300px;font-size:.94rem;margin-top:14px}
.foot-h{font-size:.74rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);margin-bottom:16px}
.mh .foot-h{font-family:inherit;font-size:.74rem;font-weight:400;line-height:1.5;letter-spacing:.16em}
.mh .foot-sub{font-family:inherit;font-weight:400;line-height:1.5;letter-spacing:.14em}
:where(.mh) :where(h4,h5,h6){font-family:inherit;font-weight:400;line-height:1.5;margin:0;padding:0}
.foot-sub{font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);opacity:.75;margin:14px 0 6px}
.foot-col .foot-sub:first-of-type{margin-top:0}
.foot-col a,.foot-col span,.foot-col address{display:block;color:var(--muted);font-size:.94rem;margin-bottom:10px;transition:color .25s}
.foot-col a:hover{color:var(--gold-bright)}
.foot-social{display:flex;gap:12px;margin-top:20px}
.foot-social a{width:40px;height:40px;border:1px solid var(--line);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--gold-bright);transition:all .3s}
.foot-social a:hover{background:var(--gold);color:var(--ink);border-color:var(--gold)}
.foot-social svg{width:18px;height:18px;fill:currentColor}
.foot-bottom{border-top:1px solid rgba(255,255,255,.06);padding-top:24px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:14px;font-size:.84rem;color:var(--muted)}
.foot-bottom a:hover{color:var(--gold-bright)}

#stickyQuote{position:fixed;left:24px;bottom:24px;z-index:60;background:var(--gold-grad);color:var(--ink);border-radius:50px;padding:15px 26px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;font-size:.8rem;box-shadow:0 14px 34px -10px rgba(0,0,0,.65);opacity:0;transform:translateY(24px);pointer-events:none;transition:opacity .4s,transform .4s}
#stickyQuote.show{opacity:1;transform:none;pointer-events:auto}
#stickyText{display:none}
@media(max-width:900px){#stickyText{display:flex;align-items:center;gap:8px;position:fixed;right:14px;bottom:18px;z-index:60;padding:12px 20px;border-radius:50px;background:rgba(14,14,16,.92);border:1px solid var(--gold);color:var(--gold-bright);font-weight:600;letter-spacing:.08em;text-transform:uppercase;font-size:.8rem;box-shadow:0 14px 34px -10px rgba(0,0,0,.65);opacity:0;transform:translateY(24px);pointer-events:none;transition:opacity .4s,transform .4s}#stickyText svg{width:16px;height:16px;fill:currentColor}#stickyText.show{opacity:1;transform:none;pointer-events:auto}}
body.mh::after{content:"";position:fixed;inset:0;z-index:999;pointer-events:none;opacity:.035;mix-blend-mode:overlay;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E")}
.will-reveal{opacity:0;transform:translateY(24px);transition:opacity .7s cubic-bezier(.2,.7,.2,1),transform .7s cubic-bezier(.2,.7,.2,1)}
.will-reveal.is-in{opacity:1;transform:none}
.fleet-pop .car-img img{animation:none}
.fleet-pop>.will-reveal{transform:translateY(90px) scale(.86);transition:opacity .6s ease,transform 1s cubic-bezier(.34,1.56,.64,1)}
.fleet-pop>.will-reveal.is-in{transform:none}
.fleet-pop>.will-reveal .car-img img.car-main{transform:translateY(30px) scale(.8);transition:transform 1.2s cubic-bezier(.2,.7,.2,1) .15s}
.fleet-pop>.will-reveal.is-in .car-img img.car-main{transform:none}

/* same readable text sizes on every screen */
body.mh{font-size:17px}
.lede{font-size:1.12rem}
.car-body p,.step p,.svc p,.faq-item p,.story p{font-size:1.06rem}
.rev-text{font-size:1.04rem}
.stat span{font-size:.82rem}
.seats,.rev-tag{font-size:.82rem}
.foot-col a,.foot-col span,.foot-col address,.foot-brand p{font-size:1.04rem}
#quote .mqf label{font-size:1.15rem}
#quote .mqf input[type=number],#quote .mqf input[type=text],#quote .mqf input[type=email],#quote .mqf input[type=tel],#quote .mqf input[type=date],#quote .mqf input[type=time],#quote .mqf select,#quote .mqf textarea{font-size:1.12rem;padding:15px 16px}
#quote .mqf input[type=date],#quote .mqf input[type=time]{padding-right:48px}
#quote .mqf .mqf-check{font-size:1.06rem}
#quote .mqf .mqf-addstop{font-size:1.08rem}
#quote .mqf .mqf-note{font-size:.98rem}
.quote-sub{font-size:1.12rem}
#quote .mqf-trust div{font-size:.86rem}
#quote .mqf-trust b{font-size:.98rem}
@media(max-width:900px){
.nav-links{display:none}.burger{display:flex}.nav-actions{display:flex}
.grid3,.story-grid{grid-template-columns:1fr}
.story-photo{order:-1}
.foot-grid{grid-template-columns:1fr 1fr}
.mh section{padding:80px 0}.wrap{padding:0 22px}.mh-nav,.mh-nav.scrolled{padding:10px 22px}
.brand img{height:64px}.mh-nav.scrolled .brand img{height:52px}
.hero{min-height:0;padding:118px 0 44px}
.hero.hero-v{padding:0 0 44px}
.stage{height:56.25vw;min-height:0;margin-top:80px}
.stage-logo{width:118px;top:5%}
.hero-v .hero-inner{margin-top:8px}
.hero-aurora,.hero-overlay+.hero-aurora{display:none}
body.mh::after{display:none}
.car-img img,.hero-quote::after,.strip::before{animation:none}


.lede{margin-bottom:24px}
.mh section.quote-white{padding:48px 0 60px}
.mh-quotepage #quote.quote-white{padding-top:104px}
.quote-sub{margin-bottom:26px}
}
@media(max-width:560px){
.wrap{padding:0 16px}
.mh section{padding:64px 0}
.foot-grid{grid-template-columns:1fr 1fr;gap:30px 20px}.mh footer{padding-bottom:92px}.foot-brand,.foot-grid>.foot-col:last-child{grid-column:1/-1}
.marquee{padding:20px 0}.marquee-track{gap:36px;padding-right:36px;animation-duration:20s}
.rev-track{animation-duration:55s}.rev-track .rev-card{width:280px;margin-right:14px;padding:20px}
.stat b{font-size:1.8rem}
#quote .mqf{padding:22px 18px;border-radius:12px}
.mh .quote-title{font-size:3rem;margin-bottom:12px}
#quote .mqf-trust{gap:8px;margin-bottom:16px}
#quote .mqf-trust div{padding:12px 4px;font-size:.78rem}
#quote .mqf-trust b{font-size:.9rem}
#quote .mqf label{margin:16px 0 7px}
#quote .mqf .mqf-row{gap:0}
.rev-cta{align-items:stretch}.mh .rev-cta a.btn{flex:1 1 0;display:flex;align-items:center;justify-content:center;text-align:center;padding:14px 10px;font-size:.72rem;letter-spacing:.12em;line-height:1.35;max-width:220px;min-height:54px}
#stickyQuote{right:auto;left:14px;bottom:18px;padding:13px 20px}
.eyebrow{letter-spacing:.22em;font-size:.66rem}
.eyebrow::before{width:28px}
}
@media(prefers-reduced-motion:reduce){
*,*::before,*::after{animation:none!important;transition:none!important}
.will-reveal{opacity:1!important;transform:none!important}
.stage-img{opacity:1!important}
.stage-logo{animation:none!important}
.hero h1 em{-webkit-text-fill-color:var(--gold-bright);color:var(--gold-bright)}
}

/* inner pages */
.mh-inner .mh-nav{background:transparent}
.pg-hero{position:relative;padding:150px 0 50px;background:radial-gradient(800px 500px at 75% 40%,rgba(227,184,79,.12),transparent 65%),#0a0a0b}
.pg-hero-grid{display:grid;grid-template-columns:1.05fr 1fr;gap:50px;align-items:center}
.pg-hero-grid.no-img{grid-template-columns:1fr}
.pg-hero-inner{position:relative;z-index:2;animation:riseT .9s cubic-bezier(.2,.7,.2,1) both}
.pg-hero-photo{perspective:1200px}
.pg-hero-photo img{width:100%;height:auto;border-radius:10px;border:1px solid var(--line);box-shadow:0 40px 70px -30px rgba(0,0,0,.9),0 0 50px -20px rgba(227,184,79,.35);animation:carturn 10s ease-in-out infinite}
.mh .pg-hero h1{font-size:clamp(2.4rem,5.5vw,4.6rem);color:var(--paper);max-width:18ch;margin-bottom:26px}
.pg-hero-cta{display:flex;gap:14px;flex-wrap:wrap}
.mh section.pg-body{padding:70px 0 90px}
.pg-wrap{max-width:900px}
.pg-text{color:#d8d2c6;font-size:1.1rem;line-height:1.75;margin-bottom:34px}
.pg-text p,.pg-text ul,.pg-text ol{margin-bottom:16px}
.pg-text ul,.pg-text ol{padding-left:22px}
.pg-text li{margin-bottom:6px}
.pg-text li::marker{color:var(--gold)}
.mh .pg-text h2{font-size:clamp(1.8rem,3.4vw,2.6rem);color:var(--paper);margin:10px 0 16px}
.mh .pg-text h3,.mh .pg-text h4{font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:var(--gold-bright);margin:22px 0 10px;font-weight:500}
.pg-text img{display:block;width:100%;height:auto;border-radius:8px;border:1px solid var(--line);margin:8px 0 22px}
.pg-text blockquote{border-left:2px solid var(--gold);background:var(--panel);padding:18px 22px 6px;margin:0 0 16px;border-radius:4px;color:#d8d2c6}
.pg-text a{color:var(--gold-bright);text-decoration:underline;text-underline-offset:3px}
.pg-text strong{color:var(--paper);font-weight:500}
.pg-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:22px;margin:10px 0 40px}
.pg-card{background:var(--panel);border:1px solid rgba(255,255,255,.06);border-radius:6px;overflow:hidden;transition:border-color .4s,transform .4s;display:flex;flex-direction:column}
.pg-quotes{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:4px 0 26px}.pg-quotes blockquote{margin:0;font-size:.95rem}
@media(max-width:700px){.pg-quotes{grid-template-columns:1fr;gap:12px}}
.pg-card:hover{border-color:var(--gold);transform:translateY(-5px)}
.pg-card-img{aspect-ratio:3/2;background:#0c0c0d;overflow:hidden}.pg-card-img img{width:100%;height:100%;object-fit:cover;display:block}
.pg-card-body{padding:22px;color:var(--muted);font-size:.95rem;line-height:1.6;flex:1;display:flex;flex-direction:column}.pg-card-body .pg-card-link{margin-top:auto;align-self:flex-start;padding-top:8px}
.pg-card-body p,.pg-card-body ul{margin-bottom:10px}.pg-card-body ul{padding-left:18px}
.mh .pg-card-body h3{font-size:1.5rem;color:var(--paper);margin-bottom:10px}
.pg-card-body h4{color:var(--gold-bright);font-weight:400;margin-bottom:8px}
.pg-card-link{display:inline-block;margin-top:6px;font-size:.74rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-bright)}
.pg-contact{display:inline-block;margin:0 12px 14px 0;padding:16px 24px;border:1px solid var(--gold);border-radius:10px;color:var(--gold-bright);font-size:1.15rem;background:var(--panel)}
.pg-contact:hover{background:var(--gold);color:var(--ink)}
.pg-gallery{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin:10px 0 40px}
.pg-gallery img{width:100%;height:auto;border-radius:6px;border:1px solid var(--line)}
@media(max-width:900px){.pg-hero-grid{grid-template-columns:1fr;gap:28px}.pg-hero-photo{order:-1}}
@media(max-width:560px){.pg-hero{min-height:0;padding:104px 0 36px}.pg-hero-cta .btn{flex:1 1 100%;text-align:center}}
</style>
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
<a href="#quote" id="stickyQuote">Get a Quote</a><a href="sms:+18438041188" id="stickyText" aria-label="Text us at (843) 804-1188"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2Z"/></svg>Text us</a>

<nav class="mh-nav" id="nav" aria-label="Main">
	<div class="nav-links nav-left">
		<a href="sms:+18438041188" class="nav-sms"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2Zm3 6.5a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Z"/></svg>Text us</a>
		<a href="<?php echo $h; ?>#fleet">Fleet</a>
		<a href="<?php echo $h; ?>#services">Services</a>
		<a href="#reviews">Reviews</a>
		<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
	</div>
	<div class="nav-actions"><a href="<?php echo esc_attr( $tel ); ?>" class="nav-tel" aria-label="Call <?php echo esc_attr( $phone ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2Z"/></svg></a><a href="sms:+18438041188" class="nav-tel" aria-label="Text <?php echo esc_attr( $phone ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2Zm3 6.5a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Zm5 0a1.5 1.5 0 1 0 0 .01Z"/></svg></a></div>
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
</div>

<?php if ( $is_home ) : ?>
<header class="hero hero-v" id="top">
	<div class="stage" aria-hidden="true">
		<img class="stage-img" src="<?php echo esc_url( $a( 'open3.webp' ) ); ?>" srcset="<?php echo esc_url( $a( 'open3-480.webp' ) ); ?> 480w, <?php echo esc_url( $a( 'open3-s.webp' ) ); ?> 720w, <?php echo esc_url( $a( 'open3-828.webp' ) ); ?> 828w, <?php echo esc_url( $a( 'open3-m.webp' ) ); ?> 1080w, <?php echo esc_url( $a( 'open3-1440.webp' ) ); ?> 1440w, <?php echo esc_url( $a( 'open3.webp' ) ); ?> 1920w" sizes="100vw" width="1920" height="1080" alt="Mecca Limo black Mercedes sedan, Sprinter and Cadillac Escalade at sunset in Charleston, SC" fetchpriority="high">
		<video id="heroVideo" muted playsinline autoplay preload="auto" data-d="<?php echo esc_url( $a( 'open3-1080.mp4' ) ); ?>" data-m="<?php echo esc_url( $a( 'open3-540.mp4' ) ); ?>" data-w="<?php echo esc_url( $a( 'open3-540.webm' ) ); ?>"></video><script>(function(v){if(!v||matchMedia('(prefers-reduced-motion: reduce)').matches)return;v.addEventListener('playing',function(){v.classList.add('on');});v.addEventListener('ended',function(){var st=v.closest('.stage');if(st)st.classList.add('hold');});v.src=v.canPlayType('video/mp4; codecs="avc1.42E01E"')?(innerWidth<900?v.dataset.m:v.dataset.d):v.dataset.w;var p=v.play();if(p&&p.catch)p.catch(function(){});})(document.getElementById('heroVideo'));</script>
	</div>
	<div class="hero-aurora" aria-hidden="true"></div>
	<div class="wrap hero-inner">
		<span class="eyebrow">Charleston, SC · Open 24/7</span>
		<h1>Charleston limo service with comfort, safety &amp; <em>luxury</em>.</h1>
		<p class="lede">Family-owned chauffeur service for airport transfers, weddings, corporate travel and nights out across Charleston and the Lowcountry.</p>
		<div>
			<form class="hero-quote" id="heroQuote" aria-label="Start your quote">
				<div class="hq-field"><label for="hqPickup">Pick up</label><input id="hqPickup" type="text" placeholder="Address, hotel or airport" autocomplete="off"></div>
				<div class="hq-field"><label for="hqDrop">Drop off</label><input id="hqDrop" type="text" placeholder="Destination" autocomplete="off"></div>
				<div class="hq-field"><label for="hqDate">Date</label><input id="hqDate" type="date" min="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>"></div>
				<div class="hq-field"><label for="hqPax">Passengers</label><input id="hqPax" type="number" min="1" max="99" inputmode="numeric" placeholder="e.g. 4"></div>
				<button class="hq-btn" type="submit">Get a Quote</button>
			</form>
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
		<p class="quote-sub">Tell us about your trip and we'll get back to you with prices and availability.</p>
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
		<div class="svc-grid">
			<?php foreach ( $services as $i => $s ) : ?>
			<a class="svc" href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $up_url . $svc_imgs[ $s[1] ] . '-t480.webp' ); ?>" width="480" height="330" alt="<?php echo esc_attr( $s[0] . ' limo service in Charleston, SC' ); ?>"><span class="svc-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><?php echo isset( $svc_icons[ $s[1] ] ) ? $svc_icons[ $s[1] ] : ''; ?></svg></span><h3><?php echo esc_html( $s[0] ); ?></h3><span class="go">Book now →</span></a>
			<?php endforeach; ?>
		</div>
		<span class="svc-hint" aria-hidden="true">Swipe for more →</span>
	</div>
</section>
<?php else : ?>
<div class="strip">
	<div class="marquee"><div class="marquee-track"><div class="stat"><b>5.0 ★</b><span>Google rating</span></div><i class="stat-sep">◆</i><div class="stat"><b>24/7</b><span>Always open</span></div><i class="stat-sep">◆</i><div class="stat"><b>Family</b><span>Owned &amp; operated</span></div><i class="stat-sep">◆</i><div class="stat"><b>Licensed</b><span>&amp; fully insured</span></div><i class="stat-sep">◆</i><div class="stat"><b>Pro</b><span>Chauffeurs</span></div><i class="stat-sep">◆</i><div class="stat"><b>CHS</b><span>Airport transfers</span></div><i class="stat-sep">◆</i></div><div class="marquee-track" aria-hidden="true"><div class="stat"><b>5.0 ★</b><span>Google rating</span></div><i class="stat-sep">◆</i><div class="stat"><b>24/7</b><span>Always open</span></div><i class="stat-sep">◆</i><div class="stat"><b>Family</b><span>Owned &amp; operated</span></div><i class="stat-sep">◆</i><div class="stat"><b>Licensed</b><span>&amp; fully insured</span></div><i class="stat-sep">◆</i><div class="stat"><b>Pro</b><span>Chauffeurs</span></div><i class="stat-sep">◆</i><div class="stat"><b>CHS</b><span>Airport transfers</span></div><i class="stat-sep">◆</i></div></div>
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
		<div class="grid3 fleet-pop">
			<div class="car">
				<div class="car-img"><span class="car-tag">Sprinter</span><img class="car-bg" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sprinter-wide.webp' ) ); ?>" alt="Mercedes Sprinter limo van background" aria-hidden="true" width="800" height="450"><img class="car-main" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sprinter-wide.webp' ) ); ?>" width="800" height="450" alt="Black Mercedes-Benz Sprinter limo van by Mecca Limo in Charleston"></div>
				<div class="car-body"><h3>Mercedes Sprinter</h3><h4 class="seats">Seats up to 14 with luggage</h4><p>The choice for wedding parties, corporate groups and bachelorette weekends.</p></div>
			</div>
			<div class="car">
				<div class="car-img"><span class="car-tag">SUV</span><img class="car-bg" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-suv.webp' ) ); ?>" alt="Cadillac Escalade SUV limo background" aria-hidden="true" width="800" height="450"><img class="car-main" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-suv.webp' ) ); ?>" width="800" height="446" alt="Black Cadillac Escalade SUV limo in Charleston SC"></div>
				<div class="car-body"><h3>Luxury SUV</h3><h4 class="seats">Seats up to 6 with luggage</h4><p>Cadillac Escalade, Chevrolet Suburban and GMC Yukon Denali. Room for the group and every bag.</p></div>
			</div>
			<div class="car">
				<div class="car-img"><span class="car-tag">Sedan</span><img class="car-bg" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sedan.webp' ) ); ?>" alt="Executive sedan background" aria-hidden="true" width="800" height="450"><img class="car-main" loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sedan.webp' ) ); ?>" width="800" height="437" alt="Black executive sedan for airport and business travel in Charleston"></div>
				<div class="car-body"><h3>Executive Sedan</h3><h4 class="seats">Seats up to 3 with 2 bags</h4><p>Quiet and private for airport transfers and business travel. Always on time.</p></div>
			</div>
		</div>
	</div>
</section>

<section class="alt" id="services">
	<div class="wrap">
		<div class="sec-head">
			<span class="eyebrow">What We Do</span>
			<h2>Limo service for every occasion.</h2>
		</div>
		<div class="svc-grid">
			<?php foreach ( $services as $i => $s ) : ?>
			<a class="svc" href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><img loading="lazy" decoding="async" src="<?php echo esc_url( $up_url . $svc_imgs[ $s[1] ] . '-t480.webp' ); ?>" width="480" height="330" alt="<?php echo esc_attr( $s[0] . ' limo service in Charleston, SC' ); ?>"><span class="svc-ico" aria-hidden="true"><svg viewBox="0 0 24 24"><?php echo isset( $svc_icons[ $s[1] ] ) ? $svc_icons[ $s[1] ] : ''; ?></svg></span><h3><?php echo esc_html( $s[0] ); ?></h3><span class="go">Book now →</span></a>
			<?php endforeach; ?>
		</div>
		<span class="svc-hint" aria-hidden="true">Swipe for more →</span>
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
			<div class="tl-s"><div class="tl-m"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg><i>2</i></div><h3>We confirm your booking</h3><p>Your price, the right vehicle and your professional chauffeur, fast.</p></div>
			<div class="tl-s"><div class="tl-m"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 17h14l-1.5-5h-11z"/><circle cx="8" cy="17" r="1.6"/><circle cx="16" cy="17" r="1.6"/><path d="M7 12l1.5-4h7l1.5 4"/></svg><i>3</i></div><h3>Arrive in style</h3><p>Early pickup, flight tracking and a safe, on-time arrival.</p></div>
		</div>
		<div class="tl-cta"><a class="btn btn-gold" href="#quote">Get a free quote</a></div>
	</div>
</section>

<section class="alt story" id="story">
	<div class="wrap story-grid">
		<div class="story-copy">
			<span class="eyebrow">Our Story</span>
			<h2>Mecca Limo: a family business built on trust.</h2>
			<p><span class="initial">M</span>ecca Limo was created with a simple mission: to give Charleston a more convenient, reliable and luxurious way to get around. As a family-owned business, our clients are at the center of everything we do.</p>
			<p>From 4 a.m. airport transfers to wedding weekends and corporate events across the Lowcountry, we show up early, drive clean vehicles, and get you there safely. That is the whole promise.</p>
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
			<?php foreach ( array( false, true ) as $dup ) : foreach ( $reviews as $r ) :
				$ini = implode( '', array_map( function ( $w ) { return mb_substr( $w, 0, 1 ); }, explode( ' ', $r[0] ) ) );
				?>
			<div class="rev-card"<?php echo $dup ? ' aria-hidden="true"' : ''; ?>>
				<div class="rev-stars" aria-label="5 out of 5 stars">★★★★★</div>
				<p class="rev-text">“<?php echo esc_html( $r[2] ); ?>”</p>
				<div class="rev-person"><div class="rev-avatar" aria-hidden="true"><?php echo esc_html( $ini ); ?></div><div><div class="rev-name"><?php echo esc_html( $r[0] ); ?></div><div class="rev-tag"><?php echo esc_html( $r[1] ); ?></div></div></div>
			</div>
			<?php endforeach; endforeach; ?>
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
		<p class="quote-sub">Tell us about your trip and we'll get back to you with prices and availability.</p>
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
					<a href="https://www.linkedin.com/in/moe-shalaby-60a8602aa/" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24"><path d="M6.94 8.5H3.56V20h3.38V8.5zM5.25 3.5a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92zM20.44 13.1c0-3.1-1.65-4.85-4.2-4.85-1.9 0-2.75 1.05-3.22 1.78V8.5H9.65c.04 1 0 11.5 0 11.5h3.37v-6.42c0-.34.03-.68.13-.92.27-.68.9-1.38 1.94-1.38 1.37 0 1.92 1.04 1.92 2.57V20h3.38l.05-6.9z" fill="currentColor"/></svg></a>
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
			</div>
			<div class="foot-col"><h2 class="foot-h">Contact</h2>
				<h3 class="foot-sub">Phone &amp; text 24/7</h3>
				<a href="<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $phone ); ?></a>
				<a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
				<h4 class="foot-sub">Office</h4>
				<address class="foot-addr">1914 Weeping Cypress Dr<br>Charleston, SC 29412</address>
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
	var nav=document.getElementById('nav'),bar=document.getElementById('progress'),sticky=document.getElementById('stickyQuote'),stx=document.getElementById('stickyText');
	var docH=0,tick=false;function measure(){docH=document.documentElement.scrollHeight-innerHeight;}
	function onScroll(){tick=false;var y=window.scrollY;nav.classList.toggle('scrolled',y>40);bar.style.transform='scaleX('+(docH>0?Math.min(y/docH,1):0)+')';sticky.classList.toggle('show',y>innerHeight*.7);stx.classList.toggle('show',y>innerHeight*.7);}
	function req(){if(!tick){tick=true;requestAnimationFrame(onScroll);}}
	addEventListener('scroll',req,{passive:true});addEventListener('resize',function(){measure();req();},{passive:true});addEventListener('load',function(){measure();req();});measure();onScroll();


	(function(){
		var v=document.getElementById('heroVideo'),st=document.querySelector('.stage');
		if(!v||matchMedia('(prefers-reduced-motion: reduce)').matches)return;
		if(v.paused&&!v.ended&&v.readyState>2){var p=v.play();if(p&&p.catch)p.catch(function(){});}
	})();
	var hq=document.getElementById('heroQuote');if(hq)hq.addEventListener('submit',function(e){
		e.preventDefault();
		var map={hqPickup:'mqf-pickup',hqDrop:'mqf-dropoff',hqDate:'mqf-date',hqPax:'mqf-pax'};
		for(var k in map){var s=document.getElementById(k),t=document.getElementById(map[k]);if(s&&t&&s.value)t.value=s.value;}
		document.getElementById('quote').scrollIntoView({behavior:'smooth'});
		setTimeout(function(){var el=document.getElementById('mqf-time');if(el)el.focus({preventScroll:true});},700);
	});

	if('IntersectionObserver' in window){
		var io=new IntersectionObserver(function(es){es.forEach(function(en){if(en.isIntersecting){en.target.classList.add('is-in');io.unobserve(en.target);}});},{threshold:.12,rootMargin:'0px 0px -8% 0px'});
		['.sec-head','.rev-badge','.story-copy','.story-photo','.grid3','.svc-grid','.tl','.faq-list','.quote-title','.rev-cta'].forEach(function(sel){
			document.querySelectorAll(sel).forEach(function(c){
				var kids=c.matches('.grid3,.svc-grid,.tl')?[].slice.call(c.children):[c];
				kids.forEach(function(el,i){el.classList.add('will-reveal');el.style.transitionDelay=(c.classList.contains('fleet-pop')?i*180:i%5*90)+'ms';io.observe(el);});
			});
		});
	}

	if('IntersectionObserver' in window){
		var po=new IntersectionObserver(function(es){es.forEach(function(en){en.target.classList.toggle('anim-off',!en.isIntersecting);});});
		document.querySelectorAll('.strip,.marquee,.rev-marquee,.story-photo,.pg-hero-photo,.hero-quote,.stats').forEach(function(el){po.observe(el);});
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
