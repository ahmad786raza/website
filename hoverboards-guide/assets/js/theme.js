/**
 * Hoverboards Guide v3 — theme JavaScript (vanilla, deferred, no dependencies).
 * Header state, drawer, search overlay, finder, comparison table, FAQ, load more,
 * table of contents, share, view beacon, anchor scrolling and lightweight analytics events.
 */
(function () {
	'use strict';

	var D = window.hbgData || {};
	var I18N = D.i18n || {};

	function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
	function qsa(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }
	function esc(str) { return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }
	function post(action, data) {
		var body = new FormData();
		body.append('action', action);
		body.append('nonce', D.nonce || '');
		Object.keys(data || {}).forEach(function (k) { body.append(k, data[k]); });
		return fetch(D.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: body }).then(function (r) { return r.json(); });
	}
	function throttle(fn, wait) {
		var last = 0, timer = null;
		return function () {
			var now = Date.now();
			if (now - last >= wait) { last = now; fn(); }
			else if (!timer) { timer = setTimeout(function () { timer = null; last = Date.now(); fn(); }, wait - (now - last)); }
		};
	}
	/** Google Analytics 4 event via Site Kit's gtag, when present and enabled. */
	function track(name, params) {
		if (!D.track || typeof window.gtag !== 'function') return;
		try { window.gtag('event', name, params || {}); } catch (e) { /* ignore */ }
	}

	/* Header shadow on scroll */
	function initHeader() {
		var header = qs('#site-header');
		if (!header) return;
		var update = function () { header.classList.toggle('is-scrolled', (window.scrollY || 0) > 4); };
		window.addEventListener('scroll', throttle(update, 80), { passive: true });
		update();
	}

	/* Mobile drawer */
	function initDrawer() {
		var drawer = qs('#mobile-drawer');
		var toggle = qs('.nav-toggle');
		if (!drawer || !toggle) return;
		var lastFocus = null;
		function open() {
			lastFocus = document.activeElement;
			drawer.classList.add('is-open');
			drawer.setAttribute('aria-hidden', 'false');
			toggle.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';
			var first = qs('.drawer__close', drawer);
			if (first) first.focus();
		}
		function close() {
			drawer.classList.remove('is-open');
			drawer.setAttribute('aria-hidden', 'true');
			toggle.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
			if (lastFocus) lastFocus.focus();
		}
		toggle.addEventListener('click', open);
		qsa('[data-drawer-close]', drawer).forEach(function (el) { el.addEventListener('click', close); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && drawer.classList.contains('is-open')) close(); });
		qsa('a[href*="#"]', drawer).forEach(function (a) { a.addEventListener('click', close); });
		qsa('.nav-menu--mobile > li.menu-item-has-children', drawer).forEach(function (li) {
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'submenu-toggle';
			btn.setAttribute('aria-expanded', 'false');
			btn.setAttribute('aria-label', 'Toggle submenu');
			btn.innerHTML = '<svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';
			var link = li.querySelector(':scope > a');
			if (link) link.insertAdjacentElement('afterend', btn);
			btn.addEventListener('click', function () {
				var on = li.classList.toggle('is-open');
				btn.setAttribute('aria-expanded', on ? 'true' : 'false');
			});
		});
	}

	/* Desktop dropdown keyboard support */
	function initDesktopNav() {
		qsa('.primary-nav .menu-item-has-children').forEach(function (li) {
			li.addEventListener('keydown', function (e) {
				if (e.key === 'Escape') { li.classList.remove('is-open'); var a = li.querySelector('a'); if (a) a.focus(); }
			});
			var link = li.querySelector(':scope > a');
			if (link) link.addEventListener('keydown', function (e) {
				if (e.key === 'ArrowDown') { e.preventDefault(); li.classList.add('is-open'); var f = li.querySelector('.sub-menu a'); if (f) f.focus(); }
			});
		});
	}

	/* Search overlay */
	function initSearch() {
		var overlay = qs('#search-overlay');
		var toggle = qs('.search-toggle');
		if (!overlay || !toggle) return;
		var input = qs('.search-field', overlay);
		var lastFocus = null;
		function open() {
			lastFocus = document.activeElement;
			overlay.classList.add('is-open');
			overlay.setAttribute('aria-hidden', 'false');
			toggle.setAttribute('aria-expanded', 'true');
			setTimeout(function () { if (input) input.focus(); }, 50);
		}
		function close() {
			overlay.classList.remove('is-open');
			overlay.setAttribute('aria-hidden', 'true');
			toggle.setAttribute('aria-expanded', 'false');
			if (lastFocus) lastFocus.focus();
		}
		toggle.addEventListener('click', function () { overlay.classList.contains('is-open') ? close() : open(); });
		var closeBtn = qs('.search-close', overlay);
		if (closeBtn) closeBtn.addEventListener('click', close);
		overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && overlay.classList.contains('is-open')) close();
			if (e.key === '/' && !/input|textarea|select/i.test(document.activeElement.tagName)) { e.preventDefault(); open(); }
		});
	}

	/* FAQ accordion (buttons with aria-expanded + hidden panels) */
	function initFAQ() {
		qsa('.faq__trigger').forEach(function (btn) {
			var panel = document.getElementById(btn.getAttribute('aria-controls'));
			if (!panel) return;
			btn.addEventListener('click', function () {
				var open = btn.getAttribute('aria-expanded') === 'true';
				btn.setAttribute('aria-expanded', open ? 'false' : 'true');
				panel.hidden = open;
			});
		});
	}

	/* Finder: rules-based, data from PHP */
	function initFinder() {
		var panel = qs('[data-finder]');
		if (!panel || !D.finder) return;
		var steps = qsa('.finder__step', panel);
		var result = qs('.finder__result', panel);
		var bars = qsa('.finder__progress li', panel);
		var answers = {};
		var current = 0;

		function show(i) {
			current = i;
			steps.forEach(function (s, idx) { s.classList.toggle('is-active', idx === i); });
			bars.forEach(function (b, idx) { b.classList.toggle('is-active', idx <= i); });
			if (result) result.hidden = true;
			var legend = qs('.finder__question', steps[i]);
			if (legend) legend.setAttribute('tabindex', '-1');
			if (legend && i > 0) legend.focus();
		}
		function pick() {
			var rules = D.finder.rules || [];
			for (var r = 0; r < rules.length; r++) {
				var rule = rules[r], ok = true;
				for (var k in rule) {
					if (k === 'id' || k === 'guides' || !rule.hasOwnProperty(k)) continue;
					if (answers[k] !== rule[k]) { ok = false; break; }
				}
				if (ok) return rule;
			}
			return null;
		}
		function render(rule) {
			if (!rule || !result) return;
			var p = (D.products || {})[rule.id];
			var guides = qs('[data-finder-guides]', result);
			if (guides) {
				guides.innerHTML = (rule.guides || []).map(function (key) {
					var g = (D.guides || {})[key];
					return g ? '<li><a href="' + esc(g.url) + '" data-hbg-track="finder-guide">' + esc(g.title) + '</a></li>' : '';
				}).join('');
			}
			var pickWrap = qs('.finder__pick', result);
			if (!p) { if (pickWrap) pickWrap.hidden = true; return; }
			if (pickWrap) pickWrap.hidden = false;
			qs('[data-finder-name]', result).textContent = p.name;
			qs('[data-finder-why]', result).textContent = p.why || p.best_for || '';
			qs('[data-finder-image]', result).innerHTML = p.image || '';
			qs('[data-finder-specs]', result).innerHTML = (p.specs || []).filter(function (s) { return s.value; }).map(function (s) { return '<div><dt>' + esc(s.label) + '</dt><dd>' + esc(s.value) + '</dd></div>'; }).join('');
			var link = qs('[data-finder-link]', result);
			if (link) { link.href = p.url; link.setAttribute('data-product', p.id); }
			var review = qs('[data-finder-review]', result);
			if (review) { review.hidden = !p.review; if (p.review) review.href = p.review; }
			qs('[data-finder-note]', result).textContent = p.verified ? (I18N.verified || '') : (I18N.advertised || '');
		}
		function finish() {
			var rule = pick();
			render(rule);
			steps.forEach(function (s) { s.classList.remove('is-active'); });
			bars.forEach(function (b) { b.classList.add('is-active'); });
			if (result) { result.hidden = false; result.setAttribute('tabindex', '-1'); result.focus(); }
			track('finder_complete', { who: answers.who, terrain: answers.terrain, budget: answers.budget, priority: answers.priority, product: rule ? rule.id : '' });
		}
		steps.forEach(function (step, idx) {
			var key = step.getAttribute('data-key');
			qsa('.finder__option', step).forEach(function (btn) {
				btn.addEventListener('click', function () {
					answers[key] = btn.getAttribute('data-value');
					if (idx + 1 < steps.length) show(idx + 1); else finish();
				});
			});
			var back = qs('[data-finder-back]', step);
			if (back) back.addEventListener('click', function () { if (current > 0) show(current - 1); });
		});
		var restart = qs('[data-finder-restart]', panel);
		if (restart) restart.addEventListener('click', function () { answers = {}; show(0); });
		show(0);
	}

	/* Comparison table: filter + sort */
	function initCompare() {
		qsa('.compare').forEach(function (section) {
			var table = qs('.compare-table', section);
			if (!table) return;
			var tbody = qs('tbody', table);
			var rows = qsa('tbody tr', table);
			var filterBtns = qsa('.seg__btn', section);
			var sortSel = qs('[data-sort]', section);
			var empty = qs('.compare__empty', section);
			var filter = 'all';
			function render() {
				var visible = 0;
				rows.forEach(function (r) {
					var tags = (r.getAttribute('data-tags') || '').split(' ');
					var show = filter === 'all' || tags.indexOf(filter) !== -1;
					r.hidden = !show;
					if (show) visible++;
				});
				var key = sortSel ? sortSel.value : '';
				rows.slice().sort(function (a, b) {
					if (!key) return parseInt(a.getAttribute('data-order'), 10) - parseInt(b.getAttribute('data-order'), 10);
					return (parseFloat(b.getAttribute('data-' + key)) || 0) - (parseFloat(a.getAttribute('data-' + key)) || 0);
				}).forEach(function (r) { tbody.appendChild(r); });
				var n = 0;
				qsa('tr', tbody).forEach(function (r) {
					r.classList.remove('is-even', 'is-odd');
					if (r.hidden) return;
					n++;
					r.classList.add(n % 2 === 0 ? 'is-even' : 'is-odd');
				});
				if (empty) empty.hidden = visible > 0;
			}
			filterBtns.forEach(function (btn) {
				btn.addEventListener('click', function () {
					filterBtns.forEach(function (b) { b.classList.remove('is-active'); b.setAttribute('aria-pressed', 'false'); });
					btn.classList.add('is-active');
					btn.setAttribute('aria-pressed', 'true');
					filter = btn.getAttribute('data-filter');
					render();
					track('compare_filter', { filter: filter });
				});
			});
			if (sortSel) sortSel.addEventListener('change', function () { render(); track('compare_sort', { sort: sortSel.value }); });
			// Mark the highest advertised figure in the rider-weight, speed and range columns (relative to the boards listed)
			[['weight', 3], ['speed', 4], ['range', 5]].forEach(function (c) {
				var best = -1;
				rows.forEach(function (r) { var v = parseFloat(r.getAttribute('data-' + c[0])) || 0; if (v > best) best = v; });
				if (best <= 0) return;
				rows.forEach(function (r) {
					var v = parseFloat(r.getAttribute('data-' + c[0])) || 0, cell = r.children[c[1]];
					if (cell && v === best && !qs('.best-tag', cell)) { cell.classList.add('is-best'); var t = document.createElement('span'); t.className = 'best-tag'; t.textContent = 'Highest'; cell.appendChild(t); }
				});
			});
			render();
		});
	}

	/* Load more posts (AJAX) */
	function initLoadMore() {
		var btn = qs('[data-load-more]');
		var grid = qs('[data-load-more-grid]');
		if (!btn || !grid || !D.ajaxUrl) return;
		var label = btn.textContent;
		btn.addEventListener('click', function () {
			var page = parseInt(btn.getAttribute('data-page') || '0', 10) + 1;
			btn.disabled = true;
			btn.textContent = I18N.loading || 'Loading…';
			post('hbg_load_more', { page: page, per_page: btn.getAttribute('data-per-page') || 6, exclude: btn.getAttribute('data-exclude') || '', cat: btn.getAttribute('data-cat') || 0 })
				.then(function (res) {
					if (!res || !res.success) throw new Error('bad response');
					var tmp = document.createElement('div');
					tmp.innerHTML = res.data.html;
					var first = null;
					qsa('.card', tmp).forEach(function (card) { card.classList.add('is-new'); grid.appendChild(card); if (!first) first = card; });
					btn.setAttribute('data-page', page);
					btn.disabled = false;
					btn.textContent = label;
					if (!res.data.has_more) { btn.textContent = I18N.noMore || 'That’s everything for now'; btn.disabled = true; }
					if (first) { var a = qs('a.card__title, .card__title a', first); if (a) a.focus(); }
				})
				.catch(function () { btn.disabled = false; btn.textContent = I18N.error || 'Something went wrong. Please try again.'; });
		});
	}

	/* Share: copy link / native share */
	function initShare() {
		qsa('.share').forEach(function (bar) {
			var url = bar.getAttribute('data-url') || location.href;
			var title = bar.getAttribute('data-title') || document.title;
			var copy = qs('[data-copy]', bar);
			if (!copy) return;
			copy.addEventListener('click', function () {
				if (navigator.share && /Android|iPhone|iPad/i.test(navigator.userAgent)) { navigator.share({ title: title, url: url }).catch(function () {}); return; }
				var done = function () { copy.classList.add('is-copied'); copy.setAttribute('title', I18N.copied || 'Link copied'); setTimeout(function () { copy.classList.remove('is-copied'); }, 1800); };
				if (navigator.clipboard) navigator.clipboard.writeText(url).then(done);
			});
		});
	}

	/* Table of contents: collapse on small screens + active section */
	function initTOC() {
		var toc = qs('[data-toc]');
		if (!toc) return;
		var toggle = qs('.toc__toggle', toc);
		if (toggle) {
			toggle.addEventListener('click', function () {
				var collapsed = toc.classList.toggle('is-collapsed');
				toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
			});
			if (window.innerWidth < 700) { toc.classList.add('is-collapsed'); toggle.setAttribute('aria-expanded', 'false'); }
		}
		var links = qsa('.toc__list a', toc);
		var targets = links.map(function (a) { return document.getElementById((a.getAttribute('href') || '').slice(1)); }).filter(Boolean);
		if (!('IntersectionObserver' in window) || !targets.length) return;
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) links.forEach(function (a) { a.classList.toggle('is-active', a.getAttribute('href') === '#' + en.target.id); });
			});
		}, { rootMargin: '-15% 0px -70% 0px', threshold: 0 });
		targets.forEach(function (t) { io.observe(t); });
	}

	/* View counter beacon (once per post per session) */
	function initViews() {
		if (!D.postId || !D.ajaxUrl) return;
		var key = 'hbg-viewed-' + D.postId;
		try { if (sessionStorage.getItem(key)) return; } catch (e) {}
		var sent = false;
		function send() {
			if (sent) return;
			sent = true;
			try { sessionStorage.setItem(key, '1'); } catch (e) {}
			post('hbg_track_view', { post_id: D.postId }).catch(function () {});
		}
		setTimeout(send, 8000);
		window.addEventListener('scroll', function onScroll() { if ((window.scrollY || 0) > window.innerHeight * 0.5) { send(); window.removeEventListener('scroll', onScroll); } }, { passive: true });
	}

	/* Affiliate and internal click events (GA4 via gtag) */
	function initTracking() {
		document.addEventListener('click', function (e) {
			var el = e.target.closest('[data-hbg-track]');
			if (!el) return;
			var kind = el.getAttribute('data-hbg-track');
			if (kind === 'affiliate') {
				track('affiliate_click', { product: el.getAttribute('data-product') || '', placement: el.getAttribute('data-placement') || '', page: location.pathname });
			} else {
				track(kind.replace(/-/g, '_'), { page: location.pathname, href: el.getAttribute('href') || '' });
			}
		}, { passive: true });
	}

	/* Smooth anchor scrolling with header offset */
	function initAnchors() {
		document.addEventListener('click', function (e) {
			var link = e.target.closest('a[href*="#"]');
			if (!link) return;
			var href = link.getAttribute('href') || '';
			var i = href.indexOf('#');
			if (i === -1 || i === href.length - 1) return;
			var path = href.slice(0, i);
			if (path && path !== location.pathname && path !== location.href.split('#')[0] && path !== location.origin + location.pathname) return;
			var target = document.getElementById(href.slice(i + 1));
			if (!target) return;
			e.preventDefault();
			var header = qs('#site-header');
			var offset = (header && getComputedStyle(header).position === 'sticky' ? header.offsetHeight : 0) + 16;
			window.scrollTo({ top: target.getBoundingClientRect().top + (window.scrollY || 0) - offset, behavior: 'smooth' });
			target.setAttribute('tabindex', '-1');
			target.focus({ preventScroll: true });
			if (history.pushState) history.pushState(null, '', '#' + target.id);
		});
	}


	/* Reveal on scroll (homepage and archives; skipped when the visitor prefers reduced motion) */
	function initReveal() {
		if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
		var els = qsa('.pcard, .card, .topic-tile, .learn__item, .faq__item, .decision, .guide__card, .trust__method li, .hero__stat');
		if (!els.length) return;
		els.forEach(function (el) { el.classList.add('reveal'); });
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); } });
		}, { rootMargin: '0px 0px -6% 0px', threshold: 0.05 });
		els.forEach(function (el) { io.observe(el); });
		setTimeout(function () { els.forEach(function (el) { el.classList.add('is-visible'); }); }, 1200);
	}

	/* Reading progress bar on articles */
	function initProgress() {
		var bar = qs('#reading-progress span');
		var body = qs('.post-content');
		if (!bar || !body) { if (bar) bar.parentNode.hidden = true; return; }
		function upd() {
			var top = body.getBoundingClientRect().top + window.scrollY;
			var total = body.offsetHeight - window.innerHeight * 0.6;
			var done = Math.min(1, Math.max(0, (window.scrollY - top + window.innerHeight * 0.4) / (total > 0 ? total : 1)));
			bar.style.transform = 'scaleX(' + done + ')';
		}
		window.addEventListener('scroll', upd, { passive: true });
		upd();
	}

	/* Back to top */
	function initBackTop() {
		var btn = qs('.back-top');
		if (!btn) return;
		function upd() { btn.classList.toggle('is-visible', window.scrollY > 900); }
		window.addEventListener('scroll', upd, { passive: true });
		upd();
		btn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
	}

	/* Sidebar "On this page" active section */
	function initSideToc() {
		var box = qs('[data-side-toc]');
		if (!box) return;
		var links = qsa('a', box);
		var targets = links.map(function (a) { return document.getElementById((a.getAttribute('href') || '').slice(1)); });
		function upd() {
			var y = window.scrollY + 130, idx = -1;
			targets.forEach(function (t, i) { if (t && t.getBoundingClientRect().top + window.scrollY <= y) idx = i; });
			links.forEach(function (a, i) { a.classList.toggle('is-active', i === idx); });
		}
		window.addEventListener('scroll', upd, { passive: true });
		upd();
	}

	function init() {
		initHeader();
		initDrawer();
		initDesktopNav();
		initSearch();
		initFAQ();
		initFinder();
		initCompare();
		initLoadMore();
		initShare();
		initTOC();
		initViews();
		initTracking();
		initAnchors();
		initReveal();
		initProgress();
		initBackTop();
		initSideToc();
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
}());
