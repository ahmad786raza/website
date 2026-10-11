/*
 * Magazine interactions: tool console tabs (with anchor routing for v1 links
 * such as /#bdh-calculator), table-of-contents highlighting, load-more feed,
 * sticky header state.
 */
(function () {
	"use strict";
	const BDH = window.BDH || {};
	const $$ = (s, c) => Array.from((c || document).querySelectorAll(s));

	/* ---------- Sticky header shadow ---------- */
	const header = document.getElementById("bdh-masthead");
	if (header) {
		const onScroll = () => header.classList.toggle("is-scrolled", window.scrollY > 8);
		window.addEventListener("scroll", onScroll, { passive: true });
		onScroll();
	}

	/* ---------- Tool console ---------- */
	const consoleEl = document.querySelector("[data-bdh-console]");
	const tabs = consoleEl ? $$('[role="tab"]', consoleEl) : [];

	function activate(tab, focus) {
		if (!tab) return;
		tabs.forEach((t) => {
			const on = t === tab;
			t.setAttribute("aria-selected", on ? "true" : "false");
			t.tabIndex = on ? 0 : -1;
			const panel = document.getElementById(t.getAttribute("aria-controls"));
			if (panel) panel.hidden = !on;
		});
		if (focus) tab.focus();
	}

	function tabForAnchor(id) {
		return tabs.find((t) => t.getAttribute("data-bdh-anchor") === id);
	}

	function routeHash(scroll) {
		const id = decodeURIComponent((location.hash || "").slice(1));
		if (!id) return false;
		const tab = tabForAnchor(id);
		if (tab) {
			activate(tab, false);
			if (scroll) {
				const target = document.getElementById(id) || consoleEl;
				requestAnimationFrame(() => target.scrollIntoView({ behavior: BDH.reducedMotion ? "auto" : "smooth", block: "start" }));
			}
			return true;
		}
		return false;
	}

	if (consoleEl) {
		tabs.forEach((t) => t.addEventListener("click", () => {
			activate(t, false);
			if (history.replaceState) history.replaceState(null, "", "#" + t.getAttribute("data-bdh-anchor"));
		}));
		const primary = $$('.bdh-console-tabs [role="tab"]', consoleEl);
		consoleEl.addEventListener("keydown", (e) => {
			const cur = e.target.closest('[role="tab"]');
			if (!cur) return;
			const list = cur.closest('[role="tablist"]') ? $$('[role="tab"]', cur.closest('[role="tablist"]')) : primary;
			const i = list.indexOf(cur);
			let next = null;
			if (e.key === "ArrowRight" || e.key === "ArrowDown") next = list[(i + 1) % list.length];
			else if (e.key === "ArrowLeft" || e.key === "ArrowUp") next = list[(i - 1 + list.length) % list.length];
			else if (e.key === "Home") next = list[0];
			else if (e.key === "End") next = list[list.length - 1];
			if (next) { e.preventDefault(); activate(next, true); }
		});
		// Deep links: /#bdh-calculator etc. (also catches in-page links to tools).
		routeHash(true);
		window.addEventListener("hashchange", () => routeHash(true));
		// ?profile= from pathway cards opens the quiz tab.
		if (/[?&]profile=/.test(location.search)) activate(tabForAnchor("bdh-quiz"), false);
	}

	// In-page links to a tool anchor activate the tab even if the hash is unchanged.
	document.addEventListener("click", (e) => {
		const a = e.target.closest('a[href*="#bdh-"]');
		if (!a || !consoleEl) return;
		const url = new URL(a.href, location.href);
		if (url.pathname !== location.pathname) return;
		const tab = tabForAnchor(url.hash.slice(1));
		if (!tab) return;
		e.preventDefault();
		activate(tab, false);
		if (history.pushState) history.pushState(null, "", url.hash);
		(document.getElementById(url.hash.slice(1)) || consoleEl).scrollIntoView({ behavior: BDH.reducedMotion ? "auto" : "smooth", block: "start" });
	});

	/* ---------- Table of contents: highlight the current section ---------- */
	const toc = document.querySelector("[data-bdh-toc]");
	if (toc && "IntersectionObserver" in window) {
		const links = $$("a", toc);
		const map = new Map(links.map((a) => [decodeURIComponent(a.hash.slice(1)), a]));
		const headings = Array.from(map.keys()).map((id) => document.getElementById(id)).filter(Boolean);
		const obs = new IntersectionObserver((entries) => {
			entries.forEach((en) => {
				if (en.isIntersecting) {
					links.forEach((l) => l.removeAttribute("aria-current"));
					const link = map.get(en.target.id);
					if (link) link.setAttribute("aria-current", "true");
				}
			});
		}, { rootMargin: "-80px 0px -70% 0px" });
		headings.forEach((h) => obs.observe(h));
	}
	// Close the inline (mobile) TOC after choosing a section.
	$$(".bdh-toc--inline a").forEach((a) => a.addEventListener("click", () => { const d = a.closest("details"); if (d) d.open = false; }));

	/* ---------- Load more (homepage feed) ---------- */
	const more = document.querySelector("[data-bdh-load-more]");
	const feed = document.querySelector("[data-bdh-feed]");
	if (more && feed && BDH.ajax_url) {
		more.addEventListener("click", async (e) => {
			e.preventDefault();
			const page = parseInt(more.getAttribute("data-page"), 10) + 1;
			const label = more.textContent;
			more.setAttribute("aria-busy", "true");
			more.textContent = (BDH.i18n && BDH.i18n.loading) || "Loading…";
			try {
				const url = BDH.ajax_url + "?action=bdh_load_more&page=" + page + "&per=" + more.getAttribute("data-per") + "&exclude=" + encodeURIComponent(more.getAttribute("data-exclude") || "");
				const res = await fetch(url, { credentials: "same-origin" });
				const data = await res.json();
				if (data && data.success && data.data.html) {
					const tmp = document.createElement("div");
					tmp.innerHTML = data.data.html;
					const first = tmp.firstElementChild;
					Array.from(tmp.children).forEach((c) => feed.appendChild(c));
					more.setAttribute("data-page", String(page));
					if (first) { const link = first.querySelector(".bdh-card-title a"); if (link) link.focus({ preventScroll: true }); }
					if (!data.data.more) { more.parentElement.innerHTML = '<p class="bdh-feed-end">' + ((BDH.i18n && BDH.i18n.no_more) || "That's everything for now.") + "</p>"; return; }
				}
				more.textContent = label;
			} catch (err) {
				more.textContent = label;
				if (BDH.toast) BDH.toast((BDH.i18n && BDH.i18n.network_error) || "Network error", "error");
			}
			more.removeAttribute("aria-busy");
		});
	}
})();
