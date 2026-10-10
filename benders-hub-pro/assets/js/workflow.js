/*
 * Workflow Planner — localStorage-backed bend-step list.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-workflow]");
	if (!root) return;
	const KEY = BDH.storage_keys.workflow;

	const form     = root.querySelector("[data-bdh-workflow-form]");
	const listEl   = root.querySelector("[data-bdh-workflow-list]");
	const emptyEl  = root.querySelector("[data-bdh-workflow-empty]");
	const titleEl  = root.querySelector("[data-bdh-workflow-title]");
	const statusEl = root.querySelector("[data-bdh-workflow-status]");

	let state = BDH.storage.get(KEY, { title: titleEl.textContent, steps: [] });
	if (state.title) titleEl.textContent = state.title;

	function persist() {
		state.title = titleEl.textContent.trim() || "My Fabrication Workflow";
		BDH.storage.set(KEY, state);
		statusEl.textContent = "Saved · " + new Date().toLocaleTimeString();
	}

	function render() {
		if (!state.steps.length) {
			emptyEl.hidden = false;
			listEl.innerHTML = "";
			listEl.appendChild(emptyEl);
			return;
		}
		emptyEl.hidden = true;
		listEl.innerHTML = "";
		state.steps.forEach((s, idx) => {
			const li = document.createElement("li");
			li.className = "bdh-workflow-step";
			li.innerHTML = `
				<span class="bdh-workflow-step-num">${idx + 1}</span>
				<div class="bdh-workflow-step-body">
					<strong>${BDH.escapeHtml(s.label || "Step")}</strong>
					<small>${BDH.escapeHtml(s.angle || "—")}° · CLR ${BDH.escapeHtml(s.clr || "—")}" · ${BDH.escapeHtml(s.tooling || "")}</small>
					${s.safety ? `<small style="color:var(--bdh-amber);">⚠ ${BDH.escapeHtml(s.safety)}</small>` : ""}
				</div>
				<div class="bdh-workflow-step-actions">
					<button type="button" class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-up="${idx}" aria-label="Move up">↑</button>
					<button type="button" class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-down="${idx}" aria-label="Move down">↓</button>
					<button type="button" class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-del="${idx}" aria-label="Delete">×</button>
				</div>`;
			listEl.appendChild(li);
		});
	}

	BDH.on(form, "submit", e => {
		e.preventDefault();
		const data = Object.fromEntries(new FormData(form).entries());
		if (!data.label) return;
		state.steps.push(data);
		persist();
		render();
		form.reset();
		BDH.toast("Step added", "success");
	});

	BDH.delegate(listEl, "click", "[data-up]",   (e, b) => { const i = +b.dataset.up;   if (i > 0) { [state.steps[i-1], state.steps[i]] = [state.steps[i], state.steps[i-1]]; persist(); render(); } });
	BDH.delegate(listEl, "click", "[data-down]", (e, b) => { const i = +b.dataset.down; if (i < state.steps.length - 1) { [state.steps[i+1], state.steps[i]] = [state.steps[i], state.steps[i+1]]; persist(); render(); } });
	BDH.delegate(listEl, "click", "[data-del]",  (e, b) => { const i = +b.dataset.del; state.steps.splice(i, 1); persist(); render(); });

	BDH.on(root.querySelector("[data-bdh-workflow-save]"), "click", () => persist());
	BDH.on(root.querySelector("[data-bdh-workflow-clear]"), "click", () => {
		if (confirm("Clear all workflow steps?")) { state.steps = []; persist(); render(); }
	});
	BDH.on(root.querySelector("[data-bdh-workflow-export]"), "click", () => {
		const blob = new Blob([JSON.stringify(state, null, 2)], { type: "application/json" });
		const url  = URL.createObjectURL(blob);
		const a = document.createElement("a");
		a.href = url; a.download = (state.title || "workflow") + ".json";
		document.body.appendChild(a); a.click(); a.remove();
		setTimeout(() => URL.revokeObjectURL(url), 1000);
	});

	BDH.on(titleEl, "blur", persist);

	render();
})();
