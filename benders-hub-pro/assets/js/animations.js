/*
 * Reveal-on-scroll for sections (GPU-friendly).
 */
(function () {
	"use strict";
	if (!("IntersectionObserver" in window)) return;
	const obs = new IntersectionObserver((entries) => {
		entries.forEach(ent => {
			if (ent.isIntersecting) {
				ent.target.classList.add("bdh-in");
				obs.unobserve(ent.target);
			}
		});
	}, { rootMargin: "0px 0px -40px 0px", threshold: 0.05 });

	document.querySelectorAll(".bdh-section, .bdh-product-box, .bdh-archetype-card, .bdh-bundle-card, .bdh-eq-card, .bdh-trust-card, .bdh-quickstart-card").forEach(el => {
		el.style.opacity = "0";
		el.style.transform = "translateY(16px)";
		el.style.transition = "opacity 0.6s var(--bdh-ease, ease), transform 0.6s var(--bdh-ease, ease)";
		obs.observe(el);
	});

	// Apply visible state via class
	const css = document.createElement("style");
	css.textContent = ".bdh-in { opacity: 1 !important; transform: none !important; }";
	document.head.appendChild(css);
})();
