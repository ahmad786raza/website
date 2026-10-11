/*
 * Equipment Database — client-side filter + AJAX fallback.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-database]");
	if (!root) return;

	const grid     = root.querySelector("[data-bdh-db-grid]");
	const searchEl = root.querySelector("[data-bdh-db-search]");
	const cats     = Array.from(root.querySelectorAll("[data-bdh-db-cat]"));
	const status   = root.querySelector("[data-bdh-db-status]");
	let activeCat  = "all";
	let activeQ    = "";

	function filter() {
		const cards = Array.from(grid.children);
		let shown = 0;
		cards.forEach(card => {
			const matchCat = activeCat === "all" || card.dataset.eqCategory === activeCat;
			const haystack = (card.textContent || "").toLowerCase();
			const matchQ = !activeQ || haystack.includes(activeQ.toLowerCase());
			const show = matchCat && matchQ;
			card.style.display = show ? "" : "none";
			if (show) shown++;
		});
		status.textContent = shown === cards.length ? "" : shown + " of " + cards.length + " shown";
	}

	cats.forEach(btn => {
		btn.addEventListener("click", () => {
			cats.forEach(c => { c.classList.remove("is-active"); c.setAttribute("aria-pressed", "false"); });
			btn.classList.add("is-active");
			btn.setAttribute("aria-pressed", "true");
			activeCat = btn.getAttribute("data-bdh-db-cat");
			filter();
		});
	});

	let timer;
	searchEl.addEventListener("input", () => {
		clearTimeout(timer);
		timer = setTimeout(() => { activeQ = searchEl.value.trim(); filter(); }, 120);
	});
})();
