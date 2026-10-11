/**
 * Customizer live preview bindings.
 */
(function ($) {
	if (!window.wp || !wp.customize) { return; }
	wp.customize('hbg_accent_color', function (value) {
		value.bind(function (newVal) {
			document.documentElement.style.setProperty('--accent', newVal);
		});
	});
}(window.jQuery));
