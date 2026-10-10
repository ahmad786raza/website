/*
 * Personalization — archetype selection + continue workflow.
 */
(function () {
	"use strict";
	const $  = BDH.$;
	const $$ = BDH.$$;
	const KEY_ARCHETYPE = BDH.storage_keys.archetype;

	function setSelected(id) {
		$$(".bdh-archetype-card").forEach(c => c.classList.toggle("is-selected", c.dataset.archetype === id));
	}

	// Restore selection on load
	const saved = BDH.storage.get(KEY_ARCHETYPE);
	if (saved) setSelected(saved);

	BDH.delegate(document, "click", "[data-bdh-archetype-pick]", function (e, btn) {
		e.preventDefault();
		const id = btn.getAttribute("data-bdh-archetype-pick");
		BDH.storage.set(KEY_ARCHETYPE, id);
		setSelected(id);
		BDH.toast("Workshop personalized to: " + (btn.closest(".bdh-archetype-card").querySelector(".bdh-archetype-name") || {}).textContent, "success");
	});

	// Inject "continue your workflow" banner if any saved state exists
	const has = ["workflow","project","quiz_result","compatibility","priorities"].some(k => BDH.storage.get(BDH.storage_keys[k]));
	if (has && document.body.classList.contains("bdh-home")) {
		const banner = document.createElement("section");
		banner.className = "bdh-continue-banner";
		banner.style.cssText = "padding:14px 0;background:linear-gradient(90deg,rgba(245,158,11,0.1),rgba(239,68,68,0.05));border-bottom:1px solid var(--bdh-border);";
		banner.innerHTML = `
			<div class="bdh-container" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
				<div style="color:var(--bdh-text);">
					<strong style="color:var(--bdh-amber);font-family:var(--bdh-font-mono);font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;">Continue Your Fabrication Workflow</strong>
					<div style="color:var(--bdh-text-soft);font-size:0.9rem;">You have saved workflows, calculator results, or projects from a previous session.</div>
				</div>
				<div style="display:flex;gap:8px;flex-wrap:wrap;">
					<a class="bdh-btn bdh-btn-sm bdh-btn-amber" href="#bdh-tracker">Open Tracker</a>
					<a class="bdh-btn bdh-btn-sm bdh-btn-ghost" href="#bdh-workflow">Workflow Planner</a>
				</div>
			</div>`;
		const main = document.getElementById("bdh-main");
		if (main) main.insertBefore(banner, main.firstChild);
	}
})();
