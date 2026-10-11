/*
 * Tube geometry visualizer — single bend drawn to true scale (SVG user units = inches).
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-visualizer]");
	if (!root) return;

	const NS = "http://www.w3.org/2000/svg";
	const keys = ["od", "wall", "clr", "angle"];
	const range = {}, numIn = {};
	keys.forEach((k) => {
		range[k] = root.querySelector('[data-bdh-vz="' + k + '"]');
		numIn[k] = root.querySelector('[data-bdh-vz-num="' + k + '"]');
	});
	const svg = root.querySelector("[data-bdh-vz-svg]");
	const layer = root.querySelector("[data-bdh-vz-layer]");
	const desc = root.querySelector("[data-bdh-vz-desc]");
	const warnings = root.querySelector("[data-bdh-vz-warnings]");
	const out = (k) => root.querySelector('[data-vz-out="' + k + '"]');

	const inch = (v) => v.toFixed(3) + '" (' + (v * 25.4).toFixed(1) + " mm)";

	function el(tag, attrs, text) {
		const n = document.createElementNS(NS, tag);
		Object.keys(attrs).forEach((a) => n.setAttribute(a, attrs[a]));
		if (text) n.textContent = text;
		layer.appendChild(n);
		return n;
	}

	function read() {
		const v = {};
		keys.forEach((k) => {
			let n = parseFloat(numIn[k].value);
			const min = parseFloat(range[k].min), max = parseFloat(range[k].max);
			if (!isFinite(n)) n = parseFloat(range[k].value);
			v[k] = Math.min(max, Math.max(min, n));
		});
		return v;
	}

	function draw() {
		const v = read();
		const msgs = [];
		// Physical limits: wall < OD/2, CLR > OD/2.
		if (v.wall >= v.od / 2) { v.wall = +(v.od / 2 - 0.005).toFixed(3); msgs.push(["warn", "Wall was more than half the OD — limited for the drawing."]); }
		if (v.clr <= v.od / 2) { v.clr = +(v.od / 2 + 0.05).toFixed(3); msgs.push(["bad", "CLR must exceed half the OD; the drawing uses the smallest possible radius."]); }

		const th = v.angle * Math.PI / 180;
		const R = v.clr, r = v.od / 2;
		const S = Math.max(R * 0.9, v.od * 3);           // straight leg length drawn
		// Math coords: centre (0,0); leg A along y = -R heading +x; arc CCW by θ.
		const P1 = [0, -R];
		const phi2 = -Math.PI / 2 + th;
		const P2 = [R * Math.cos(phi2), R * Math.sin(phi2)];
		const dir2 = [-Math.sin(phi2), Math.cos(phi2)];
		const A0 = [-S, -R];
		const B1 = [P2[0] + dir2[0] * S, P2[1] + dir2[1] * S];
		const T = v.angle < 179.9 ? R * Math.tan(th / 2) : Infinity;
		const I = isFinite(T) ? [T, -R] : null;

		// Bounding box (include tube half-width and the bend centre).
		const pts = [A0, P1, P2, B1, [0, 0]];
		for (let i = 0; i <= 24; i++) { const a = -Math.PI / 2 + th * i / 24; pts.push([R * Math.cos(a), R * Math.sin(a)]); }
		if (I) pts.push(I);
		let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
		pts.forEach((p) => { minX = Math.min(minX, p[0]); maxX = Math.max(maxX, p[0]); minY = Math.min(minY, p[1]); maxY = Math.max(maxY, p[1]); });
		const pad = r + Math.max(maxX - minX, maxY - minY) * 0.08;
		minX -= pad; maxX += pad; minY -= pad; maxY += pad;
		const w = maxX - minX, h = maxY - minY;
		svg.setAttribute("viewBox", [minX, -maxY, w, h].join(" ")); // flip y via negation below
		const fy = (p) => [p[0], -p[1]];
		const fs = Math.max(w, h) * 0.035;

		layer.innerHTML = "";
		const large = th > Math.PI ? 1 : 0;
		const a = fy(A0), p1 = fy(P1), p2 = fy(P2), b = fy(B1), c = fy([0, 0]);
		const d = "M " + a + " L " + p1 + " A " + R + " " + R + " 0 " + large + " 0 " + p2 + " L " + b;

		el("path", { d, fill: "none", stroke: "#4b5259", "stroke-width": v.od, "stroke-linecap": "butt", "stroke-linejoin": "round" });
		el("path", { d, fill: "none", stroke: "#e7e4de", "stroke-width": Math.max(v.od - 2 * v.wall, 0.001), "stroke-linecap": "butt" });
		el("path", { d, fill: "none", stroke: "#c2410c", "stroke-width": fs * 0.08, "stroke-dasharray": fs * 0.5 + " " + fs * 0.35 });

		// Radius lines and centre.
		el("line", { x1: c[0], y1: c[1], x2: p1[0], y2: p1[1], stroke: "#2f5d8a", "stroke-width": fs * 0.07 });
		el("line", { x1: c[0], y1: c[1], x2: p2[0], y2: p2[1], stroke: "#2f5d8a", "stroke-width": fs * 0.07 });
		el("circle", { cx: c[0], cy: c[1], r: fs * 0.18, fill: "#2f5d8a" });
		el("text", { x: c[0] + fs * 0.4, y: c[1] + (p1[1] - c[1]) / 2, fill: "#2f5d8a", "font-size": fs, "font-family": "JetBrains Mono, monospace" }, "CLR " + R.toFixed(2) + '"');

		// Tangent points and the intersection of the straight centrelines.
		[p1, p2].forEach((p) => el("circle", { cx: p[0], cy: p[1], r: fs * 0.16, fill: "#16191d" }));
		if (I) {
			const ii = fy(I);
			el("line", { x1: p1[0], y1: p1[1], x2: ii[0], y2: ii[1], stroke: "#5c636b", "stroke-width": fs * 0.05, "stroke-dasharray": fs * 0.25 + " " + fs * 0.2 });
			el("line", { x1: p2[0], y1: p2[1], x2: ii[0], y2: ii[1], stroke: "#5c636b", "stroke-width": fs * 0.05, "stroke-dasharray": fs * 0.25 + " " + fs * 0.2 });
			el("circle", { cx: ii[0], cy: ii[1], r: fs * 0.14, fill: "none", stroke: "#5c636b", "stroke-width": fs * 0.05 });
			el("text", { x: (p1[0] + ii[0]) / 2, y: p1[1] + fs * 1.3 + r, fill: "#3b4148", "font-size": fs * 0.9, "font-family": "JetBrains Mono, monospace", "text-anchor": "middle" }, "T " + T.toFixed(2) + '"');
		}
		el("text", { x: a[0], y: a[1] - r - fs * 0.5, fill: "#16191d", "font-size": fs, "font-family": "Inter, sans-serif", "font-weight": "600" }, v.angle + "° bend · OD " + v.od.toFixed(2) + '"');

		// Numbers.
		const D = R / v.od, WF = v.od / v.wall, arc = R * th, stretch = v.od / (2 * R) * 100;
		out("d").textContent = D.toFixed(2) + "D";
		out("wf").textContent = WF.toFixed(1);
		out("arc").textContent = inch(arc);
		out("t").textContent = isFinite(T) ? inch(T) : "— (legs parallel)";
		out("ri").textContent = inch(R - r);
		out("stretch").textContent = "≈ " + stretch.toFixed(1) + "%";
		root.querySelector('[data-tile="d"]').setAttribute("data-state", D < 2 ? "bad" : D < 3 ? "warn" : "ok");
		desc.textContent = "Diagram of a " + v.angle + " degree bend in " + v.od + " inch tube on a " + R + " inch centerline radius.";

		if (D < 2) msgs.push(["bad", "D of bend below 2: expect to need mandrel and wiper-die support."]);
		else if (D < 3) msgs.push(["warn", "D of bend below 3: tighter than the usual non-mandrel range — test on scrap."]);
		if (WF >= 30 && D < 3) msgs.push(["warn", "Thin wall (wall factor " + WF.toFixed(0) + ") at a tight radius increases wrinkling risk."]);
		if (!msgs.length) msgs.push(["ok", "Within the usual non-mandrel range for this wall."]);
		warnings.innerHTML = "";
		msgs.forEach((m) => { const p = document.createElement("p"); p.className = "bdh-note bdh-note--" + m[0]; p.textContent = m[1]; warnings.appendChild(p); });
	}

	keys.forEach((k) => {
		range[k].addEventListener("input", () => { numIn[k].value = range[k].value; draw(); });
		numIn[k].addEventListener("input", () => { const n = parseFloat(numIn[k].value); if (isFinite(n)) range[k].value = n; draw(); });
	});

	function prefill(snap) {
		if (!snap) return;
		const f = snap.units === "mm" ? 1 / 25.4 : 1;
		const map = { od: snap.od * f, wall: snap.wall * f, clr: snap.clr * f, angle: snap.angle };
		keys.forEach((k) => { if (isFinite(map[k])) { numIn[k].value = +map[k].toFixed(3); range[k].value = map[k]; } });
		draw();
	}
	window.addEventListener("bdh:spec:update", (e) => {
		let flag = null;
		try { flag = sessionStorage.getItem("bdh_viz_prefill"); } catch (err) {}
		if (flag) prefill(e.detail);
	});
	let start = null;
	try { if (sessionStorage.getItem("bdh_viz_prefill")) { start = BDH.storage.get("bdh_spec_snapshot"); sessionStorage.removeItem("bdh_viz_prefill"); } } catch (err) {}
	if (start) prefill(start); else draw();
})();
