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

$services = array(
	array( 'Weddings', '/wedding/', 'On-time, on-theme rides for the couple, the wedding party and guests.' ),
	array( 'Airport Transfers', '/airport/', 'Pickups and drop-offs at Charleston International (CHS), with flight tracking.' ),
	array( 'Night Out & Party Bus', '/night-out/', 'Birthdays, bachelorette weekends, concerts. Everyone gets home safely.' ),
	array( 'Corporate', '/corporate/', 'Discreet chauffeurs and clean billing for executives and clients.' ),
	array( 'Events & Prom', '/events/', 'Proms, galas and celebrations, arriving together in style.' ),
	array( 'Sightseeing & Tours', '/attractions/', 'Historic landmarks, plantations, museums and custom Charleston tours.' ),
	array( 'Beach Trips', '/beach/', 'Folly Beach, Isle of Palms, Sullivan\'s Island and Kiawah.' ),
	array( 'Golf Courses', '/golf-courses/', 'Kiawah, Wild Dunes and the Lowcountry\'s best courses.' ),
	array( 'Hotel Transfers', '/hotels/', 'Door-to-door service to and from Charleston hotels and rentals.' ),
	array( 'Cruise Port', '/cruise-trips/', 'Transfers to and from the Charleston cruise terminal.' ),
);

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
	array( 'How far in advance should I book?', 'As early as you can for weddings and peak weekends. We also handle last-minute and same-day requests whenever a vehicle is available. Just call ' . $phone . ', 24/7.' ),
	array( 'What areas do you serve?', 'Charleston and the wider Lowcountry, including Mount Pleasant, North Charleston, Kiawah Island, Seabrook Island, Isle of Palms, Folly Beach, Sullivan\'s Island, Summerville and Georgetown.' ),
	array( 'Which vehicles are in your fleet?', 'A Mercedes-Benz Sprinter for larger groups, luxury SUVs such as the Cadillac Escalade, Chevrolet Suburban and GMC Yukon Denali, and an executive sedan for airport runs and business travel. Tell us your group size and we will match the right vehicle.' ),
	array( 'Do you handle airport transfers?', 'Yes. We pick up and drop off at Charleston International Airport (CHS) and the area\'s private aviation terminals. Share your flight number and your chauffeur will track your arrival.' ),
	array( 'Are you available 24/7?', 'Yes. Mecca Limo is available around the clock, every day, including early-morning airport runs and late nights out.' ),
	array( 'How do I get a price?', 'Fill out the quote form on this page or call ' . $phone . '. We will send pricing and availability for your trip, with no obligation.' ),
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0a0a0b">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php if ( $is_home ) : ?><link rel="preload" as="image" href="<?php echo esc_url( $a( 'hero-fleet.webp' ) ); ?>" imagesrcset="<?php echo esc_url( $a( 'hero-fleet-m.webp' ) ); ?> 800w, <?php echo esc_url( $a( 'hero-fleet.webp' ) ); ?> 1600w" imagesizes="100vw" fetchpriority="high"><?php endif; ?>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@300;400;500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@300;400;500;600&display=swap"></noscript>
<?php wp_head(); ?>
<style>
:root{--ink:#0a0a0b;--ink-soft:#141416;--panel:#1a1a1d;--gold:#c9a34e;--gold-bright:#e2c274;--line:rgba(201,163,78,.22);--paper:#f3efe6;--muted:#aaa494}
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
.btn-gold{position:relative;overflow:hidden;background:linear-gradient(135deg,var(--gold),var(--gold-bright));color:var(--ink)!important;font-weight:500}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(201,163,78,.3)}
.btn-ghost{border-color:rgba(243,239,230,.3);color:var(--paper)}
.btn-ghost:hover{border-color:var(--gold);color:var(--gold-bright)}
.btn-gold::before,.hq-btn::before{content:"";position:absolute;top:0;left:-120%;width:60%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.55),transparent);transform:skewX(-18deg)}
.btn-gold:hover::before,.hq-btn:hover::before{animation:sheen .9s ease}
@keyframes sheen{from{left:-120%}to{left:150%}}

