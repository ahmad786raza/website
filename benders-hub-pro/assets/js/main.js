/*
 * Benders Hub Pro — Main JS
 * No jQuery. Vanilla ES2017+, all defers.
 */
(function () {
	"use strict";

	window.BDH = window.BDH || {};

	const $  = (sel, ctx = document) => ctx.querySelector(sel);
	const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

	BDH.$ = $;
	BDH.$$ = $$;

	BDH.on = function (el, evt, fn, opts) {
		if (!el) return;
		el.addEventListener(evt, fn, opts);
	};

	BDH.delegate = function (root, evt, sel, fn) {
		root.addEventListener(evt, function (e) {
			const target = e.target.closest(sel);
			if (target && root.contains(target)) {
				fn.call(target, e, target);
			}
		});
	};

	BDH.html = function (strings, ...values) {
		return strings.reduce((acc, s, i) => acc + s + (values[i] == null ? "" : String(values[i])), "");
	};

	BDH.escapeHtml = function (str) {
		return String(str).replace(/[&<>"']/g, (ch) => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[ch]));
	};

	BDH.amazonUrl = function (keyword) {
		const tag = (window.BDH && window.BDH.amazon_tag) || "rcblogs-20";
		return "https://www.amazon.com/s?k=" + encodeURIComponent(keyword) + "&tag=" + encodeURIComponent(tag);
	};

	BDH.storage = {
		get(key, fallback = null) {
			try { const raw = localStorage.getItem(key); return raw ? JSON.parse(raw) : fallback; }
			catch (e) { return fallback; }
		},
		set(key, value) {
			try { localStorage.setItem(key, JSON.stringify(value)); return true; }
			catch (e) { return false; }
		},
		remove(key) { try { localStorage.removeItem(key); } catch (e) {} },
	};

	BDH.toast = function (msg, type = "info") {
		let host = $("#bdh-toast-host");
		if (!host) {
			host = document.createElement("div");
			host.id = "bdh-toast-host";
			host.style.cssText = "position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;";
			document.body.appendChild(host);
		}
		const el = document.createElement("div");
		el.className = "bdh-toast bdh-toast-" + type;
		el.textContent = msg;
		el.style.cssText = "background:#1f2937;color:#fff;padding:12px 16px;border-radius:10px;border:1px solid rgba(245,158,11,0.4);box-shadow:0 8px 24px rgba(0,0,0,0.4);font-size:0.9rem;animation:bdh-toast-in 0.3s ease;";
		if (type === "success") el.style.borderColor = "rgba(16,185,129,0.5)";
		if (type === "error")   el.style.borderColor = "rgba(239,68,68,0.5)";
		host.appendChild(el);
		setTimeout(() => { el.style.transition = "opacity 0.3s, transform 0.3s"; el.style.opacity = "0"; el.style.transform = "translateY(8px)"; }, 3000);
		setTimeout(() => { el.remove(); }, 3400);
	};

	/**
	 * Header — toggle panels.
	 */
	BDH.delegate(document, "click", "[data-bdh-toggle]", function (e, target) {
		e.preventDefault();
		const panel = target.getAttribute("data-bdh-toggle");
		const el = $('[data-bdh-panel="' + panel + '"]');
		if (!el) return;
		const hidden = el.hasAttribute("hidden");
		if (hidden) {
			$$('[data-bdh-panel]').forEach(p => p.setAttribute("hidden", ""));
			el.removeAttribute("hidden");
			target.setAttribute("aria-expanded", "true");
		} else {
			el.setAttribute("hidden", "");
			target.setAttribute("aria-expanded", "false");
		}
	});

	/**
	 * Newsletter submit (any form with data-bdh-newsletter).
	 */
	BDH.delegate(document, "submit", "[data-bdh-newsletter]", async function (e, form) {
		e.preventDefault();
		const email = (form.querySelector('input[type="email"]') || {}).value || "";
		const msgEl = form.querySelector("[data-bdh-newsletter-msg]");
		const setMsg = (text, err = false) => {
			if (msgEl) { msgEl.textContent = text; msgEl.classList.toggle("error", err); }
			else { BDH.toast(text, err ? "error" : "success"); }
		};

		if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
			setMsg(BDH.i18n.loading || "Invalid email.", true);
			return;
		}

		try {
			const fd = new FormData();
			fd.append("action", "bdh_newsletter");
			fd.append("nonce", BDH.nonce);
			fd.append("email", email);
			const res = await fetch(BDH.ajax_url, { method: "POST", body: fd, credentials: "same-origin" });
			const data = await res.json();
			if (data.success) {
				setMsg(data.data.message || "Subscribed!");
				form.reset();
			} else {
				setMsg(data.data.message || "Try again.", true);
			}
		} catch (err) {
			setMsg("Network error. Try again.", true);
		}
	});

	/**
	 * Hero Live Spec Console — mirror calculator/visualizer state from localStorage.
	 */
	function refreshHeroSpec() {
		const panel = $("[data-bdh-hero-spec]");
		if (!panel) return;
		const snap = BDH.storage.get("bdh_spec_snapshot");
		if (!snap) return;

		const materialLabels = {
			mild: "Mild Steel", chromoly: "Chromoly 4130", stainless: "T304 Stainless",
			stainless_t321: "T321 Stainless", aluminum: "Aluminum 6061", copper: "Copper",
		};
		const matLabel = materialLabels[snap.material] || snap.material || "Mild Steel";

		const set = (key, value) => {
			const el = panel.querySelector('[data-bdh-spec="' + key + '"]');
			if (el) el.textContent = value;
		};
		set("od",        snap.od + '"');
		set("wall",      snap.wall + '"');
		set("clr",       snap.clr + '"');
		set("angle",     snap.angle + "°");
		set("material",  matLabel);
		set("springback","+" + snap.springback + "°");

		const status = panel.querySelector("[data-bdh-spec-status]");
		if (status) {
			status.textContent = snap.wrinkle === "HIGH" ? "REVIEW" : (snap.wrinkle === "MED" || snap.wrinkle === "MEDIUM") ? "CAUTION" : "OPTIMAL";
			status.style.background =
				snap.wrinkle === "HIGH" ? "rgba(239,68,68,0.15)"
				: (snap.wrinkle === "MED" || snap.wrinkle === "MEDIUM") ? "rgba(245,158,11,0.15)"
				: "rgba(16,185,129,0.15)";
			status.style.color =
				snap.wrinkle === "HIGH" ? "#fca5a5"
				: (snap.wrinkle === "MED" || snap.wrinkle === "MEDIUM") ? "var(--bdh-amber)"
				: "#6ee7b7";
		}
		const title = panel.querySelector("[data-bdh-spec-title]");
		if (title) title.textContent = "Last bend preview";
	}
	refreshHeroSpec();
	window.addEventListener("bdh:spec:update", refreshHeroSpec);
	window.addEventListener("storage", function (e) {
		if (e.key === "bdh_spec_snapshot") refreshHeroSpec();
	});

	/**
	 * Smooth-scroll for in-page anchors with offset for sticky header.
	 */
	BDH.delegate(document, "click", 'a[href^="#"]', function (e, link) {
		const href = link.getAttribute("href");
		if (href === "#" || href.length < 2) return;
		const target = document.querySelector(href);
		if (!target) return;
		e.preventDefault();
		const offset = 80;
		const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
		window.scrollTo({ top, behavior: "smooth" });
		// close mobile menu if open
		const mm = $('[data-bdh-panel="mobile-menu"]');
		if (mm && !mm.hasAttribute("hidden")) mm.setAttribute("hidden", "");
	});

})();
