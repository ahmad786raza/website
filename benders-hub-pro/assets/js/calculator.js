/*
 * Bend Radius Calculator.
 * Hidden results until submit.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-calc]");
	if (!root) return;
	const $ = BDH.$;

	const form    = $("[data-bdh-calc-form]", root);
	const result  = $("[data-bdh-calc-result]", root);

	// Material strength coefficients (rough engineering ratios for K-factor & springback)
	const materials = {
		mild:           { name: "Mild Steel / DOM",   sb: 1.0, k: 0.41, stress: 1.0 },
		chromoly:       { name: "Chromoly 4130",      sb: 1.4, k: 0.39, stress: 1.5 },
		stainless:      { name: "T304 Stainless",     sb: 1.6, k: 0.40, stress: 1.8 },
		stainless_t321: { name: "T321 Stainless",     sb: 1.5, k: 0.41, stress: 1.7 },
		aluminum:       { name: "Aluminum 6061",      sb: 0.7, k: 0.33, stress: 0.6 },
		copper:         { name: "Copper",             sb: 0.5, k: 0.36, stress: 0.4 },
	};

	function tileState(el, state) {
		el.closest(".bdh-calc-result-tile").setAttribute("data-state", state);
	}

	BDH.on(form, "submit", function (e) {
		e.preventDefault();
		const data = Object.fromEntries(new FormData(form).entries());
		const od    = parseFloat(data.od);
		const wall  = parseFloat(data.wall);
		const angle = parseFloat(data.angle);
		const clr   = parseFloat(data.clr);
		const mat   = materials[data.material] || materials.mild;
		const proc  = data.process;

		if (!od || !wall || !angle || !clr) return;

		const id        = od - 2 * wall;
		const dtRatio   = (od / wall);
		const clrToOd   = clr / od;
		const minClrRec = od * 2.0; // 2D min industry rule of thumb
		const idealClr  = Math.max(minClrRec, clr);

		// Bend allowance: BA = θ (R + K·t)
		const rad = angle * Math.PI / 180;
		const ba  = rad * (clr + mat.k * wall);
		const cut = ba + 4; // mock straight tube allowance per side, illustrative

		// Wrinkle risk (heuristic):
		// thinner wall + tighter CLR + harder material → higher risk
		let wrinkleScore = 0;
		if (clrToOd < 2)  wrinkleScore += 40;
		if (clrToOd < 1.5) wrinkleScore += 25;
		if (dtRatio > 35) wrinkleScore += 20;
		if (dtRatio > 50) wrinkleScore += 25;
		wrinkleScore += (mat.stress - 1) * 15;
		if (proc === "press") wrinkleScore += 15;
		if (proc === "mandrel") wrinkleScore -= 25;
		wrinkleScore = Math.max(0, Math.min(100, Math.round(wrinkleScore)));

		// Springback (degrees added)
		const sbDeg = +(angle * 0.018 * mat.sb).toFixed(2);

		// Render
		$("[data-bdh-calc-clr]", root).textContent           = idealClr.toFixed(2) + '"';
		$("[data-bdh-calc-clr-note]", root).textContent      = "Min 2×OD = " + minClrRec.toFixed(2) + '"';
		tileState($("[data-bdh-calc-clr]", root), idealClr >= minClrRec ? "ok" : "warn");

		const wrinkleLabel = wrinkleScore < 25 ? "LOW" : wrinkleScore < 60 ? "MEDIUM" : "HIGH";
		$("[data-bdh-calc-wrinkle]", root).textContent      = wrinkleLabel + " (" + wrinkleScore + "%)";
		$("[data-bdh-calc-wrinkle-note]", root).textContent = wrinkleScore < 25 ? "Setup looks clean." : wrinkleScore < 60 ? "Consider mandrel or higher CLR." : "Mandrel + adjusted CLR strongly recommended.";
		tileState($("[data-bdh-calc-wrinkle]", root), wrinkleScore < 25 ? "ok" : wrinkleScore < 60 ? "warn" : "bad");

		$("[data-bdh-calc-springback]", root).textContent     = "+" + sbDeg + "°";
		$("[data-bdh-calc-springback-note]", root).textContent= "Over-bend by ≈ " + sbDeg + "° to compensate.";
		tileState($("[data-bdh-calc-springback]", root), "ok");

		$("[data-bdh-calc-allowance]", root).textContent      = ba.toFixed(3) + '"';
		$("[data-bdh-calc-allowance-note]", root).textContent = "Per bend (K=" + mat.k + ")";

		$("[data-bdh-calc-dt]", root).textContent      = dtRatio.toFixed(1);
		$("[data-bdh-calc-dt-note]", root).textContent = dtRatio > 50 ? "Thin-wall risk." : "OK";
		tileState($("[data-bdh-calc-dt]", root), dtRatio > 50 ? "warn" : "ok");

		$("[data-bdh-calc-cut]", root).textContent      = cut.toFixed(2) + '"';
		$("[data-bdh-calc-cut-note]", root).textContent = "Approx. tube length per leg.";

		const tooling = [
			"Die: " + od.toFixed(3) + '" OD, ' + idealClr.toFixed(2) + '" CLR.',
			wrinkleScore > 50 ? "Process: rotary draw with mandrel." : "Process: " + proc + ".",
			"Material: " + mat.name + " (K=" + mat.k + ", springback factor " + mat.sb + "×).",
		];
		$("[data-bdh-calc-tooling]", root).textContent = tooling.join(" ");
		$("[data-bdh-calc-time]", root).textContent    = new Date().toLocaleTimeString();
		$("[data-bdh-calc-amazon]", root).href         = BDH.amazonUrl(od.toFixed(2) + " inch tube bender die " + data.material);

		// Persist snapshot so Hero Live Spec Console can mirror it.
		BDH.storage.set("bdh_spec_snapshot", {
			od:        od.toFixed(3),
			wall:      wall.toFixed(3),
			clr:       idealClr.toFixed(3),
			angle:     angle,
			material:  mat.name,
			springback: sbDeg,
			wrinkle:   wrinkleLabel,
			process:   proc,
			ts:        Date.now(),
		});
		window.dispatchEvent(new CustomEvent("bdh:spec:update"));

		result.hidden = false;
		result.scrollIntoView({ behavior: "smooth", block: "start" });
	});

	BDH.on(form, "reset", () => {
		result.hidden = true;
	});
})();
