<?php
/**
 * Mecca Limo: keep the Google Ads click ID (gclid / gbraid / wbraid) from the ad visit
 * for 90 days and add it to the "New Quote Request" email, so booked rides can later
 * be uploaded to Google Ads as "Booked ride (offline)" conversions.
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
(function(){try{var p=new URLSearchParams(location.search),k=['gclid','gbraid','wbraid'];for(var i=0;i<k.length;i++){var v=p.get(k[i]);if(v&&/^[\w.-]{10,200}$/.test(v)){document.cookie='mecca_click='+k[i]+':'+v+'|'+Date.now()+';max-age=7776000;path=/;SameSite=Lax;Secure';break;}}}catch(e){}})();
</script>
	<?php
}, 97 );

add_filter( 'wp_mail', function ( $args ) {
	if ( empty( $args['subject'] ) || 0 !== strpos( (string) $args['subject'], 'New Quote Request:' ) || empty( $_COOKIE['mecca_click'] ) ) {
		return $args;
	}
	$raw = sanitize_text_field( wp_unslash( $_COOKIE['mecca_click'] ) );
	if ( ! preg_match( '/^(gclid|gbraid|wbraid):([\w.-]{10,200})\|(\d+)$/', $raw, $m ) ) {
		return $args;
	}
	$when = gmdate( 'Y-m-d H:i:s', (int) ( $m[3] / 1000 ) ) . ' UTC';
	$line = sprintf( 'Google Ads click: %s = %s (ad clicked %s). Keep this with the booking so the ride can be credited to Google Ads.', $m[1], $m[2], $when );
	$html = false !== stripos( (string) $args['message'], '</' );
	$args['message'] .= $html ? '<p style="color:#888;font-size:12px">' . esc_html( $line ) . '</p>' : "\n\n" . $line;
	return $args;
}, 5 );
