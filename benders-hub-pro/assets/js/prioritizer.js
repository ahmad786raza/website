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
		if (top.includes("precision") && top.includes("automation")) { recs = ["Servo-hydraulic CNC bender", "Multi-radius stack dies", "Digital angle finder ±0.1°", "Programmable bend sequencer"]; kw = "CNC tube bender"; }
		else if (top.includes("speed") && top.includes("output"))    { recs = ["High-throughput hydraulic bender", "Quick-change tooling", "Pneumatic clamp upgrade", "Tubing feed stand"]; kw = "high speed hydraulic tube bender"; }
		else if (top.includes("portability"))                       { recs = ["Portable conduit bender", "Hand-pump hydraulic kit", "Field angle protractor", "Truck tubing rack"]; kw = "portable hydraulic tube bender"; }
		else if (top.includes("safety"))                            { recs = ["Light curtain kit", "OSHA-compliant signage", "Lockout/tagout station", "Welding-rated PPE bundle"]; kw = "tube bender safety guard"; }
		else if (top.includes("cost"))                              { recs = ["Manual hydraulic 20T bender", "Multi-die starter pack", "Bench-grade notcher", "Cordless angle finder"]; kw = "affordable hydraulic tube bender"; }
		else if (top.includes("flexibility"))                       { recs = ["Modular die system", "Mandrel + non-mandrel capability", "Multi-process bender", "Universal counter dies"]; kw = "modular tube bender system"; }
		else                                                         { recs = ["Pro workshop 20T bender", "Stack die set", "Tube notcher", "Mandrel option"]; }

		recEl.innerHTML = "<h4 style=\"margin-bottom:8px;\">Recommended setup</h4><ul>" + recs.map(r => `<li>${BDH.escapeHtml(r)}</li>`).join("") + "</ul>";
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
