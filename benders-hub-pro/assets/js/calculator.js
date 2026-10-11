/*
 * Bend calculator — exact single-bend geometry + test-bend springback helper.
 *
 *   arc length  L = CLR × θ (radians)
 *   tangent     T = CLR × tan(θ / 2)     (bend start → intersection of straight centrelines)
 *   cut length    = (legA − T) + (legB − T) + L + trim     (legs measured to the intersection)
 *   D of bend     = CLR ÷ OD,   wall factor = OD ÷ wall
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-calc]");
	if (!root || !window.BDH) return;

	const form    = root.querySelector("[data-bdh-calc-form]");
	const result  = root.querySelector("[data-bdh-calc-result]");
	const errorEl = root.querySelector("[data-bdh-calc-error]");
	const assess  = root.querySelector("[data-bdh-calc-assessment]");
	const out     = (k) => root.querySelector('[data-out="' + k + '"]');
	const LENGTHS = ["od", "wall", "clr", "legA", "legB", "trim"];
	let units = "in";

	const fmt = (v) => units === "mm" ? v.toFixed(1) + " mm" : v.toFixed(3) + '"';
	const num = (v) => { const n = parseFloat(String(v).replace(",", ".")); return isFinite(n) ? n : NaN; };

	// Unit switch converts the values already typed.
	root.querySelectorAll('input[name="units"]').forEach((r) => {
		r.addEventListener("change", () => {
			const next = r.value;
			if (next === units) return;
			const f = next === "mm" ? 25.4 : 1 / 25.4;
			LENGTHS.forEach((name) => {
				const el = form.elements[name];
				const v = num(el.value);
				if (isFinite(v)) el.value = String(+(v * f).toFixed(next === "mm" ? 2 : 3));
			});
			units = next;
			root.querySelectorAll("[data-bdh-unit]").forEach((a) => (a.textContent = units));
			if (!result.hidden) form.requestSubmit ? form.requestSubmit() : form.dispatchEvent(new Event("submit"));
		});
	});

	function fail(msg, field) {
		errorEl.textContent = msg;
		errorEl.hidden = false;
		result.hidden = true;
		if (field && form.elements[field]) {
			form.elements[field].setAttribute("aria-invalid", "true");
			form.elements[field].focus();
		}
	}

	function item(cls, text) {
		const p = document.createElement("p");
		p.className = "bdh-note bdh-note--" + cls;
		p.textContent = text;
		return p;
	}

	form.addEventListener("submit", (e) => {
		e.preventDefault();
		form.querySelectorAll("[aria-invalid]").forEach((el) => el.removeAttribute("aria-invalid"));
		errorEl.hidden = true;

		const od = num(form.elements.od.value), wall = num(form.elements.wall.value), clr = num(form.elements.clr.value);
		const angle = num(form.elements.angle.value);
		const legA = num(form.elements.legA.value), legB = num(form.elements.legB.value);
		const trim = num(form.elements.trim.value) || 0;

		if (!(od > 0)) return fail("Enter the tube's outside diameter.", "od");
		if (!(wall > 0)) return fail("Enter a wall thickness greater than zero.", "wall");
		if (wall >= od / 2) return fail("Wall thickness must be less than half the OD.", "wall");
		if (!(clr > 0)) return fail("Enter the die's centerline radius.", "clr");
		if (clr <= od / 2) return fail("CLR must be larger than half the OD, otherwise the inside of the bend would have a negative radius.", "clr");
		if (!(angle > 0 && angle <= 180)) return fail("Bend angle must be between 1° and 180°.", "angle");
		if (trim < 0) return fail("Trim allowance cannot be negative.", "trim");

		const rad = angle * Math.PI / 180;
		const arc = clr * rad;
		const isU = angle >= 179.9;
		const tangent = isU ? Infinity : clr * Math.tan(rad / 2);
		const D = clr / od, WF = od / wall;
		const ro = clr + od / 2, ri = clr - od / 2;
		const stretch = od / (2 * clr) * 100;

		out("arc").textContent = fmt(arc);
		out("tangent").textContent = isU ? "— (legs parallel)" : fmt(tangent);
		out("d").textContent = D.toFixed(2) + "D";
		out("wf").textContent = WF.toFixed(1);
		out("radii").textContent = fmt(ro) + " / " + fmt(ri);
		out("stretch").textContent = "≈ " + stretch.toFixed(1) + "%";

		const haveLegs = legA > 0 && legB > 0;
		if (haveLegs) {
			let straightA, straightB, note;
			if (isU) {
				straightA = legA; straightB = legB;
				note = "180°: legs are taken as straight lengths to the tangent points.";
			} else {
				if (legA < tangent) return fail("Leg A is shorter than the tangent length (" + fmt(tangent) + "), so the bend will not fit.", "legA");
				if (legB < tangent) return fail("Leg B is shorter than the tangent length (" + fmt(tangent) + "), so the bend will not fit.", "legB");
				straightA = legA - tangent; straightB = legB - tangent;
				note = "Legs A + B minus " + fmt(2 * tangent - arc) + " (2T − arc), plus trim.";
			}
			const cut = straightA + straightB + arc + trim;
			out("cut").textContent = fmt(cut);
			out("cut-note").textContent = note;
			out("startMark").textContent = fmt(straightA);
		} else {
			out("cut").textContent = "—";
			out("cut-note").textContent = "Enter both legs to get a cut length.";
			out("startMark").textContent = "—";
		}

		// Difficulty assessment — rules of thumb, labelled as such.
		assess.innerHTML = "";
		const h = document.createElement("h4");
		h.textContent = "Bend difficulty (rules of thumb)";
		assess.appendChild(h);
		let dState = "ok";
		if (D < 1.5) {
			dState = "bad";
			assess.appendChild(item("bad", "D of bend " + D.toFixed(2) + ": a very tight radius. This is mandrel-bender territory (aftermarket mandrel exhaust bends are commonly 1–1.5D)."));
		} else if (D < 2) {
			dState = "warn";
			assess.appendChild(item("warn", "D of bend " + D.toFixed(2) + ": tight. Expect to need mandrel and wiper-die support unless the wall is heavy."));
		} else if (D < 3) {
			dState = "warn";
			assess.appendChild(item("warn", "D of bend " + D.toFixed(2) + ": below the roughly 3× OD that Pro-Tools gives as a practical minimum without a mandrel. Heavier walls may still bend cleanly — test on scrap first."));
		} else {
			assess.appendChild(item("ok", "D of bend " + D.toFixed(2) + ": within the usual non-mandrel range. For reference, common roll-cage dies for 1.75\" tube work out to about 3.1–3.7D."));
		}
		let wfLabel = WF < 15 ? "heavy wall" : WF < 30 ? "medium wall" : WF < 50 ? "thin wall" : "very thin wall";
		assess.appendChild(item(WF >= 30 && D < 3 ? "warn" : "info", "Wall factor " + WF.toFixed(1) + " (" + wfLabel + "). Higher wall factors wrinkle more easily, especially at low D. Check your die maker's mandrel/wiper selection chart — as one published example, 2\" × .065\" tube (wall factor ≈ 31) at 2D called for a two-ball mandrel with a wiper die."));
		if (D < 3) {
			assess.appendChild(item("info", "Building a roll cage? Some rulebooks set a minimum bend radius — SCCA Time Trials, for example, requires at least 3× the tube diameter at the centreline. Check your current rulebook."));
		}
		root.querySelector('[data-tile="d"]').setAttribute("data-state", dState);
		root.querySelector('[data-tile="wf"]').setAttribute("data-state", WF >= 50 ? "warn" : "ok");

		const snap = { od, wall, clr, angle, units, ts: Date.now() };
		BDH.storage.set("bdh_spec_snapshot", snap);
		window.dispatchEvent(new CustomEvent("bdh:spec:update", { detail: snap }));

		result.hidden = false;
		result.scrollIntoView({ behavior: BDH.reducedMotion ? "auto" : "smooth", block: "nearest" });
	});

	form.addEventListener("reset", () => {
		result.hidden = true;
		errorEl.hidden = true;
		setTimeout(() => {
			units = "in";
			root.querySelectorAll("[data-bdh-unit]").forEach((a) => (a.textContent = "in"));
		}, 0);
	});

	const printBtn = root.querySelector("[data-bdh-print]");
	if (printBtn) printBtn.addEventListener("click", () => window.print());

	// Hand the current numbers to the visualizer.
	const vizLink = root.querySelector("[data-bdh-calc-viz]");
	if (vizLink) vizLink.addEventListener("click", () => {
		try { sessionStorage.setItem("bdh_viz_prefill", "1"); } catch (err) {}
	});

	/* ---------- Springback from test bends ---------- */
	const sb = document.querySelector("[data-bdh-springback]");
	if (sb) {
		const sbForm = sb.querySelector("[data-bdh-sb-form]");
		const sbOut  = sb.querySelector("[data-bdh-sb-out]");
		sbForm.addEventListener("submit", (e) => {
			e.preventDefault();
			const bent = num(sbForm.elements.bent.value), measured = num(sbForm.elements.measured.value), target = num(sbForm.elements.target.value);
			if (!(bent > 0 && measured > 0 && target > 0)) { sbOut.textContent = "Enter all three angles."; return; }
			if (measured > bent) { sbOut.textContent = "The measured angle is larger than the machine reading — check which is which (springback makes the tube relax to a smaller angle)."; return; }
			const spring = bent - measured;
			const proportional = target * bent / measured;
			const offset = target + spring;
			sbOut.textContent = "Springback on your test piece: " + spring.toFixed(1) + "°. To finish at " + target + "°, bend to about " +
				Math.min(proportional, offset).toFixed(1) + "°–" + Math.max(proportional, offset).toFixed(1) + "° on the machine " +
				"(proportional vs. constant-offset estimates). Bend a second test piece near your target angle to narrow this down, and log the result for this material batch and die.";
		});
	}
})();
