/*
 * Compatibility Generator — produces tooling list.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-compat]");
	if (!root) return;

	const form     = root.querySelector("[data-bdh-compat-form]");
	const resultEl = root.querySelector("[data-bdh-compat-result]");
	const listEl   = root.querySelector("[data-bdh-compat-list]");
	const warnEl   = root.querySelector("[data-bdh-compat-warnings]");
	const amazonEl = root.querySelector("[data-bdh-compat-amazon]");

	BDH.on(form, "submit", e => {
		e.preventDefault();
		const data = Object.fromEntries(new FormData(form).entries());
		const size = parseFloat(data.size);
		const items = [];
		const warnings = [];

		items.push({ title: 'Die set', body: size + '" OD CLR ' + (size * 2.5).toFixed(2) + '" rotary draw die' });
		items.push({ title: 'Counter die', body: 'Matched profile for ' + data.material });
		items.push({ title: 'Pressure die / wiper', body: 'Hardened tool steel, ' + (size).toFixed(2) + '" cavity' });

		if (data.material === "chromoly" || data.material === "stainless") {
			items.push({ title: 'Mandrel ball + link', body: '3-link plug mandrel for thin wall' });
			warnings.push({ cls: "warn", msg: "Use molybdenum disulfide lubricant for hard alloys." });
		}
		if (data.type === "exhaust") {
			items.push({ title: 'TIG-ready accessories', body: 'V-band flange, exhaust hanger set' });
		}
		if (data.type === "rollcage") {
			items.push({ title: 'Tube notcher + fishmouth saw', body: 'Adjustable 0–60° with chromoly bi-metal hole saws' });
			items.push({ title: 'Chassis layout tape', body: 'High-temp marking + bend layout system' });
		}
		if (data.goal === "speed") {
			items.push({ title: 'Quick-change tool head', body: 'Tool-less die changeover' });
		}
		if (data.goal === "precision") {
			items.push({ title: 'Digital angle finder', body: '±0.1° readout, magnetic base' });
		}
		if (size >= 3) {
			warnings.push({ cls: "warn", msg: "Sizes 3\"+ require 50T+ tonnage and floor-mount stability." });
		}
		if (data.material === "aluminum") {
			warnings.push({ cls: "warn", msg: "Aluminum work-hardens — anneal between sharp bends." });
		}

		listEl.innerHTML = items.map(i => `
			<div class="bdh-compat-item">
				<strong>${BDH.escapeHtml(i.title)}</strong>
				<small>${BDH.escapeHtml(i.body)}</small>
			</div>`).join("");

		warnEl.innerHTML = warnings.map(w => `<div class="bdh-vz-warning ${w.cls}">${BDH.escapeHtml(w.msg)}</div>`).join("");

		amazonEl.href = BDH.amazonUrl(size.toFixed(2) + " inch tube bender die set " + data.material);
		resultEl.hidden = false;
		BDH.storage.set(BDH.storage_keys.compatibility, { data, ts: Date.now() });
		resultEl.scrollIntoView({ behavior: "smooth", block: "nearest" });
	});
})();
