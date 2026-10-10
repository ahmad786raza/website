/*
 * Tube Geometry Visualizer — live SVG of bend.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-visualizer]");
	if (!root) return;

	const inputs = {
		od:       root.querySelector('[data-bdh-vz="od"]'),
		wall:     root.querySelector('[data-bdh-vz="wall"]'),
		clr:      root.querySelector('[data-bdh-vz="clr"]'),
		angle:    root.querySelector('[data-bdh-vz="angle"]'),
		material: root.querySelector('[data-bdh-vz="material"]'),
	};
	const outs = {
		od:    root.querySelector('[data-bdh-vz-out="od"]'),
		wall:  root.querySelector('[data-bdh-vz-out="wall"]'),
		clr:   root.querySelector('[data-bdh-vz-out="clr"]'),
		angle: root.querySelector('[data-bdh-vz-out="angle"]'),
	};
	const svg        = root.querySelector("[data-bdh-vz-svg]");
	const pathTube   = root.querySelector("[data-bdh-vz-tubepath]");
	const pathCL     = root.querySelector("[data-bdh-vz-centerline]");
	const radiusLine = root.querySelector("[data-bdh-vz-radiusline]");
	const centerDot  = root.querySelector("[data-bdh-vz-centerdot]");
	const angleLabel = root.querySelector("[data-bdh-vz-anglelabel]");
	const clrLabel   = root.querySelector("[data-bdh-vz-clrlabel]");
	const grid       = root.querySelector("[data-bdh-vz-grid]");
	const stressEl   = root.querySelector("[data-bdh-vz-stress]");
	const sbEl       = root.querySelector("[data-bdh-vz-sb]");
	const idEl       = root.querySelector("[data-bdh-vz-id]");
	const wrEl       = root.querySelector("[data-bdh-vz-wr]");
	const warnings   = root.querySelector("[data-bdh-vz-warnings]");

	const mats = {
		mild:      { sb: 1.0, stress: 1.0 },
		chromoly:  { sb: 1.4, stress: 1.5 },
		stainless: { sb: 1.6, stress: 1.8 },
		aluminum:  { sb: 0.7, stress: 0.6 },
	};

	// Draw blueprint grid once
	function drawGrid() {
		grid.innerHTML = "";
		for (let x = 0; x <= 600; x += 30) {
			const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
			line.setAttribute("x1", x); line.setAttribute("x2", x);
			line.setAttribute("y1", 0); line.setAttribute("y2", 400);
			line.setAttribute("stroke", "rgba(245, 158, 11, 0.06)"); line.setAttribute("stroke-width", "0.5");
			grid.appendChild(line);
		}
		for (let y = 0; y <= 400; y += 30) {
			const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
			line.setAttribute("y1", y); line.setAttribute("y2", y);
			line.setAttribute("x1", 0); line.setAttribute("x2", 600);
			line.setAttribute("stroke", "rgba(245, 158, 11, 0.06)"); line.setAttribute("stroke-width", "0.5");
			grid.appendChild(line);
		}
	}
	drawGrid();

	function arcPath(cx, cy, r, startA, endA) {
		const sa = startA * Math.PI / 180, ea = endA * Math.PI / 180;
		const x1 = cx + r * Math.cos(sa), y1 = cy + r * Math.sin(sa);
		const x2 = cx + r * Math.cos(ea), y2 = cy + r * Math.sin(ea);
		const large = Math.abs(endA - startA) > 180 ? 1 : 0;
		const sweep = endA > startA ? 1 : 0;
		return `M ${x1} ${y1} A ${r} ${r} 0 ${large} ${sweep} ${x2} ${y2}`;
	}

	function update() {
		const od    = parseFloat(inputs.od.value);
		const wall  = parseFloat(inputs.wall.value);
		const clr   = parseFloat(inputs.clr.value);
		const angle = parseFloat(inputs.angle.value);
		const mat   = mats[inputs.material.value] || mats.mild;

		outs.od.textContent    = od.toFixed(2);
		outs.wall.textContent  = wall.toFixed(3);
		outs.clr.textContent   = clr.toFixed(2);
		outs.angle.textContent = angle;

		// Scale: 1" = 28px (illustrative)
		const scale  = 28;
		const tubeR  = clr * scale;
		const tubeW  = Math.max(6, Math.min(40, od * 8));
		const cx = 200, cy = 200; // center of bend

		// Straight segments emanate from cx,cy in two directions defined by angle
		// We draw: incoming straight, then arc, then outgoing straight
		const half = angle / 2;
		const start = 180 + half;     // incoming tangent direction
		const end   = 180 - half;     // outgoing tangent direction
		const straightLen = 130;

		const sx1 = cx + tubeR * Math.cos(start * Math.PI / 180);
		const sy1 = cy + tubeR * Math.sin(start * Math.PI / 180);
		const sx2 = cx + tubeR * Math.cos(end   * Math.PI / 180);
		const sy2 = cy + tubeR * Math.sin(end   * Math.PI / 180);

		// Tangent perpendicular vectors (extending straight legs)
		const tanA = (start + 90) * Math.PI / 180;
		const tanB = (end   - 90) * Math.PI / 180;
		const lx1 = sx1 + straightLen * Math.cos(tanA);
		const ly1 = sy1 + straightLen * Math.sin(tanA);
		const lx2 = sx2 + straightLen * Math.cos(tanB);
		const ly2 = sy2 + straightLen * Math.sin(tanB);

		const arc = arcPath(cx, cy, tubeR, start, end);
		const path = `M ${lx1} ${ly1} L ${sx1} ${sy1} ${arc} L ${lx2} ${ly2}`;
		pathTube.setAttribute("d", path);
		pathTube.setAttribute("stroke-width", tubeW);
		pathCL.setAttribute("d", path);
		radiusLine.setAttribute("d", `M ${cx} ${cy} L ${sx1} ${sy1}`);
		centerDot.setAttribute("cx", cx);
		centerDot.setAttribute("cy", cy);

		angleLabel.setAttribute("x", cx - 14);
		angleLabel.setAttribute("y", cy + 5);
		angleLabel.textContent = angle + "°";

		clrLabel.setAttribute("x", (cx + sx1) / 2 + 6);
		clrLabel.setAttribute("y", (cy + sy1) / 2 - 6);
		clrLabel.textContent = "CLR " + clr.toFixed(1) + '"';

		// Readouts
		const dt        = od / wall;
		const clrToOd   = clr / od;
		const sbDeg     = +(angle * 0.018 * mat.sb).toFixed(2);
		const wrinkle   = clrToOd < 1.5 ? "HIGH" : clrToOd < 2 ? "MED" : "LOW";
		const stressLevel = (dt > 50 ? "HIGH" : dt > 30 ? "MED" : "LOW") + " · " + mat.stress.toFixed(1) + "×";
		const idRestrict = clrToOd < 1.5 ? "10-18%" : clrToOd < 2 ? "5-10%" : "<5%";

		stressEl.textContent = stressLevel;
		sbEl.textContent     = "+" + sbDeg + "°";
		idEl.textContent     = idRestrict;
		wrEl.textContent     = wrinkle;

		// Warnings
		warnings.innerHTML = "";
		const addWarn = (cls, msg) => {
			const d = document.createElement("div");
			d.className = "bdh-vz-warning " + cls;
			d.textContent = msg;
			warnings.appendChild(d);
		};
		if (clrToOd < 1.5)  addWarn("bad",  "CLR below 1.5× OD — wrinkle risk severe. Use mandrel.");
		else if (clrToOd < 2) addWarn("warn", "CLR below 2× OD — consider mandrel for thin wall.");
		if (dt > 50)         addWarn("warn", "D/T ratio > 50 — wall is thin; lubricate die and slow feed.");
		if (mat.stress > 1.4) addWarn("warn", "High-strength material — expect " + Math.round((mat.sb - 1) * 100 + 0.5) + "% more springback than mild steel.");
		if (clrToOd >= 2 && dt <= 50 && mat.stress <= 1.4) addWarn("ok", "Setup is in the comfort zone — clean bend expected.");

		// Mirror to Hero Live Spec Console
		BDH.storage.set("bdh_spec_snapshot", {
			od:        od.toFixed(3),
			wall:      wall.toFixed(3),
			clr:       clr.toFixed(2),
			angle:     angle,
			material:  inputs.material.value,
			springback: sbDeg,
			wrinkle:   wrinkle,
			process:   "rotary",
			ts:        Date.now(),
		});
		window.dispatchEvent(new CustomEvent("bdh:spec:update"));
	}

	["input", "change"].forEach(evt => {
		Object.values(inputs).forEach(el => el && el.addEventListener(evt, update));
	});
	update();
})();