/* nav */
.mh-page .mh-nav{background:rgba(10,10,11,.97);border-bottom:1px solid var(--line)}
.mh-page #quote{padding-top:150px}
.mh-page main{display:flex;flex-direction:column}
.mh-page main>#quote{order:-1}
#progress{position:fixed;top:0;left:0;height:2px;width:0;z-index:100;background:linear-gradient(90deg,var(--gold),var(--gold-bright),#fff6df);box-shadow:0 0 12px rgba(226,194,116,.7)}
.mh-nav{position:fixed;top:0;left:0;right:0;z-index:50;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:24px;padding:14px 40px;background:linear-gradient(to bottom,rgba(10,10,11,.92),rgba(10,10,11,0));transition:background .4s,padding .4s}
.mh-nav.scrolled{background:rgba(10,10,11,.94);backdrop-filter:blur(14px) saturate(120%);-webkit-backdrop-filter:blur(14px);padding:8px 40px;border-bottom:1px solid var(--line)}
.brand{grid-column:2;justify-self:center}
.brand img{height:62px;width:auto;transition:height .4s}
.nav-left{grid-column:1;justify-self:start}
.nav-right{grid-column:3;justify-self:end}
.nav-tel{display:none;grid-column:1;justify-self:start;width:42px;height:42px;border:1px solid var(--line);border-radius:50%;align-items:center;justify-content:center;color:var(--gold-bright)}
.nav-tel svg{width:18px;height:18px;fill:currentColor}
.burger{grid-column:3;justify-self:end}
.mh-nav.scrolled .brand img{height:48px}
.nav-links{display:flex;gap:30px;align-items:center}
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
.mobile-menu .mm-cta{margin-top:14px;text-align:center;background:linear-gradient(135deg,var(--gold),var(--gold-bright));color:var(--ink);border:0;border-radius:10px;padding:16px;font-family:'Jost',sans-serif;font-size:.9rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase}
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
.hero-aurora{position:absolute;inset:-25%;z-index:2;pointer-events:none;mix-blend-mode:screen;filter:blur(22px);background:radial-gradient(38% 42% at 28% 72%,rgba(201,163,78,.28),transparent 60%),radial-gradient(30% 34% at 74% 38%,rgba(226,194,116,.18),transparent 60%);animation:aurora 22s ease-in-out infinite alternate}
@keyframes aurora{0%{transform:translate3d(-4%,3%,0) scale(1)}50%{transform:translate3d(5%,-3%,0) scale(1.14)}100%{transform:translate3d(-2%,5%,0) scale(1.06)}}
.hero-inner{position:relative;z-index:3;width:100%}
.hero.hero-v{display:block;min-height:0;padding:0 0 64px}
.stage{position:relative;height:min(56.25vw,80vh);overflow:hidden;background:#0a0a0b}
.stage img,.stage video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.stage video{opacity:0;transition:opacity .5s}
.stage video.on{opacity:1}
.stage::after{content:"";position:absolute;left:0;right:0;bottom:0;height:34%;background:linear-gradient(rgba(10,10,11,0),#0a0a0b);z-index:2}
.hero-v .hero-inner{margin-top:-2vw}
.mh .hero-v h1{font-size:clamp(2.4rem,4.6vw,4.2rem);max-width:20ch}
.hero-inner>*{animation:rise .9s cubic-bezier(.2,.7,.2,1) both}
.hero-inner>:nth-child(2){animation-delay:.12s}
.hero-inner>:nth-child(3){animation-delay:.24s}
.hero-inner>:nth-child(4){animation-delay:.36s}
@keyframes rise{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:none}}
.mh .hero h1{font-size:clamp(2.6rem,6.2vw,5.2rem);color:var(--paper);margin-bottom:18px;max-width:15ch}
.hero h1 em{font-style:italic;background:linear-gradient(100deg,var(--gold) 0%,#fff7e0 28%,var(--gold-bright) 50%,var(--gold) 78%);background-size:220% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent;animation:shine 6s linear infinite}
@keyframes shine{to{background-position:-220% center}}
.lede{max-width:520px;font-size:1.06rem;color:#d8d2c6;margin-bottom:28px}
.hero-quote{position:relative;overflow:hidden;display:grid;grid-template-columns:1fr 1fr;gap:14px;align-items:end;max-width:860px;background:rgba(12,12,14,.62);backdrop-filter:blur(12px) saturate(120%);-webkit-backdrop-filter:blur(12px);border:1px solid var(--line);border-radius:16px;padding:20px;box-shadow:0 26px 60px -30px rgba(0,0,0,.75)}
.hero-quote::after{content:"";position:absolute;top:0;left:-45%;width:45%;height:1px;background:linear-gradient(90deg,transparent,var(--gold-bright),transparent);animation:qscan 5s linear infinite}
@keyframes qscan{0%{left:-45%}100%{left:100%}}
.hq-field:nth-child(1),.hq-field:nth-child(2),.hq-btn{grid-column:1/-1}
.hq-field{display:flex;flex-direction:column;gap:7px;min-width:0}
.hq-field label{font-size:.62rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-bright)}
.hq-field input{width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(226,194,116,.28);border-radius:8px;padding:11px 12px;color:var(--paper);font-family:'Jost',sans-serif;font-size:16px;color-scheme:dark}
.hq-field input::placeholder{color:#8f897b}
.hq-field input:focus{outline:none;border-color:var(--gold-bright);box-shadow:0 0 0 3px rgba(226,194,116,.16)}
.hq-btn{position:relative;overflow:hidden;white-space:nowrap;background:linear-gradient(135deg,var(--gold),var(--gold-bright));color:var(--ink);border:none;border-radius:9px;padding:0 26px;height:46px;font-family:'Jost',sans-serif;font-weight:600;letter-spacing:.08em;text-transform:uppercase;font-size:.82rem;cursor:pointer;transition:transform .3s,box-shadow .3s}
.hq-btn:hover{transform:translateY(-2px);box-shadow:0 16px 34px -12px rgba(226,194,116,.55)}
@media(min-width:860px){.hero-quote{grid-template-columns:1.25fr 1.25fr 1fr .8fr auto;gap:12px;padding:18px}.hq-field:nth-child(1),.hq-field:nth-child(2),.hq-btn{grid-column:auto}}
.hero-links{margin-top:18px;display:flex;gap:22px;flex-wrap:wrap;font-size:.86rem;color:#d8d2c6}
.hero-links a:hover{color:var(--gold-bright)}
.hero-mcta{display:none}

/* strip */
.strip{position:relative;border-top:1px solid var(--line);border-bottom:1px solid var(--line);background:var(--ink-soft)}
.strip::before{content:"";position:absolute;top:-1px;left:0;height:1px;width:100%;background:linear-gradient(90deg,transparent,var(--gold),transparent);background-size:50% 100%;background-repeat:no-repeat;animation:scan 6s linear infinite}
@keyframes scan{0%{background-position:-60% 0}100%{background-position:160% 0}}
.strip .wrap{display:flex;flex-wrap:wrap;justify-content:space-between;gap:24px;padding:32px}
.stat b{font-family:'Cormorant Garamond',serif;font-size:2.2rem;color:var(--gold);display:block;line-height:1;font-weight:600;transition:text-shadow .4s}
.strip:hover .stat b{text-shadow:0 0 22px rgba(226,194,116,.45)}
.stat span{font-size:.74rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}

/* sections */
.mh section{padding:110px 0}
.sec-head{max-width:680px;margin-bottom:56px}
.sec-head.center{text-align:center;margin-left:auto;margin-right:auto}
.sec-head:hover .eyebrow::before{width:70px}
.mh .sec-head h2{font-size:clamp(2.1rem,4.4vw,3.3rem);color:var(--paper)}
.sec-head p{color:var(--muted);margin-top:16px;font-size:1.02rem}
.alt{background:var(--ink-soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}

/* fleet */
.grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.car,.rev-card,.step{background:var(--panel);border:1px solid rgba(255,255,255,.05);border-radius:4px;overflow:hidden;transition:border-color .4s,transform .4s,box-shadow .4s}
.car:hover,.rev-card:hover,.step:hover{border-color:var(--gold);transform:translateY(-6px);box-shadow:0 24px 60px -20px rgba(0,0,0,.7),0 0 40px -12px rgba(201,163,78,.35)}
.car-img{height:230px;overflow:hidden;position:relative}
.car-img img{width:100%;height:100%;object-fit:cover;animation:kenburns 15s ease-in-out infinite alternate;transition:filter .5s}
.car:nth-child(2) .car-img img{animation-duration:18s}
.car:nth-child(3) .car-img img{animation-duration:13s}
@keyframes kenburns{0%{transform:scale(1.05)}100%{transform:scale(1.12) translate3d(-2.5%,-1.5%,0)}}
.car-img::before{content:"";position:absolute;inset:0;z-index:3;pointer-events:none;background:linear-gradient(115deg,transparent 42%,rgba(255,255,255,.4) 50%,transparent 58%);transform:translateX(-130%)}
.car:hover .car-img::before{animation:sweep .85s ease}
@keyframes sweep{to{transform:translateX(130%)}}
.car:hover .car-img img{filter:brightness(1.09) contrast(1.03)}
.car-tag{position:absolute;top:14px;left:14px;z-index:2;font-size:.66rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-bright);background:rgba(10,10,11,.7);border:1px solid var(--line);padding:5px 11px;border-radius:2px}
.car-body{padding:26px}
.mh .car-body h3{font-size:1.7rem;color:var(--paper);margin-bottom:6px}
.seats{font-size:.76rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:12px}
.car-body p,.step p{font-size:.95rem;color:var(--muted)}

/* services */
.svc-grid{display:grid;grid-template-columns:repeat(5,1fr);border-top:1px solid rgba(255,255,255,.05);border-left:1px solid rgba(255,255,255,.05)}
.svc{position:relative;display:block;padding:30px 26px;border-right:1px solid rgba(255,255,255,.05);border-bottom:1px solid rgba(255,255,255,.05);transition:background .35s}
.svc:hover{background:var(--panel)}
.svc::before{content:"";position:absolute;left:0;top:0;width:2px;height:0;background:linear-gradient(var(--gold),var(--gold-bright));transition:height .4s ease}
.svc:hover::before{height:100%}
.mh .svc h3{font-size:1.4rem;color:var(--paper)}
.svc p{color:var(--muted);font-size:.9rem;margin-top:8px}
.svc .go{display:inline-block;margin-top:12px;font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-bright)}

/* steps */
.step{padding:40px 34px}
.step-num{font-family:'Cormorant Garamond',serif;font-size:2.8rem;color:var(--gold);display:block;line-height:1;margin-bottom:16px}
.mh .step h3{font-size:1.6rem;color:var(--paper);margin-bottom:8px}

/* story */
.story-grid{display:grid;grid-template-columns:1.1fr 1fr;gap:60px;align-items:center}
.mh .story h2{font-size:clamp(2.1rem,4.2vw,3.1rem);color:var(--paper);margin-bottom:22px}
.story p{color:#c9c4b8;font-size:1.05rem;margin-bottom:16px}
.story .initial{font-family:'Cormorant Garamond',serif;color:var(--gold);float:left;font-size:4.2rem;line-height:.8;margin:6px 14px 0 0}
.story .sig{color:var(--gold-bright);font-family:'Cormorant Garamond',serif;font-size:1.5rem;margin:6px 0 22px}
.story-photo{border:1px solid var(--line);border-radius:4px;overflow:hidden;height:400px}
.story-photo img{width:100%;height:100%;object-fit:cover}

/* reviews */
.rev-badge{display:flex;align-items:center;justify-content:center;gap:12px;margin:-30px auto 50px;color:var(--muted);font-size:.92rem;flex-wrap:wrap}
.rev-badge .g-stars,.rev-stars{color:var(--gold-bright);letter-spacing:2px}
.rev-badge b{color:var(--paper);font-weight:500}
.rev-card{padding:30px;display:flex;flex-direction:column}
.rev-stars{font-size:.95rem;margin-bottom:14px}
.rev-text{color:#d3cec2;font-size:.96rem;line-height:1.65;flex:1;margin-bottom:22px}
.rev-person{display:flex;align-items:center;gap:14px}
.rev-avatar{width:44px;height:44px;flex:none;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-bright));color:var(--ink);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-weight:600;font-size:1.15rem;transition:transform .4s,box-shadow .4s}
.rev-card:hover .rev-avatar{transform:scale(1.08);box-shadow:0 0 22px rgba(226,194,116,.5)}
.rev-name{color:var(--paper);font-size:.94rem}
.rev-tag{color:var(--gold);font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;margin-top:2px}
.rev-cta{text-align:center;margin-top:50px}
.rev-cta a{margin:0 8px}

/* quote (white) */
.mh section.quote-white{position:relative;background:radial-gradient(900px 520px at 50% 0%,rgba(201,163,78,.10),transparent 65%),var(--ink-soft);color:var(--paper);padding:clamp(90px,12vw,150px) 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.mh .quote-title{font-weight:600;text-align:center;font-size:clamp(3.2rem,9.5vw,7.4rem);line-height:.92;margin-bottom:18px;background:linear-gradient(100deg,var(--gold) 0%,#fff7e0 28%,var(--gold-bright) 50%,var(--gold) 78%);background-size:220% auto;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:var(--gold-bright);animation:shine 8s linear infinite}
.quote-sub{text-align:center;max-width:560px;margin:0 auto 50px;color:#d8d2c6;font-size:1.08rem;font-weight:300}
#quote .mqf>h2,#quote .mqf>.mqf-sub{display:none}
#quote .mqf-trust{max-width:920px;margin:0 auto 22px;gap:14px}
#quote .mqf-trust div{background:var(--panel);border:1px solid var(--line);border-radius:12px;color:var(--muted);font-family:'Jost',sans-serif;font-size:.95rem;padding:16px 8px}
#quote .mqf-trust b{color:var(--gold-bright);font-size:1.05rem;font-weight:500}
#quote .mqf-trust svg{stroke:var(--gold-bright);width:30px;height:30px}
#quote .mqf{max-width:920px;background:rgba(20,20,22,.92);border:1px solid var(--gold);border-radius:16px;padding:clamp(26px,5vw,56px);color:var(--paper);box-shadow:0 34px 80px -34px rgba(0,0,0,.8),0 0 50px -18px rgba(201,163,78,.35);font-family:'Jost',sans-serif}
#quote .mqf label{color:var(--gold-bright);font-weight:500;font-size:clamp(.98rem,1.5vw,1.08rem);letter-spacing:.02em;margin:20px 0 9px}
#quote .mqf input[type=number],#quote .mqf input[type=text],#quote .mqf input[type=email],#quote .mqf input[type=tel],#quote .mqf input[type=date],#quote .mqf input[type=time],#quote .mqf select,#quote .mqf textarea{background:rgba(255,255,255,.04);color:var(--paper);border:1px solid rgba(226,194,116,.35);border-radius:10px;padding:14px 16px;font-family:'Jost',sans-serif;font-size:1.05rem;font-weight:400;color-scheme:dark}
#quote .mqf select option{background:#141416;color:var(--paper)}
#quote .mqf input::placeholder,#quote .mqf textarea::placeholder{color:#8f897b;font-weight:300}
#quote .mqf input:focus,#quote .mqf select:focus,#quote .mqf textarea:focus{border-color:var(--gold-bright);box-shadow:0 0 0 3px rgba(226,194,116,.18)}
#quote .mqf .mqf-row{gap:26px}
#quote .mqf .mqf-check{font-weight:300;color:#d8d2c6;font-size:1rem;margin-top:16px}
#quote .mqf .mqf-check input{accent-color:var(--gold);width:20px;height:20px}
#quote .mqf .mqf-addstop{color:var(--gold-bright);text-decoration:underline;text-underline-offset:3px;font-size:1rem}
#quote .mqf button[type=submit]{position:relative;overflow:hidden;background:linear-gradient(135deg,var(--gold),var(--gold-bright));color:var(--ink);border-radius:10px;padding:22px;font-family:'Jost',sans-serif;font-size:clamp(1.05rem,2vw,1.3rem);font-weight:600;letter-spacing:.1em;margin-top:28px;transition:transform .3s,box-shadow .3s}
#quote .mqf button[type=submit]::before{content:"";position:absolute;top:0;left:-120%;width:60%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.55),transparent);transform:skewX(-18deg)}
#quote .mqf button[type=submit]:hover{background:linear-gradient(135deg,var(--gold),var(--gold-bright));transform:translateY(-2px);box-shadow:0 18px 40px -12px rgba(226,194,116,.55)}
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
.foot-col a,.foot-col span{display:block;color:var(--muted);font-size:.94rem;margin-bottom:10px;transition:color .25s}
.foot-col a:hover{color:var(--gold-bright)}
.foot-social{display:flex;gap:12px;margin-top:20px}
.foot-social a{width:40px;height:40px;border:1px solid var(--line);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--gold-bright);transition:all .3s}
.foot-social a:hover{background:var(--gold);color:var(--ink);border-color:var(--gold)}
.foot-social svg{width:18px;height:18px;fill:currentColor}
.foot-bottom{border-top:1px solid rgba(255,255,255,.06);padding-top:24px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:14px;font-size:.84rem;color:var(--muted)}
.foot-bottom a:hover{color:var(--gold-bright)}

#stickyQuote{position:fixed;left:24px;bottom:24px;z-index:60;background:linear-gradient(135deg,var(--gold),var(--gold-bright));color:var(--ink);border-radius:50px;padding:15px 26px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;font-size:.8rem;box-shadow:0 14px 34px -10px rgba(0,0,0,.65);opacity:0;transform:translateY(24px);pointer-events:none;transition:opacity .4s,transform .4s}
#stickyQuote.show{opacity:1;transform:none;pointer-events:auto}
body.mh::after{content:"";position:fixed;inset:0;z-index:999;pointer-events:none;opacity:.035;mix-blend-mode:overlay;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E")}
.will-reveal{opacity:0;transform:translateY(24px);transition:opacity .7s cubic-bezier(.2,.7,.2,1),transform .7s cubic-bezier(.2,.7,.2,1)}
.will-reveal.is-in{opacity:1;transform:none}
.fleet-pop .car-img img{animation:none}
.fleet-pop>.will-reveal{transform:translateY(90px) scale(.86);transition:opacity .6s ease,transform 1s cubic-bezier(.34,1.56,.64,1)}
.fleet-pop>.will-reveal.is-in{transform:none}
.fleet-pop>.will-reveal .car-img img{transform:scale(1.3);transition:transform 1.2s cubic-bezier(.2,.7,.2,1) .15s}
.fleet-pop>.will-reveal.is-in .car-img img{transform:scale(1.05)}

@media(max-width:1100px){.svc-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:900px){
.nav-links{display:none}.burger{display:flex}.nav-tel{display:flex}
.grid3,.story-grid{grid-template-columns:1fr}
.svc-grid{grid-template-columns:1fr 1fr}
.story-photo{height:280px;order:-1}
.foot-grid{grid-template-columns:1fr 1fr}
.mh section{padding:80px 0}.wrap{padding:0 22px}.mh-nav,.mh-nav.scrolled{padding:10px 22px}
.brand img{height:46px}.mh-nav.scrolled .brand img{height:40px}
.hero{min-height:0;padding:118px 0 44px}
.hero.hero-v{padding:0 0 44px}
.stage{height:56.25vw;margin-top:64px}
.hero-v .hero-inner{margin-top:8px}
.hero-aurora,.hero-overlay+.hero-aurora{display:none}
body.mh::after{display:none}
.car-img img,.hero-quote::after,.strip::before{animation:none}
.hero-quote,.hero-links{display:none}
.hero-mcta{display:flex;flex-direction:column;gap:12px;max-width:420px}
.hero-mcta .btn{text-align:center;padding:17px 20px;font-size:.85rem;border-radius:10px}
.hero-mcta .btn-ghost{background:rgba(10,10,11,.45)}
.lede{margin-bottom:24px}
.mh main{display:flex;flex-direction:column}
.mh main>#quote{order:-1}
.mh section.quote-white{padding:48px 0 60px}
.mh-page #quote.quote-white{padding-top:104px}
.quote-sub{margin-bottom:26px}
}
@media(max-width:560px){
.wrap{padding:0 16px}
.mh section{padding:64px 0}
.svc-grid{grid-template-columns:1fr}
.foot-grid{grid-template-columns:1fr}
.strip .wrap{gap:18px 26px;padding:24px 16px}
.stat b{font-size:1.8rem}
#quote .mqf{padding:22px 18px;border-radius:12px}
.mh .quote-title{font-size:3rem;margin-bottom:12px}
#quote .mqf-trust{gap:8px;margin-bottom:16px}
#quote .mqf-trust div{padding:12px 4px;font-size:.78rem}
#quote .mqf-trust b{font-size:.9rem}
#quote .mqf label{margin:16px 0 7px}
#quote .mqf .mqf-row{gap:0}
.rev-cta a{display:block;margin:0 auto 12px;max-width:320px}
#stickyQuote{right:auto;left:14px;bottom:18px;padding:13px 20px}
.eyebrow{letter-spacing:.22em;font-size:.66rem}
.eyebrow::before{width:28px}
}
@media(prefers-reduced-motion:reduce){
*,*::before,*::after{animation:none!important;transition:none!important}
.will-reveal{opacity:1!important;transform:none!important}
.hero h1 em{-webkit-text-fill-color:var(--gold-bright);color:var(--gold-bright)}
}
</style>
<script type="application/ld+json"><?php
echo wp_json_encode( array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array_map( function ( $f ) {
		return array( '@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ) );
	}, $faqs ),
) );
?></script>
</head>
<body <?php body_class( $is_home ? 'mh' : 'mh mh-page' ); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main">Skip to content</a>
<div id="progress" aria-hidden="true"></div>
<a href="#quote" id="stickyQuote">Get a Quote</a>

<nav class="mh-nav" id="nav" aria-label="Main">
	<div class="nav-links nav-left">
		<a href="<?php echo $h; ?>#fleet">Fleet</a>
		<a href="<?php echo $h; ?>#services">Services</a>
		<a href="#reviews">Reviews</a>
		<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
	</div>
	<a href="<?php echo esc_attr( $tel ); ?>" class="nav-tel" aria-label="Call <?php echo esc_attr( $phone ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2Z"/></svg></a>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand"><img src="<?php echo esc_url( $logo ); ?>" width="240" height="114" alt="Mecca Limo Chauffeur Service"></a>
	<div class="nav-links nav-right">
		<a href="#quote">Get a Quote</a>
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
		<img src="<?php echo esc_url( $a( 'hero-fleet.webp' ) ); ?>" srcset="<?php echo esc_url( $a( 'hero-fleet-m.webp' ) ); ?> 800w, <?php echo esc_url( $a( 'hero-fleet.webp' ) ); ?> 1600w" sizes="100vw" width="1600" height="900" alt="" fetchpriority="high">
		<video id="heroVideo" muted playsinline loop preload="none" data-d="<?php echo esc_url( $a( 'hero-fleet-720' ) ); ?>" data-m="<?php echo esc_url( $a( 'hero-fleet-480' ) ); ?>"></video>
	</div>
	<div class="hero-aurora" aria-hidden="true"></div>
	<div class="wrap hero-inner">
		<span class="eyebrow">Charleston, SC · Available 24/7</span>
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
			<div class="hero-mcta"><a href="#quote" class="btn btn-gold">Get a Quote</a><a href="<?php echo esc_attr( $tel ); ?>" class="btn btn-ghost">Call <?php echo esc_html( $phone ); ?></a></div>
			<div class="hero-links"><a href="<?php echo esc_attr( $tel ); ?>">Call <?php echo esc_html( $phone ); ?></a><a href="<?php echo $h; ?>#fleet">View our fleet →</a></div>
		</div>
	</div>
</header>
<?php endif; ?>

<main id="main">
<div class="strip">
	<div class="wrap">
		<div class="stat"><b>5.0 ★</b><span>Google rating</span></div>
		<div class="stat"><b>24/7</b><span>Always available</span></div>
		<div class="stat"><b>Family</b><span>Owned &amp; operated</span></div>
		<div class="stat"><b>Licensed</b><span>&amp; fully insured</span></div>
	</div>
</div>

<?php if ( $is_home ) : ?>
<section id="fleet">
	<div class="wrap">
		<div class="sec-head">
			<span class="eyebrow">Our Fleet</span>
			<h2>Black car and limo service in Charleston, SC.</h2>
			<p>Late-model, professionally maintained and detailed before every ride. Choose the vehicle that fits your group and your occasion.</p>
		</div>
		<div class="grid3 fleet-pop">
			<div class="car">
				<div class="car-img"><span class="car-tag">Sprinter</span><img loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sprinter.webp' ) ); ?>" width="800" height="450" alt="Black Mercedes-Benz Sprinter limo van by Mecca Limo in Charleston"></div>
				<div class="car-body"><h3>Mercedes Sprinter</h3><div class="seats">Seats up to 14 with luggage</div><p>The choice for wedding parties, corporate groups and bachelorette weekends.</p></div>
			</div>
			<div class="car">
				<div class="car-img"><span class="car-tag">SUV</span><img loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-suv.webp' ) ); ?>" width="800" height="446" alt="Black Cadillac Escalade SUV limo in Charleston SC"></div>
				<div class="car-body"><h3>Luxury SUV</h3><div class="seats">Seats up to 6 with luggage</div><p>Cadillac Escalade, Chevrolet Suburban and GMC Yukon Denali. Room for the group and every bag.</p></div>
			</div>
			<div class="car">
				<div class="car-img"><span class="car-tag">Sedan</span><img loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'fleet-sedan.webp' ) ); ?>" width="800" height="437" alt="Black executive sedan for airport and business travel in Charleston"></div>
				<div class="car-body"><h3>Executive Sedan</h3><div class="seats">Seats up to 3 with 2 bags</div><p>Quiet and private for airport runs and business travel. Always on time.</p></div>
			</div>
		</div>
	</div>
</section>

<section class="alt" id="services">
	<div class="wrap">
		<div class="sec-head">
			<span class="eyebrow">What We Do</span>
			<h2>One call for every kind of trip.</h2>
		</div>
		<div class="svc-grid">
			<?php foreach ( $services as $s ) : ?>
			<a class="svc" href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><h3><?php echo esc_html( $s[0] ); ?></h3><p><?php echo esc_html( $s[2] ); ?></p><span class="go">Explore <?php echo esc_html( strtolower( $s[0] ) ); ?> →</span></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section id="how">
	<div class="wrap">
		<div class="sec-head">
			<span class="eyebrow">How it works</span>
			<h2>Booked in three easy steps.</h2>
		</div>
		<div class="grid3">
			<div class="step"><span class="step-num">01</span><h3>Request a quote</h3><p>Tell us your pickup, destination, date and group size. It takes under a minute.</p></div>
			<div class="step"><span class="step-num">02</span><h3>We confirm your ride</h3><p>We reply quickly with pricing, the right vehicle for your group, and your professional chauffeur.</p></div>
			<div class="step"><span class="step-num">03</span><h3>Arrive in style</h3><p>Your chauffeur arrives early, tracks your flight when needed, and gets you there safely and on time.</p></div>
		</div>
	</div>
</section>

<section class="alt story" id="story">
	<div class="wrap story-grid">
		<div class="story-copy">
			<span class="eyebrow">Our Story</span>
			<h2>A family business, built on trust.</h2>
			<p><span class="initial">M</span>ecca Limo was created with a simple mission: to give Charleston a more convenient, reliable and luxurious way to get around. As a family-owned business, our clients are at the center of everything we do.</p>
			<p>From 4 a.m. airport runs to wedding weekends and corporate events across the Lowcountry, we show up early, drive clean vehicles, and get you there safely. That is the whole promise.</p>
			<div class="sig">Moe &amp; the Mecca team</div>
			<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Learn more about Mecca Limo</a>
		</div>
		<div class="story-photo"><img loading="lazy" decoding="async" src="<?php echo esc_url( $a( 'story.webp' ) ); ?>" width="900" height="700" alt="Mecca Limo branded Mercedes-Benz Sprinter at sunset"></div>
	</div>
</section>
<?php endif; ?>

<section id="reviews">
	<div class="wrap">
		<div class="sec-head center">
			<span class="eyebrow">What our clients say</span>
			<h2>Charleston keeps coming back.</h2>
		</div>
		<div class="rev-badge"><span class="g-stars">★★★★★</span><span><b>5.0</b> from <b>140+</b> reviews on Google</span></div>
		<div class="grid3">
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
		<div class="rev-cta">
			<a href="https://www.google.com/search?q=Mecca+Limo+Charleston+reviews" target="_blank" rel="noopener" class="btn btn-gold">Read all reviews on Google</a>
		</div>
	</div>
</section>

<section id="quote" class="quote-white">
	<div class="wrap">
		<?php if ( $is_home ) : ?><h2 class="quote-title">Get a Quote</h2><?php else : ?><h1 class="quote-title">Get a Quote</h1><?php endif; ?>
		<p class="quote-sub">Tell us about your trip and we'll get back to you with prices and availability.</p>
		<?php echo do_shortcode( '[mecca_quote_form]' ); ?>
	</div>
</section>

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
</main>

<footer class="mh-foot">
	<div class="wrap">
		<div class="foot-grid">
			<div class="foot-brand">
				<img src="<?php echo esc_url( $logo ); ?>" width="240" height="114" alt="Mecca Limo" loading="lazy">
				<p>A family-owned chauffeur service with our clients at the center of it all. Luxury vehicles and professional drivers, 24/7.</p>
				<div class="foot-social">
					<a href="https://www.facebook.com/profile.php?id=100090948969233" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H17V3.6c-.29-.04-1.28-.12-2.43-.12-2.4 0-4.07 1.47-4.07 4.17V9.9H7.8V13h2.7v8h3Z"/></svg></a>
					<a href="https://www.instagram.com/meccalimo/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s0 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.65.07-4.9.07s-3.63 0-4.9-.07c-3.26-.15-4.77-1.7-4.92-4.92C2.2 15.6 2.2 15.2 2.2 12s0-3.58.07-4.85C2.42 3.92 3.93 2.38 7.2 2.27 8.4 2.2 8.8 2.2 12 2.2Zm0 4.86A4.94 4.94 0 1 0 12 17a4.94 4.94 0 0 0 0-9.94Zm0 8.14a3.2 3.2 0 1 1 0-6.4 3.2 3.2 0 0 1 0 6.4Zm5.14-9.4a1.15 1.15 0 1 0 0 2.3 1.15 1.15 0 0 0 0-2.3Z"/></svg></a>
					<a href="https://x.com/meccalimo" target="_blank" rel="noopener" aria-label="X"><svg viewBox="0 0 24 24"><path d="M17.8 3h3.1l-6.8 7.7L22 21h-6.2l-4.9-6.3L5.3 21H2.2l7.2-8.3L1.8 3h6.4l4.4 5.8L17.8 3Zm-1.1 16.2h1.7L7.4 4.7H5.6l11.1 14.5Z"/></svg></a>
					<a href="https://www.tiktok.com/@meccalimo" target="_blank" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24"><path d="M16.5 3c.3 2.03 1.44 3.24 3.5 3.5v2.5c-1.2.12-2.25-.27-3.48-1.01v4.66c0 5.92-6.45 7.77-9.04 3.52-1.67-2.74-.64-7.55 4.73-7.74v2.63c-.41.07-.85.17-1.25.31-1.2.4-1.88 1.16-1.69 2.5.36 2.57 5.08 3.33 4.68-1.7V3h2.55Z"/></svg></a>
				</div>
			</div>
			<div class="foot-col"><p class="foot-h">Services</p>
				<?php foreach ( array_slice( $services, 0, 6 ) as $s ) : ?><a href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><?php echo esc_html( $s[0] ); ?></a><?php endforeach; ?>
			</div>
			<div class="foot-col"><p class="foot-h">Explore</p>
				<?php foreach ( array_slice( $services, 6 ) as $s ) : ?><a href="<?php echo esc_url( home_url( $s[1] ) ); ?>"><?php echo esc_html( $s[0] ); ?></a><?php endforeach; ?>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About us</a>
				<a href="<?php echo esc_url( home_url( '/policy/' ) ); ?>">Booking policy</a>
			</div>
			<div class="foot-col"><p class="foot-h">Contact</p>
				<a href="<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $phone ); ?></a>
				<a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
				<span>1914 Weeping Cypress Dr<br>Charleston, SC 29412</span>
				<a href="https://www.google.com/maps/dir/?api=1&amp;destination=Mecca+Limo+1914+Weeping+Cypress+Dr+Charleston+SC+29412" target="_blank" rel="noopener">Get directions →</a>
				<span>Open 24 hours, 7 days a week</span>
				<a href="<?php echo esc_url( home_url( '/get-a-quote/' ) ); ?>">Get a quote</a>
			</div>
		</div>
		<div class="foot-bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Mecca Limo Chauffeur Service. All rights reserved.</span><span>Serving Charleston, Mount Pleasant, Kiawah, Folly Beach &amp; the Lowcountry</span></div>
	</div>
</footer>

<script>
(function(){
	var nav=document.getElementById('nav'),bar=document.getElementById('progress'),sticky=document.getElementById('stickyQuote');
	function onScroll(){var y=window.scrollY;nav.classList.toggle('scrolled',y>40);var h=document.documentElement.scrollHeight-innerHeight;bar.style.width=(h>0?y/h*100:0)+'%';sticky.classList.toggle('show',y>innerHeight*.7);}
	addEventListener('scroll',onScroll,{passive:true});onScroll();

	addEventListener('load',function(){var v=document.getElementById('heroVideo');if(!v||matchMedia('(prefers-reduced-motion: reduce)').matches)return;setTimeout(function(){var ext=v.canPlayType('video/mp4; codecs="avc1.42E01E"')?'.mp4':'.webm';v.src=(innerWidth<900?v.dataset.m:v.dataset.d)+ext;v.addEventListener('playing',function(){v.classList.add('on');},{once:true});var p=v.play();if(p&&p.catch)p.catch(function(){});},600);});

	var hq=document.getElementById('heroQuote');if(hq)hq.addEventListener('submit',function(e){
		e.preventDefault();
		var map={hqPickup:'mqf-pickup',hqDrop:'mqf-dropoff',hqDate:'mqf-date',hqPax:'mqf-pax'};
		for(var k in map){var s=document.getElementById(k),t=document.getElementById(map[k]);if(s&&t&&s.value)t.value=s.value;}
		document.getElementById('quote').scrollIntoView({behavior:'smooth'});
		setTimeout(function(){var el=document.getElementById('mqf-time');if(el)el.focus({preventScroll:true});},700);
	});

	if('IntersectionObserver' in window){
		var io=new IntersectionObserver(function(es){es.forEach(function(en){if(en.isIntersecting){en.target.classList.add('is-in');io.unobserve(en.target);}});},{threshold:.12,rootMargin:'0px 0px -8% 0px'});
		['.sec-head','.rev-badge','.strip .wrap','.story-copy','.story-photo','.grid3','.svc-grid','.faq-list','.quote-title','.rev-cta'].forEach(function(sel){
			document.querySelectorAll(sel).forEach(function(c){
				var kids=c.matches('.grid3,.svc-grid,.strip .wrap')?[].slice.call(c.children):[c];
				kids.forEach(function(el,i){el.classList.add('will-reveal');el.style.transitionDelay=(c.classList.contains('fleet-pop')?i*180:i%5*90)+'ms';io.observe(el);});
			});
		});
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
