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
(function(){try{if(document.cookie.indexOf('mecca_src=')<0){var r='';try{r=document.referrer?new URL(document.referrer).hostname:'';}catch(e){}if(r===location.hostname)r='';document.cookie='mecca_src='+encodeURIComponent((r||'direct')+'|'+location.pathname.slice(0,80))+';max-age=7776000;path=/;SameSite=Lax;Secure';}var p=new URLSearchParams(location.search),k=['gclid','gbraid','wbraid'];for(var i=0;i<k.length;i++){var v=p.get(k[i]);if(v&&/^[\w.-]{10,200}$/.test(v)){document.cookie='mecca_click='+k[i]+':'+v+'|'+Date.now()+';max-age=7776000;path=/;SameSite=Lax;Secure';break;}}}catch(e){}})();
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

/** Remember which IPs came from a Google ad (30 days), so a person who clicks on a phone and books from a computer on the same Wi-Fi is still credited. */
function mecca_src_ip() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? preg_replace( '/[^0-9a-f.:]/i', '', $_SERVER['REMOTE_ADDR'] ) : '';
}
add_action( 'template_redirect', function () {
	if ( empty( $_GET['gclid'] ) && empty( $_GET['gbraid'] ) && empty( $_GET['wbraid'] ) ) {
		return;
	}
	$ip = mecca_src_ip();
	if ( $ip ) {
		set_transient( 'mecca_adip_' . md5( $ip ), time(), 30 * DAY_IN_SECONDS );
	}
} );

/** Where did this person come from? Returns a short label like "Google Ad", "Google Ad (other device)", "Organic: google.com", "Direct". */
function mecca_src_label() {
	if ( ! empty( $_COOKIE['mecca_click'] ) ) {
		return 'Google Ad';
	}
	$ip = mecca_src_ip();
	if ( $ip && get_transient( 'mecca_adip_' . md5( $ip ) ) ) {
		return 'Google Ad (other device)';
	}
	$src = isset( $_COOKIE['mecca_src'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_COOKIE['mecca_src'] ) ) ) : '';
	$host = strtok( $src, '|' );
	if ( ! $host || 'direct' === $host ) {
		return 'Direct';
	}
	if ( preg_match( '/googleadservices|doubleclick/', $host ) ) {
		return 'Google Ad';
	}
	if ( preg_match( '/chatgpt|openai|perplexity|claude|gemini|copilot/', $host ) ) {
		return 'AI: ' . $host;
	}
	return 'Organic: ' . $host;
}

add_action( 'save_post_mecca_booking', function ( $id, $post, $update ) {
	if ( $update || is_admin() || get_post_meta( $id, '_bc_source', true ) ) {
		return;
	}
	update_post_meta( $id, '_bc_source', mecca_src_label() );
}, 10, 3 );

add_filter( 'wp_mail', function ( $args ) {
	if ( empty( $args['subject'] ) || 0 !== strpos( (string) $args['subject'], 'New Quote Request:' ) ) {
		return $args;
	}
	$line = 'Came from: ' . mecca_src_label();
	$html = false !== stripos( (string) $args['message'], '</' );
	$args['message'] .= $html ? '<p style="color:#888;font-size:12px">' . esc_html( $line ) . '</p>' : "\n\n" . $line;
	return $args;
}, 6 );
