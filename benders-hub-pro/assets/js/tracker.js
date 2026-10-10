/*
 * Project Tracker — localStorage projects + simple bar charts.
 */
(function () {
	"use strict";
	const root = document.querySelector("[data-bdh-tracker]");
	if (!root) return;
	const KEY = BDH.storage_keys.project;

	const form    = root.querySelector("[data-bdh-tracker-form]");
	const listEl  = root.querySelector("[data-bdh-tracker-list]");
	const barsEl  = root.querySelector("[data-bdh-tracker-bars]");
	const pbarsEl = root.querySelector("[data-bdh-tracker-progressbars]");

	let state = BDH.storage.get(KEY, { projects: [] });

	function persist() { BDH.storage.set(KEY, state); }

	function render() {
		if (!state.projects.length) {
			listEl.innerHTML = '<p class="bdh-tracker-empty">No active projects yet. Add your first build to start tracking.</p>';
			barsEl.innerHTML = "";
			pbarsEl.innerHTML = "";
			return;
		}
		listEl.innerHTML = state.projects.map((p, idx) => `
			<div class="bdh-tracker-project">
				<div class="bdh-tracker-project-head">
					<div>
						<strong>${BDH.escapeHtml(p.name)}</strong>
						<small>${BDH.escapeHtml(p.material || '—')} · ${BDH.escapeHtml(p.feet || '0')} ft</small>
					</div>
					<div style="display:flex;gap:6px;">
						<button class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-prog="${idx}" data-delta="-10">−10%</button>
						<button class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-prog="${idx}" data-delta="10">+10%</button>
						<button class="bdh-btn bdh-btn-sm bdh-btn-ghost" data-del="${idx}">×</button>
					</div>
				</div>
				<div class="bdh-tracker-progress"><div class="bdh-tracker-progress-bar" style="width:${p.progress || 0}%"></div></div>
				<small style="display:block;margin-top:4px;color:var(--bdh-text-soft);">Progress: ${p.progress || 0}%</small>
			</div>
		`).join("");

		// Material usage bars
		const maxFeet = Math.max(1, ...state.projects.map(p => +p.feet || 0));
		barsEl.innerHTML = state.projects.map(p => {
			const pct = ((+p.feet || 0) / maxFeet) * 100;
			return `<div class="bdh-tracker-bar"><small>${BDH.escapeHtml(p.name.slice(0, 14))}</small><div class="bdh-tracker-bar-fill"><span style="width:${pct}%"></span></div><small>${(+p.feet||0).toFixed(1)} ft</small></div>`;
		}).join("");

		pbarsEl.innerHTML = state.projects.map(p => {
			return `<div class="bdh-tracker-bar"><small>${BDH.escapeHtml(p.name.slice(0, 14))}</small><div class="bdh-tracker-bar-fill"><span style="width:${p.progress||0}%"></span></div><small>${p.progress||0}%</small></div>`;
		}).join("");
	}

	BDH.on(form, "submit", e => {
		e.preventDefault();
		const data = Object.fromEntries(new FormData(form).entries());
		state.projects.push({ name: data.name, material: data.material, feet: +data.feet || 0, progress: +data.progress || 0 });
		persist(); render(); form.reset();
		BDH.toast("Project tracked", "success");
	});

	BDH.delegate(listEl, "click", "[data-del]", (e, b) => { state.projects.splice(+b.dataset.del, 1); persist(); render(); });
	BDH.delegate(listEl, "click", "[data-prog]", (e, b) => {
		const i = +b.dataset.prog;
		state.projects[i].progress = Math.max(0, Math.min(100, (+state.projects[i].progress || 0) + (+b.dataset.delta)));
		persist(); render();
	});

	BDH.on(root.querySelector("[data-bdh-tracker-export]"), "click", () => {
		const blob = new Blob([JSON.stringify(state, null, 2)], { type: "application/json" });
		const url = URL.createObjectURL(blob);
		const a = document.createElement("a"); a.href = url; a.download = "bendershub-projects.json";
		document.body.appendChild(a); a.click(); a.remove();
	});
	BDH.on(root.querySelector("[data-bdh-tracker-clear]"), "click", () => {
		if (confirm("Clear all tracked projects?")) { state.projects = []; persist(); render(); }
	});

	render();
})();
