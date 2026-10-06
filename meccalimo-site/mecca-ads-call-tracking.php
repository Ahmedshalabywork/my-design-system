<?php
/**
 * Mecca Limo: Google Ads phone tracking on every front-end page.
 *
 * - "Phone number tap (website)" fires on taps of tel:/sms: links only (never on
 *   form submits, so a quote request is not counted twice).
 * - "Website calls 60s+" swaps the number shown to ad visitors for a Google
 *   forwarding number, so real calls of 60 seconds or more are counted.
 *
 * gtag.js loads on the first interaction or shortly after the page has loaded,
 * so it never delays the first paint. Events queued before then are sent once it loads.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}
	?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'AW-11250744864');
gtag('config', 'AW-11250744864/BcfNCJ2fyZMdEKD84vQp', {'phone_conversion_number': '(843) 804-1188'});
(function(w,d){
	var loaded=false,load=function(){if(loaded||w.meccaGtagLoaded)return;loaded=true;w.meccaGtagLoaded=true;var s=d.createElement('script');s.async=true;s.src='https://www.googletagmanager.com/gtag/js?id=AW-11250744864';d.head.appendChild(s);};
	['scroll','touchstart','pointerdown','mousemove','keydown','click','wheel'].forEach(function(x){w.addEventListener(x,load,{once:true,passive:true});});
	w.addEventListener('load',function(){setTimeout(load,2500);});
	d.addEventListener('click',function(ev){
		var a=ev.target.closest&&ev.target.closest('a[href^="tel:"],a[href^="sms:"]');
		if(a){gtag('event','conversion',{'send_to':'AW-11250744864/R5yzCJqfyZMdEKD84vQp'});load();}
	},true);
})(window,document);
</script>
	<?php
}, 98 );
