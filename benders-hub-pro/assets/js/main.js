/*
 * Benders Hub — core helpers shared by every page and tool.
 * Vanilla JS, deferred. The localized data object (window.BDH) is printed by
 * wp_localize_script before this file runs.
 */
(function () {
	"use strict";

	window.BDH = window.BDH || {};
	const BDH = window.BDH;

	BDH.$  = (sel, ctx = document) => ctx.querySelector(sel);
	BDH.$$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
	BDH.on = (el, evt, fn, opts) => { if (el) el.addEventListener(evt, fn, opts); };
	BDH.delegate = (root, evt, sel, fn) => {
		root.addEventListener(evt, (e) => {
			const target = e.target.closest(sel);
			if (target && root.contains(target)) fn.call(target, e, target);
		});
	};
	BDH.escapeHtml = (str) => String(str).replace(/[&<>"']/g, (ch) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[ch]));
	BDH.reducedMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

	BDH.amazonUrl = (keyword) => {
		const tag = BDH.amazon_tag || "rcblogs-20";
		return "https://www.amazon.com/s?k=" + encodeURIComponent(keyword) + "&tag=" + encodeURIComponent(tag);
	};

	BDH.storage = {
		get(key, fallback = null) { try { const raw = localStorage.getItem(key); return raw ? JSON.parse(raw) : fallback; } catch (e) { return fallback; } },
		set(key, value) { try { localStorage.setItem(key, JSON.stringify(value)); return true; } catch (e) { return false; } },
		remove(key) { try { localStorage.removeItem(key); } catch (e) {} },
	};

	BDH.toast = (msg, type = "info") => {
		let host = document.getElementById("bdh-toast-host");
		if (!host) {
			host = document.createElement("div");
			host.id = "bdh-toast-host";
			host.setAttribute("role", "status");
			host.setAttribute("aria-live", "polite");
			document.body.appendChild(host);
		}
		const el = document.createElement("div");
		el.className = "bdh-toast bdh-toast--" + type;
		el.textContent = msg;
		host.appendChild(el);
		setTimeout(() => el.classList.add("is-leaving"), 3000);
		setTimeout(() => el.remove(), 3400);
	};

	/* Header panels: search + mobile menu. */
	function closePanels(except) {
		BDH.$$("[data-bdh-panel]").forEach((p) => { if (p !== except) p.hidden = true; });
		BDH.$$("[data-bdh-toggle]").forEach((b) => { if (!except || b.getAttribute("aria-controls") !== except.id) b.setAttribute("aria-expanded", "false"); });
		document.body.classList.toggle("bdh-menu-open", !!(except && except.id === "bdh-mobile-menu"));
	}
	BDH.delegate(document, "click", "[data-bdh-toggle]", (e, btn) => {
		e.preventDefault();
		const panel = document.querySelector('[data-bdh-panel="' + btn.getAttribute("data-bdh-toggle") + '"]');
		if (!panel) return;
		const opening = panel.hidden;
		closePanels(opening ? panel : null);
		panel.hidden = !opening;
		btn.setAttribute("aria-expanded", opening ? "true" : "false");
		if (opening) {
			const focusable = panel.querySelector("input, a, button");
			if (focusable) focusable.focus();
		}
	});
	document.addEventListener("keydown", (e) => {
		if (e.key !== "Escape") return;
		const open = BDH.$$("[data-bdh-panel]").find((p) => !p.hidden);
		if (!open) return;
		closePanels(null);
		const btn = document.querySelector('[aria-controls="' + open.id + '"]');
		if (btn) btn.focus();
	});

	/* Newsletter forms (stored server-side; honest success/failure messages). */
	BDH.delegate(document, "submit", "[data-bdh-newsletter]", async (e, form) => {
		e.preventDefault();
		const input = form.querySelector('input[type="email"]');
		const msg = form.querySelector("[data-bdh-newsletter-msg]");
		const say = (text, err) => { if (msg) { msg.textContent = text; msg.classList.toggle("is-error", !!err); } else { BDH.toast(text, err ? "error" : "success"); } };
		const email = (input && input.value || "").trim();
		if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) { say((BDH.i18n && BDH.i18n.invalid_email) || "Please enter a valid email address.", true); if (input) input.focus(); return; }
		const btn = form.querySelector("button[type=submit]");
		if (btn) btn.disabled = true;
		try {
			const fd = new FormData();
			fd.append("action", "bdh_newsletter");
			fd.append("email", email);
			fd.append("website", (form.querySelector('input[name="website"]') || {}).value || "");
			fd.append("source", location.pathname);
			const res = await fetch(BDH.ajax_url, { method: "POST", body: fd, credentials: "same-origin" });
			const data = await res.json();
			if (data && data.success) { say(data.data.message); form.reset(); }
			else { say((data && data.data && data.data.message) || "Please try again.", true); }
		} catch (err) {
			say((BDH.i18n && BDH.i18n.network_error) || "Network error — please try again.", true);
		} finally {
			if (btn) btn.disabled = false;
		}
	});

	/* Print buttons. */
	BDH.delegate(document, "click", "[data-bdh-print]", () => window.print());
})();
