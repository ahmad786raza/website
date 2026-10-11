/*
 * Tooling checklist — lists tooling to confirm with the bender manufacturer
 * for a tube size, material and job. Guidance only; no invented tonnages.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-compat]");
	if (!root || !window.BDH) return;

	const form     = root.querySelector("[data-bdh-compat-form]");
	const resultEl = root.querySelector("[data-bdh-compat-result]");
	const listEl   = root.querySelector("[data-bdh-compat-list]");
	const warnEl   = root.querySelector("[data-bdh-compat-warnings]");
	const amazonEl = root.querySelector("[data-bdh-compat-amazon]");

	form.addEventListener("submit", (e) => {
		e.preventDefault();
		const d = Object.fromEntries(new FormData(form).entries());
		const size = parseFloat(d.size);
		const items = [];
		const notes = [];
		const thinJob = d.type === "exhaust" || d.goal === "finish";

		if (d.material === "copper") {
			items.push(["Lever-type hand bender", "Forms sized for " + size + "\" soft tube or line."]);
		} else if (d.type === "exhaust") {
			items.push(["Mandrel tooling or pre-bent mandrel sections", "Exhaust bends are commonly about 1–1.5× OD CLR — " + (size * 1).toFixed(2) + "\"–" + (size * 1.5).toFixed(2) + "\" for " + size + "\" tube — which needs mandrel support in thin wall."]);
			items.push(["Wiper die", "Supports the inside of the bend against wrinkling on mandrel benders."]);
		} else {
			items.push(["Bend die: " + size + "\" OD round tube", "Pick a CLR your bender's maker offers for this OD. Without a mandrel, about 3× OD or more is the usual range (" + (size * 3).toFixed(2) + "\"+). Pipe dies are sized differently."]);
			items.push(["Clamp block and pressure die", "Matched to the same OD — normally part of the die set."]);
		}
		if (d.type === "rollcage") {
			items.push(["Tube notcher + hole saws", "Sized to the receiving tube OD, with saws suited to " + (d.material === "chromoly" ? "4130" : "your material") + "."]);
			items.push(["Rulebook check", "Tube size, wall, material and minimum bend radius from your sanctioning body's current rules (SCCA Time Trials, for example, requires ≥ 3× tube diameter at the centreline)."]);
		}
		if (d.goal === "precision") items.push(["Angle measurement", "A digital angle finder or degree ring to measure springback on a test piece."]);
		if (d.goal === "speed") items.push(["Degree stops / hydraulic drive", "Repeat angles without re-measuring every bend."]);
		if (d.goal === "cost") items.push(["Buy dies as you need them", "Start with the one OD/CLR you will use most; dies are usually brand-specific."]);
		if (thinJob || d.material === "stainless") items.push(["Bending lubricant", "Use the type your die or mandrel maker recommends."]);

		if (d.material === "chromoly") notes.push(["warn", "JD2 notes that 4130 springs back about twice as much as welded mild steel of the same size — confirm the bender is rated for 4130 at your wall thickness."]);
		if (d.material === "stainless") notes.push(["warn", "Stainless needs more force than mild steel and springs back more. Check the capacity chart for stainless specifically."]);
		if (d.material === "aluminum") notes.push(["warn", "Temper matters: annealed tube bends most easily, while 6061-T6 can crack at tight radii (Rogue Fabrication recommends about 3.5–4× OD)."]);
		if (size >= 2.5) notes.push(["warn", "2.5\" OD and larger is beyond many home-shop manual benders — check the capacity chart; hydraulic or industrial machines are typical."]);
		notes.push(["info", "Always confirm compatibility in your bender manufacturer's die catalogue — dies rarely interchange between brands."]);

		listEl.innerHTML = items.map((i) => '<div class="bdh-compat-item"><strong>' + BDH.escapeHtml(i[0]) + "</strong><small>" + BDH.escapeHtml(i[1]) + "</small></div>").join("");
		warnEl.innerHTML = notes.map((n) => '<p class="bdh-note bdh-note--' + n[0] + '">' + BDH.escapeHtml(n[1]) + "</p>").join("");
		amazonEl.href = BDH.amazonUrl(size + " inch tube bender die");
		resultEl.hidden = false;
		BDH.storage.set(BDH.storage_keys.compatibility, { data: d, ts: Date.now() });
		resultEl.scrollIntoView({ behavior: BDH.reducedMotion ? "auto" : "smooth", block: "nearest" });
	});
})();
