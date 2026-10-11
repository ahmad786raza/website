/*
 * Find My Bender quiz — every answer contributes to a score for each class of
 * equipment; the result explains the reasons and offers a runner-up.
 * Recommendations are equipment TYPES, never invented products.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-quiz]");
	if (!root || !window.BDH) return;
	const $ = (s, c) => (c || root).querySelector(s);
	const $$ = (s, c) => Array.from((c || root).querySelectorAll(s));

	const cards   = $$("[data-bdh-quiz-step-card]");
	const intro   = $('[data-bdh-quiz-screen="intro"]');
	const runner  = $('[data-bdh-quiz-screen="runner"]');
	const result  = $('[data-bdh-quiz-screen="result"]');
	const nextBtn = $("[data-bdh-quiz-next]");
	const backBtn = $("[data-bdh-quiz-back]");
	const errorEl = $("[data-bdh-quiz-error]");
	const total   = cards.length;
	let step = 0;
	let guides = {};
	try { guides = JSON.parse(root.getAttribute("data-guides") || "{}"); } catch (e) {}

	const TYPES = {
		lever: {
			title: "Lever-type hand tube bender",
			summary: "Small, thin tube bends cleanly in a hand bender sized to the tube. A big machine would add cost without improving the result.",
			look: ["Forms for each OD you use", "Rated for your material (copper, steel or stainless line)", "Clear degree marks"],
			safety: "Wear eye protection; deburr cut ends and keep fingers clear of the form while bending.",
			kw: "lever tube bender"
		},
		conduit: {
			title: "EMT conduit hand bender",
			summary: "Conduit benders have the offset and multiplier marks electricians rely on, cast into the shoe for each trade size.",
			look: ["Shoe for each trade size (1/2\", 3/4\", 1\")", "Readable marks and multiplier chart", "Handle that suits your height"],
			safety: "Brace the conduit and use foot pressure as the maker describes; follow your local electrical code for bend limits between pull points.",
			kw: "EMT conduit bender"
		},
		rotary_manual: {
			title: "Manual rotary-draw bender",
			summary: "Rotary-draw benders pull the tube around a die, so every bend has the same radius and the tube stays round — what cage, chassis and frame work needs.",
			look: ["A published capacity chart covering your OD, wall and material", "Dies available in your OD and the radius you need", "A degree ring or stops, and a hydraulic upgrade path"],
			safety: "Bolt the bender to a solid floor and keep hands clear of the die and pressure die while it turns.",
			kw: "rotary draw tube bender"
		},
		rotary_hydraulic: {
			title: "Hydraulic rotary-draw bender",
			summary: "The same rotary-draw principle with a hydraulic cylinder doing the work — the practical choice for heavier walls, 4130 and frequent bending.",
			look: ["Capacity chart for your heaviest wall and material", "Pump type you can run (manual, air or electric)", "Degree stops for repeatable angles"],
			safety: "Never exceed the rated pressure, inspect hoses and fittings, and keep clear of the die path when the cylinder is under load.",
			kw: "hydraulic tube bender"
		},
		ram: {
			title: "Hydraulic ram (pipe) bender",
			summary: "A bottle-jack ram bender is a low-cost way to bend heavy-wall schedule pipe where an exact radius and perfect finish don't matter.",
			look: ["Shoes for your nominal pipe sizes", "Schedule 40/80 rating", "A stable frame for the ram force"],
			safety: "These press the pipe between dies — they tend to flatten or kink thin-wall tube and are not suitable for roll cages or exhausts.",
			kw: "hydraulic pipe bender"
		},
		mandrel_bends: {
			title: "Pre-bent mandrel bends (cut and weld)",
			summary: "For one-off exhausts and intakes, buying mandrel-bent U- and J-bends in your size and cutting them to fit gives smooth, full-bore bends without buying a mandrel bender.",
			look: ["Bends in your exact OD, wall and material", "A stated CLR so the pieces line up", "A good cut-off saw, clamps and a welder"],
			safety: "Wear a respirator and eye protection when cutting and welding stainless and coated (aluminised) tube.",
			kw: "mandrel bend exhaust tubing"
		},
		mandrel: {
			title: "Mandrel bender",
			summary: "Thin-wall tube at tight radii needs internal mandrel support and a wiper die to stay round — the domain of mandrel benders.",
			look: ["Tooling cost per size (die, mandrel, wiper)", "Lubrication and setup support from the maker", "Power and floor space it needs"],
			safety: "Mandrel and wiper setup is critical; follow the manufacturer's procedure and guard the machine.",
			kw: "mandrel tube bender"
		},
		cnc: {
			title: "CNC / electric rotary-draw bender",
			summary: "For batches of identical multi-bend parts, a programmable bender sets angle and rotation for you and stores springback per material.",
			look: ["A demonstration on your actual parts", "Tooling lead times and service support", "Software, power and floor-space requirements"],
			safety: "Production benders need guarding and trained operators; follow lockout/tagout for tooling changes.",
			kw: "CNC tube bender"
		},
		roller: {
			title: "Ring / section roller",
			summary: "Large sweeping curves and full hoops are made by rolling the tube through three rolls in several passes rather than drawing it around a die.",
			look: ["Rolls that fit your tube or pipe profile", "Capacity for your largest section", "Hand-crank or motorised drive"],
			safety: "Keep fingers away from the in-running rolls.",
			kw: "tube roller bender"
		}
	};

	function score(a) {
		const s = {}, why = {};
		Object.keys(TYPES).forEach((k) => { s[k] = 0; why[k] = []; });
		const add = (k, n, reason) => { s[k] += n; if (reason && n > 0) why[k].push(reason); };

		switch (a.project) {
			case "rollcage":
				add("rotary_manual", 6, "Cage work needs a consistent radius and round tube — what rotary-draw bending gives.");
				add("rotary_hydraulic", 5, "Cage work needs a consistent radius and round tube — what rotary-draw bending gives.");
				add("ram", -12); add("lever", -8); add("conduit", -10); break;
			case "chassis":
				add("rotary_manual", 6, "Bumpers, frames and armour are classic rotary-draw jobs.");
				add("rotary_hydraulic", 3, "Bumpers, frames and armour are classic rotary-draw jobs.");
				add("ram", -3); break;
			case "exhaust":
				add("mandrel_bends", 7, "Exhaust tube is thin-wall and bent tight, which needs mandrel support to stay full-bore.");
				add("mandrel", 4, "Exhaust tube is thin-wall and bent tight, which needs mandrel support to stay full-bore.");
				add("ram", -10); add("lever", -6); break;
			case "lines":
				add("lever", 9, "Brake, fuel and hydraulic lines are small, thin tube — hand-bender territory.");
				add("ram", -10); add("cnc", -4); break;
			case "pipe":
				add("ram", 4, "Schedule pipe for rails and gates tolerates ram bending.");
				add("rotary_hydraulic", 3, "A hydraulic rotary-draw bender handles pipe with a cleaner, consistent radius.");
				add("roller", 2); break;
			case "conduit":
				add("conduit", 12, "Conduit benders carry the offset marks electrical work relies on.");
				add("ram", -4); break;
			case "production":
				add("cnc", 7, "Identical parts in volume are where programmable benders pay off.");
				add("rotary_hydraulic", 3, "Hydraulic rotary-draw with stops is the step below CNC for repeat work."); break;
			case "art":
				add("roller", 6, "Hoops and sweeping curves are made on a roller, not a die.");
				add("lever", 1); break;
		}
		switch (a.material) {
			case "chromoly":
			case "stainless":
				if (a.wall !== "thin") add("rotary_hydraulic", 2, "4130 and stainless need more force and spring back more than mild steel.");
				break;
			case "soft":
				add("lever", 3, "Soft copper and line tube bend easily by hand."); add("cnc", -2); break;
			case "pipe":
				add("ram", 2); add("rotary_hydraulic", 1); add("lever", -4); break;
			case "aluminum":
				add("mandrel", 1); break;
		}
		switch (a.diameter) {
			case "small": add("lever", 3, "Your tube is under 1\" OD."); add("rotary_hydraulic", -1); break;
			case "mid": add("rotary_manual", 2, "1\"–2\" OD is the sweet spot for manual rotary-draw benders."); add("rotary_hydraulic", 1); add("lever", -6); break;
			case "large": add("rotary_hydraulic", 3, "Over 2\" OD usually calls for hydraulic power."); add("rotary_manual", -2); add("lever", -10); add("conduit", -4); break;
			case "huge": add("cnc", 2); add("rotary_hydraulic", 2, "Large diameters need hydraulic or industrial machines."); add("roller", 1); add("rotary_manual", -6); add("lever", -12); add("ram", -2); break;
		}
		switch (a.wall) {
			case "thin":
				if (a.radius === "tight") { add("mandrel_bends", 3, "Thin wall at a tight radius wrinkles without a mandrel."); add("mandrel", 4, "Thin wall at a tight radius wrinkles without a mandrel."); add("rotary_manual", -3); add("rotary_hydraulic", -2); }
				add("ram", -4); break;
			case "heavy":
				add("rotary_hydraulic", 3, "Heavy walls are much easier with hydraulic assistance."); add("rotary_manual", -1); break;
		}
		switch (a.radius) {
			case "tight": add("mandrel", 2); add("mandrel_bends", 1); break;
			case "standard": add("rotary_manual", 1); add("rotary_hydraulic", 1); break;
			case "large": add("roller", 5, "Large sweeps and rings come from rolling, not a fixed die."); break;
		}
		switch (a.volume) {
			case "regular": add("rotary_hydraulic", 2, "Bending most weeks justifies hydraulic power."); add("mandrel_bends", -1); break;
			case "production": add("cnc", 4, "Daily or batch work is where CNC repeatability pays."); add("mandrel", 3); add("rotary_hydraulic", 2); add("mandrel_bends", -3); break;
		}
		switch (a.power) {
			case "portable": add("lever", 1); add("conduit", 1); add("ram", 1); add("cnc", -10); add("mandrel", -4); break;
			case "garage": add("rotary_manual", 1); add("cnc", -4); break;
			case "shop": add("rotary_hydraulic", 1); break;
			case "industrial": add("cnc", 2); add("mandrel", 1); break;
		}
		switch (a.budget) {
			case "b1": add("lever", 2); add("conduit", 2); add("ram", 2); add("mandrel_bends", 2, "Pre-bent sections keep the cost low."); add("rotary_manual", -2); add("rotary_hydraulic", -5); add("mandrel", -10); add("cnc", -12); break;
			case "b2": add("rotary_manual", 2, "Your budget fits a manual rotary-draw bender and a first die."); add("rotary_hydraulic", -1); add("mandrel", -8); add("cnc", -12); break;
			case "b3": add("rotary_hydraulic", 2, "Your budget covers a hydraulic package and several dies."); add("mandrel", -2); add("cnc", -8); break;
			case "b4": add("cnc", 1); add("mandrel", 2); break;
		}
		if (a.experience === "beginner") { add("cnc", -2); add("mandrel", -1); }
		return { s, why };
	}

	function show(i) {
		cards.forEach((c, n) => { c.hidden = n !== i; });
		$("[data-bdh-quiz-step]").textContent = i + 1;
		$("[data-bdh-quiz-progress]").style.width = ((i + 1) / total * 100) + "%";
		backBtn.disabled = i === 0;
		nextBtn.textContent = i === total - 1 ? (BDH.i18n && BDH.i18n.see_results) || "See my match" : (BDH.i18n && BDH.i18n.next) || "Next";
		errorEl.hidden = true;
	}

	function answers() {
		const a = {};
		cards.forEach((c) => { const sel = c.querySelector("input:checked"); if (sel) a[c.dataset.bdhQuizId] = sel.value; });
		return a;
	}

	function render() {
		const a = answers();
		const { s, why } = score(a);
		const ranked = Object.keys(s).sort((x, y) => s[y] - s[x]);
		const best = ranked[0], second = ranked[1];
		const T = TYPES[best];
		const set = (k, txt) => { const el = $('[data-r="' + k + '"]'); if (el) el.textContent = txt; };
		set("title", T.title);
		set("summary", T.summary);
		const list = (k, items) => { const ul = $('[data-r="' + k + '"]'); ul.innerHTML = ""; items.forEach((t) => { const li = document.createElement("li"); li.textContent = t; ul.appendChild(li); }); };
		const reasons = Array.from(new Set(why[best])).slice(0, 4);
		list("reasons", reasons.length ? reasons : ["It scored best across your answers."]);
		const look = T.look.slice();
		if (a.project === "rollcage") look.push("Your sanctioning body's current rules for tube size, material and minimum bend radius");
		list("look", look);
		set("safety", T.safety);
		const second_ok = s[second] > 0 && s[second] >= s[best] - 6;
		set("runnerup", second_ok ? "Also worth considering: " + TYPES[second].title.toLowerCase() + "." : "");
		const amazon = $('[data-r="amazon"]');
		amazon.href = BDH.amazonUrl(T.kw);
		amazon.setAttribute("aria-label", "Search Amazon for " + T.kw + " (opens in a new tab)");
		const guide = $('[data-r="guide"]');
		const g = a.project === "rollcage" && guides.rollcage_rules ? guides.rollcage_rules : guides[best];
		if (g) { guide.href = g.url; guide.textContent = "Read: " + g.title; guide.hidden = false; } else { guide.hidden = true; }
		BDH.storage.set(BDH.storage_keys.quiz_result, { answers: a, result: best, ts: Date.now() });
	}

	function start() {
		intro.hidden = true; result.hidden = true; runner.hidden = false;
		show(step);
		const first = cards[step].querySelector("input");
		if (first) first.focus({ preventScroll: true });
	}

	$("[data-bdh-quiz-start]").addEventListener("click", () => { step = 0; start(); });

	runner.addEventListener("submit", (e) => {
		e.preventDefault();
		if (!cards[step].querySelector("input:checked")) {
			errorEl.textContent = (BDH.i18n && BDH.i18n.pick_answer) || "Choose an answer to continue.";
			errorEl.hidden = false;
			const f = cards[step].querySelector("input"); if (f) f.focus();
			return;
		}
		if (step === total - 1) {
			render();
			runner.hidden = true; result.hidden = false;
			result.focus({ preventScroll: true });
			result.scrollIntoView({ behavior: BDH.reducedMotion ? "auto" : "smooth", block: "start" });
			return;
		}
		step++; show(step);
		const sel = cards[step].querySelector("input:checked") || cards[step].querySelector("input");
		if (sel) sel.focus({ preventScroll: true });
	});

	backBtn.addEventListener("click", () => { if (step > 0) { step--; show(step); const sel = cards[step].querySelector("input:checked") || cards[step].querySelector("input"); if (sel) sel.focus(); } });

	// Auto-advance only when an option was chosen with a pointer — keyboard users
	// move between options with the arrow keys and press Next themselves.
	let pointerOnOption = false;
	runner.addEventListener("pointerdown", (e) => { pointerOnOption = !!e.target.closest(".bdh-quiz-opt"); });
	runner.addEventListener("keydown", () => { pointerOnOption = false; });
	runner.addEventListener("change", (e) => {
		if (e.target.type !== "radio") return;
		errorEl.hidden = true;
		if (!pointerOnOption) return;
		pointerOnOption = false;
		// Only advance if the reader hasn't already moved on with the Next button.
		const at = step;
		if (step < total - 1) setTimeout(() => { if (step === at && !runner.hidden) { runner.requestSubmit ? runner.requestSubmit() : nextBtn.click(); } }, 180);
	});

	$("[data-bdh-quiz-retake]").addEventListener("click", () => { step = 0; start(); });
	$("[data-bdh-quiz-share]").addEventListener("click", async () => {
		const url = location.href.split("#")[0].split("?")[0] + "#bdh-quiz";
		try {
			if (navigator.share) await navigator.share({ title: (BDH.brand || "Benders Hub") + " — Find My Bender", url });
			else { await navigator.clipboard.writeText(url); BDH.toast((BDH.i18n && BDH.i18n.copied) || "Link copied.", "success"); }
		} catch (err) {}
	});

	// ?profile=… (from the pathway cards) pre-answers the first question and opens the quiz.
	const profileMap = { garage: "chassis", exhaust: "exhaust", rollcage: "rollcage", motorsport: "chassis", pipe: "pipe", production: "production" };
	const params = new URLSearchParams(location.search);
	const prof = profileMap[params.get("profile")];
	if (prof) {
		const input = cards[0].querySelector('input[value="' + prof + '"]');
		if (input) { input.checked = true; step = 1; start(); }
	}
})();
