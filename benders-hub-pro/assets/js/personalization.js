/*
 * Remembers which fabrication pathway a reader chose (in their browser only)
 * and highlights it on return visits. Links keep working normally.
 */
(function () {
	"use strict";
	if (!window.BDH || !BDH.storage_keys) return;
	const KEY = BDH.storage_keys.archetype;
	const mark = (id) => document.querySelectorAll("[data-archetype]").forEach((c) => c.classList.toggle("is-selected", c.getAttribute("data-archetype") === id));
	const saved = BDH.storage.get(KEY);
	if (saved) mark(saved);
	document.addEventListener("click", (e) => {
		const link = e.target.closest("[data-bdh-archetype-pick]");
		if (!link) return;
		const id = link.getAttribute("data-bdh-archetype-pick");
		BDH.storage.set(KEY, id);
		mark(id);
	});
})();
