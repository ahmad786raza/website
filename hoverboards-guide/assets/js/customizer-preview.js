(function ($) {
	if (!window.wp || !wp.customize) { return; }
	wp.customize('hbg_accent_color', function (value) {
		value.bind(function (v) { document.documentElement.style.setProperty('--accent', v); });
	});
}(window.jQuery));
