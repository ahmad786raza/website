/*
 * Tube Bender Match Quiz.
 * Hidden results until completion. Recommendation engine in JS.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-quiz]");
	if (!root) return;
	const $  = BDH.$;
	const $$ = BDH.$$;

	const questions = $$("[data-bdh-quiz-step-card]", root);
	const totalEl   = $("[data-bdh-quiz-total]", root);
	const stepEl    = $("[data-bdh-quiz-step]", root);
	const progressEl= $("[data-bdh-quiz-progress]", root);
	const introEl   = $('[data-bdh-quiz-screen="intro"]', root);
	const runnerEl  = $('[data-bdh-quiz-screen="runner"]', root);
	const resultEl  = $('[data-bdh-quiz-screen="result"]', root);
	const nextBtn   = $("[data-bdh-quiz-next]", root);
	const backBtn   = $("[data-bdh-quiz-back]", root);

	let step = 0;
	const total = questions.length;
	const answers = {};

	function show(idx) {
		questions.forEach((q, i) => { q.hidden = i !== idx; });
		stepEl.textContent = idx + 1;
		const pct = ((idx + 1) / total) * 100;
		progressEl.style.setProperty("--bdh-progress", pct + "%");
		backBtn.disabled = idx === 0;
		nextBtn.innerHTML = (idx === total - 1)
			? '<span>See My Results</span>'
			: '<span>Next</span><svg class="bdh-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
	}

	function currentAnswer() {
		const card = questions[step];
		const selected = card.querySelector("input[type=radio]:checked");
		return selected ? selected.value : null;
	}

	function buildResult() {
		// Pick a primary category.
		const a = answers;
		let category = "Manual hydraulic 12-ton tube bender";
		let kw = "tube bender";
		let tooling = "Multi-die starter set, manual angle stop, tubing notcher";
		let material = "Mild steel and DOM compatible — match die radius to wall thickness.";
		let safety = "Wear leather gloves and safety glasses. Verify clamp before every bend.";
		let summary = "We recommend a workshop-grade bender matched to your shop power and precision.";
		let title = "Workshop-grade tube bender";

		if (a.mode === "cnc" || a.precision === "cnc" || a.experience === "pro") {
			category = "CNC servo-hydraulic tube bender";
			kw = "CNC tube bender";
			tooling = "Multi-radius stack dies, programmable controller, mandrel ball set";
			safety = "CNC lockout, light curtains, operator certification.";
			title = "CNC production-grade bender";
			summary = "Programmable repeatability for batch work. Pair with CAD/CAM tubing software.";
		} else if (a.project === "rollcage" || a.material === "chromoly") {
			category = "Pro rotary draw bender (1.75\" / 2\")";
			kw = "roll cage tube bender chromoly";
			tooling = "Tight-CLR chromoly dies, tube notcher, fishmouth jig, chassis table";
			material = "Chromoly 4130 + DOM. Use CLR ≥ 5× OD.";
			safety = "Pinch-point training, certified TIG setup, race PPE.";
			title = "Roll cage / chassis bender";
			summary = "Built for chromoly chassis work — tight CLR, repeatable angles, mandrel optional.";
		} else if (a.project === "exhaust" || a.sector === "auto") {
			category = "2.5\" mandrel exhaust bender";
			kw = "mandrel tube bender exhaust";
			tooling = "Mandrel ball assembly, sectioned dies, TIG-ready accessories";
			material = "T304 stainless, mild steel, aluminized. Wall 0.049\"–0.065\".";
			safety = "Heat-resistant gloves, TIG-rated welder.";
			title = "Exhaust mandrel bender";
			summary = "No-wrinkle bends preserving ID for performance flow.";
		} else if (a.workshop === "industrial" || a.budget === "$$$$$") {
			category = "Industrial CNC multi-stack bender";
			kw = "industrial CNC tube bender";
			tooling = "Multi-stack die system, robotic loader, QA gauges";
			material = "All weldable metals across heavy gauges.";
			safety = "OSHA cell guarding, lockout/tagout.";
			title = "Industrial multi-stack bender";
			summary = "High-throughput production with under-8s cycle times.";
		} else if (a.workshop === "mobile") {
			category = "Portable conduit / pipe bender";
			kw = "portable conduit pipe bender";
			tooling = "Multi-size shoe set, field protractor, hand pump";
			material = "EMT, IMC, Schedule 40 — light & on-site.";
			safety = "PPE + fall protection on-site.";
			title = "Mobile fabrication bender";
			summary = "Truck-friendly bending for service and field work.";
		} else if (a.experience === "beginner" || a.budget === "$") {
			category = "Manual multi-die starter bender";
			kw = "manual tube bender beginner";
			tooling = "3-die starter set, hand-leverage frame, basic notcher";
			material = "Mild steel, EMT, light aluminum.";
			safety = "Basic PPE, lubricant for die.";
			title = "Beginner-friendly bender";
			summary = "Best balance of precision, learnability, and price for first-time fabricators.";
		}

		// Render
		$("[data-bdh-quiz-result-title]", root).textContent       = title;
		$("[data-bdh-quiz-result-summary]", root).textContent     = summary;
		$("[data-bdh-quiz-result-category]", root).textContent    = category;
		$("[data-bdh-quiz-result-tooling]", root).textContent     = tooling;
		$("[data-bdh-quiz-result-material]", root).textContent    = material;
		$("[data-bdh-quiz-result-safety]", root).textContent      = safety;
		$("[data-bdh-quiz-amazon]", root).href                    = BDH.amazonUrl(kw);

		BDH.storage.set(BDH.storage_keys.quiz_result, { answers, title, kw, category, ts: Date.now() });
	}

	BDH.on($("[data-bdh-quiz-start]", root), "click", () => {
		introEl.hidden = true;
		runnerEl.hidden = false;
		show(0);
	});

	BDH.on(nextBtn, "click", () => {
		const ans = currentAnswer();
		if (!ans) {
			BDH.toast("Pick an answer to continue", "error");
			questions[step].animate(
				[{ transform: "translateX(0)" }, { transform: "translateX(-6px)" }, { transform: "translateX(6px)" }, { transform: "translateX(0)" }],
				{ duration: 200 }
			);
			return;
		}
		const card = questions[step];
		answers[card.dataset.bdhQuizId] = ans;

		if (step === total - 1) {
			runnerEl.hidden = true;
			buildResult();
			resultEl.hidden = false;
			window.scrollTo({ top: root.getBoundingClientRect().top + window.scrollY - 80, behavior: "smooth" });
			return;
		}
		step++;
		show(step);
	});

	BDH.on(backBtn, "click", () => {
		if (step === 0) return;
		step--;
		show(step);
	});

	BDH.delegate(root, "click", "[data-bdh-quiz-retake]", () => {
		step = 0;
		Object.keys(answers).forEach(k => delete answers[k]);
		questions.forEach(q => { q.querySelectorAll("input").forEach(i => i.checked = false); });
		resultEl.hidden = true;
		runnerEl.hidden = false;
		show(0);
	});

	BDH.delegate(root, "click", "[data-bdh-quiz-share]", async () => {
		const url = location.href.split("#")[0] + "#bdh-quiz";
		try {
			if (navigator.share) {
				await navigator.share({ title: BDH.brand + " — Tube Bender Match", url });
			} else {
				await navigator.clipboard.writeText(url);
				BDH.toast("Link copied to clipboard", "success");
			}
		} catch (e) {}
	});

	// Auto-advance with radio change (UX polish)
	BDH.delegate(root, "change", "input[type=radio]", () => {
		const ind = currentAnswer();
		if (ind && step < total - 1) {
			setTimeout(() => nextBtn.click(), 220);
		}
	});
})();
