<?php
/**
 * Mecca Limo: fire the Google Ads "Quote form submitted" conversion only after a
 * genuine quote request. The quote form (mecca-quote-form) emails the office a
 * "New Quote Request:" message only after validation and spam checks pass, so
 * that email is the signal. The tag is printed in the footer of that same page view.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_mail', function ( $args ) {
	if ( isset( $args['subject'] ) && 0 === strpos( (string) $args['subject'], 'New Quote Request:' ) ) {
		$GLOBALS['mecca_ads_quote_sent'] = true;
	}
	return $args;
} );

add_action( 'wp_footer', function () {
	if ( empty( $GLOBALS['mecca_ads_quote_sent'] ) ) {
		return;
	}
	?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'AW-11250744864');
gtag('event', 'conversion', {'send_to': 'AW-11250744864/WbwNCIG255IdEKD84vQp'});
(function(d){var s=d.createElement('script');s.async=true;s.src='https://www.googletagmanager.com/gtag/js?id=AW-11250744864';d.head.appendChild(s);})(document);
</script>
	<?php
}, 99 );
