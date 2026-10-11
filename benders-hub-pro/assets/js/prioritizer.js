/*
 * Workshop Efficiency Prioritizer.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-prio]");
	if (!root) return;

	const KEY = BDH.storage_keys.priorities;
	const sliders = Array.from(root.querySelectorAll("[data-bdh-prio-slider]"));
	const valueEls = {};
	sliders.forEach(s => {
		const k = s.getAttribute("data-bdh-prio-slider");
		valueEls[k] = root.querySelector(`[data-bdh-prio-value="${k}"]`);
		s.addEventListener("input", () => { valueEls[k].textContent = s.value; });
	});

	// Restore saved
	const saved = BDH.storage.get(KEY, {});
	sliders.forEach(s => {
		const k = s.getAttribute("data-bdh-prio-slider");
		if (saved[k]) { s.value = saved[k]; valueEls[k].textContent = saved[k]; }
	});

	const resultEl  = root.querySelector("[data-bdh-prio-result]");
	const summaryEl = root.querySelector("[data-bdh-prio-summary]");
	const recEl     = root.querySelector("[data-bdh-prio-recommend]");
	const amazonEl  = root.querySelector("[data-bdh-prio-amazon]");

	BDH.on(root.querySelector("[data-bdh-prio-build]"), "click", () => {
		const data = {};
		sliders.forEach(s => data[s.getAttribute("data-bdh-prio-slider")] = +s.value);
		BDH.storage.set(KEY, data);

		// Pick top 3 priorities
		const sorted = Object.entries(data).sort(([,a],[,b]) => b - a);
		const top = sorted.slice(0, 3).map(e => e[0]);
		const labels = {
			precision: "precision", speed: "speed", automation: "automation",
			portability: "portability", safety: "safety", cost: "cost efficiency",
			flexibility: "fabrication flexibility", output: "industrial output",
		};
		summaryEl.textContent = "Your shop optimizes for: " + top.map(k => labels[k] || k).join(", ") + ".";

		let recs = [];
		let kw = "tube bender";
		if (top.includes("automation") || top.includes("output"))    { recs = ["CNC or electric rotary-draw bender (only with batch volume)", "Tube measuring and bending software", "Degree stops on any manual machines"]; kw = "CNC tube bender"; }
		else if (top.includes("precision"))                          { recs = ["Rotary-draw bender with a degree ring or stops", "Digital angle finder for test-bend springback", "Dies in the exact OD/CLR you need"]; kw = "rotary draw tube bender"; }
		else if (top.includes("speed"))                              { recs = ["Hydraulic rotary-draw bender (air or electric pump)", "Degree stops for repeat angles", "Tube rack and cut station beside the bender"]; kw = "hydraulic tube bender"; }
		else if (top.includes("portability"))                        { recs = ["Lever hand benders for small tube", "Hand-pump hydraulic pipe bender for heavy pipe", "Portable notcher or saw"]; kw = "portable pipe bender"; }
		else if (top.includes("safety"))                             { recs = ["Floor-anchored bender with guarded pinch points", "Eye, hand and hearing protection", "Written procedure from the bender's manual next to the machine"]; kw = "tube bender"; }
		else if (top.includes("cost"))                               { recs = ["Manual rotary-draw bender", "One die in your most-used size", "Hydraulic upgrade later if the bender supports it"]; kw = "manual tube bender"; }
		else if (top.includes("flexibility"))                        { recs = ["Rotary-draw bender with a broad die catalogue", "Tube notcher", "Section roller for large-radius work"]; kw = "rotary draw tube bender"; }
		else                                                         { recs = ["Rotary-draw bender", "Tube notcher", "Angle finder"]; }

		recEl.innerHTML = "<h4>Equipment to research first</h4><ul class=\"bdh-checklist\">" + recs.map(r => `<li>${BDH.escapeHtml(r)}</li>`).join("") + "</ul>";
		amazonEl.href = BDH.amazonUrl(kw);
		resultEl.hidden = false;
		resultEl.scrollIntoView({ behavior: "smooth", block: "nearest" });
	});

	BDH.on(root.querySelector("[data-bdh-prio-reset]"), "click", () => {
		sliders.forEach(s => { s.value = 3; valueEls[s.getAttribute("data-bdh-prio-slider")].textContent = "3"; });
		BDH.storage.remove(KEY);
		resultEl.hidden = true;
	});
})();
