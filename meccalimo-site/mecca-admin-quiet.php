<?php
/**
 * Hide plugin sales/upsell boxes on the dashboard (Popups for Divi course,
 * Divimode sale, Novamira Pro). Real warnings are left alone.
 */
add_action( 'admin_footer', function () {
	?>
	<script>
	(function () {
		var spam = ['free email course', 'Divimode Summer Sale', 'Novamira Pro is here', 'Divi Areas Pro'];
		document.querySelectorAll('.notice, .updated, .error, [class*="notice"]').forEach(function (n) {
			var t = n.textContent || '';
			if (spam.some(function (s) { return t.indexOf(s) !== -1; })) { n.style.display = 'none'; }
		});
	})();
	</script>
	<?php
} );
