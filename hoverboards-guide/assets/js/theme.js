/**
 * Hoverboards Guide — theme JavaScript (v2.1, vanilla, no dependencies).
 */
(function () {
	'use strict';

	var D = window.hbgData || {};
	var I18N = D.i18n || {};
	var root = document.documentElement;

	function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
	function qsa(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }
	function escapeHTML(str) {
		return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
	}
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

	/* ---------------------------------------------------------------------
	   Header: scrolled state, reading progress, back-to-top, sticky CTA
	--------------------------------------------------------------------- */
	function initScrollUI() {
		var header = qs('.site-header');
		var progress = qs('#reading-progress');
		var toTop = qs('.back-to-top');
		var stickyCta = qs('.sticky-mobile-cta');
		var article = qs('.single__body');

		function update() {
			var y = window.scrollY || window.pageYOffset;
			if (header) header.classList.toggle('is-scrolled', y > 8);
			if (toTop) toTop.classList.toggle('is-visible', y > 700);
			if (stickyCta) stickyCta.classList.toggle('visible', y > 600);
			if (progress) {
				var top, height;
				if (article) {
					var rect = article.getBoundingClientRect();
					top = rect.top + y;
					height = rect.height;
				} else {
					top = 0;
					height = document.body.scrollHeight;
				}
				var winH = window.innerHeight;
				var pct = (y + winH - top) / (height + winH * 0.2);
				progress.style.width = Math.max(0, Math.min(1, pct)) * 100 + '%';
			}
		}
		window.addEventListener('scroll', throttle(update, 50), { passive: true });
		window.addEventListener('resize', throttle(update, 100));
		update();

		if (toTop) {
			toTop.addEventListener('click', function () {
				window.scrollTo({ top: 0, behavior: 'smooth' });
			});
		}
	}

	/* ---------------------------------------------------------------------
	   Ticker speed proportional to content width
	--------------------------------------------------------------------- */
	function initTicker() {
		var track = qs('.ticker__track');
		if (!track) return;
		var w = track.scrollWidth / 2;
		if (w > 0) track.style.setProperty('--ticker-duration', Math.max(20, Math.round(w / 55)) + 's');
	}

	/* ---------------------------------------------------------------------
	   Mobile drawer
	--------------------------------------------------------------------- */
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
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && drawer.classList.contains('is-open')) close();
		});
		// Close after choosing an in-page link so the anchor scroll is visible.
		qsa('a[href*="#"]', drawer).forEach(function (a) { a.addEventListener('click', close); });

		// Sub-menu accordions
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
				var openNow = li.classList.toggle('is-open');
				btn.setAttribute('aria-expanded', openNow ? 'true' : 'false');
			});
		});
	}

	/* ---------------------------------------------------------------------
	   Desktop dropdown keyboard support
	--------------------------------------------------------------------- */
	function initDesktopNav() {
		qsa('.primary-nav .menu-item-has-children').forEach(function (li) {
			li.addEventListener('keydown', function (e) {
				if (e.key === 'Escape') { li.classList.remove('is-open'); var a = li.querySelector('a'); if (a) a.focus(); }
			});
			var link = li.querySelector(':scope > a');
			if (link) {
				link.addEventListener('keydown', function (e) {
					if (e.key === 'ArrowDown') { e.preventDefault(); li.classList.add('is-open'); var f = li.querySelector('.sub-menu a'); if (f) f.focus(); }
				});
			}
		});
	}

	/* ---------------------------------------------------------------------
	   Search overlay
	--------------------------------------------------------------------- */
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
			if ((e.key === '/' || (e.key === 'k' && (e.metaKey || e.ctrlKey))) && !/input|textarea/i.test(document.activeElement.tagName)) {
				e.preventDefault(); open();
			}
		});
	}

	/* ---------------------------------------------------------------------
	   Dark mode toggle
	--------------------------------------------------------------------- */
	function initTheme() {
		var btn = qs('.theme-toggle');
		if (!btn) return;
		function sync() { btn.setAttribute('aria-pressed', root.getAttribute('data-scheme') === 'dark' ? 'true' : 'false'); }
		btn.addEventListener('click', function () {
			var next = root.getAttribute('data-scheme') === 'dark' ? 'light' : 'dark';
			root.setAttribute('data-scheme', next);
			try { localStorage.setItem('hbg-scheme', next); } catch (e) { /* private mode */ }
			sync();
		});
		sync();
	}

	/* ---------------------------------------------------------------------
	   Tabs (browse-by-topic, shop-by-use-case) — ARIA tabs with arrow keys.
	   Panels are all rendered server-side; without JS they simply stack.
	--------------------------------------------------------------------- */
	function initTabs() {
		qsa('[data-tabs]').forEach(function (wrap) {
			var tabs = qsa('[role="tab"]', wrap);
			var panels = qsa('[role="tabpanel"]', wrap);
			if (!tabs.length || !panels.length) return;

			function activate(tab, focus) {
				var id = tab.getAttribute('aria-controls');
				tabs.forEach(function (t) {
					var on = t === tab;
					t.classList.toggle('is-active', on);
					t.setAttribute('aria-selected', on ? 'true' : 'false');
					t.tabIndex = on ? 0 : -1;
				});
				panels.forEach(function (p) { p.classList.toggle('is-active', p.id === id); });
				if (focus) tab.focus();
				// New panels may contain not-yet-observed images / cards.
				markLoadedImages(wrap);
			}
			tabs.forEach(function (tab, i) {
				tab.addEventListener('click', function () { activate(tab); });
				tab.addEventListener('keydown', function (e) {
					var dir = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
					if (e.key === 'Home') dir = -i;
					if (e.key === 'End') dir = tabs.length - 1 - i;
					if (!dir) return;
					e.preventDefault();
					activate(tabs[(i + dir + tabs.length) % tabs.length], true);
				});
			});
			wrap.__hbgActivatePanel = function (panel) {
				var tab = tabs.filter(function (t) { return t.getAttribute('aria-controls') === panel.id; })[0];
				if (tab) activate(tab);
			};
			var initial = tabs.filter(function (t) { return t.classList.contains('is-active'); })[0] || tabs[0];
			activate(initial);
		});
	}

	/** If `target` sits inside a hidden tab panel, open that tab first. */
	function revealTarget(target) {
		if (!target || !target.closest) return;
		var panel = target.closest('[role="tabpanel"]');
		if (!panel) return;
		var wrap = panel.closest('[data-tabs]');
		if (wrap && wrap.__hbgActivatePanel && !panel.classList.contains('is-active')) wrap.__hbgActivatePanel(panel);
	}

	/* ---------------------------------------------------------------------
	   Image frames: drop the loading shimmer once the picture is in.
	--------------------------------------------------------------------- */
	var FRAMES = '.card__media, .feat__media, .post-list__thumb, .mr__thumb, .product-image, .cat-tile, .qp__thumb, .nl-banner__media, .trust-card__media, .archive-hero__bg';
	function markLoadedImages(ctx) {
		qsa(FRAMES, ctx).forEach(function (frame) {
			if (frame.classList.contains('is-loaded')) return;
			var img = frame.querySelector('img');
			if (!img) { frame.classList.add('is-loaded'); return; } // SVG placeholder — nothing to wait for
			if (img.complete && img.naturalWidth > 0) { frame.classList.add('is-loaded'); return; }
			if (img.__hbgBound) return;
			img.__hbgBound = true;
			var done = function () { frame.classList.add('is-loaded'); };
			img.addEventListener('load', done, { once: true });
			img.addEventListener('error', done, { once: true });
		});
	}
	function initImageStates() {
		markLoadedImages(document);
		var grid = qs('[data-load-more-grid]');
		if (grid && 'MutationObserver' in window) {
			new MutationObserver(function () { markLoadedImages(grid); }).observe(grid, { childList: true });
		}
		// Safety net: never leave a frame shimmering for more than 12 s (blocked image, very slow host).
		setTimeout(function () { qsa(FRAMES).forEach(function (f) { f.classList.add('is-loaded'); }); }, 12000);
	}

	/* ---------------------------------------------------------------------
	   FAQ accordion
	--------------------------------------------------------------------- */
	function initFAQ() {
		var items = qsa('.faq-item');
		if (!items.length) return;
		items.forEach(function (item) {
			var trigger = qs('.faq-item__trigger', item);
			var body = qs('.faq-item__body', item);
			if (!trigger || !body) return;
			trigger.addEventListener('click', function () {
				var isOpen = trigger.getAttribute('aria-expanded') === 'true';
				items.forEach(function (o) {
					var t = qs('.faq-item__trigger', o), b = qs('.faq-item__body', o);
					if (t && b) { t.setAttribute('aria-expanded', 'false'); b.classList.remove('open'); }
				});
				if (!isOpen) { trigger.setAttribute('aria-expanded', 'true'); body.classList.add('open'); }
			});
		});
	}

	/* ---------------------------------------------------------------------
	   Quiz (rules-based, data from PHP)
	--------------------------------------------------------------------- */
	function initQuiz() {
		var section = qs('.quiz-section');
		if (!section || !D.quiz) return;
		var steps = qsa('.quiz-step', section);
		var result = qs('.quiz-result', section);
		var bars = qsa('.quiz-progress__bar', section);
		var progressWrap = qs('.quiz-progress', section);
		var answers = {};
		var current = 0;

		function showStep(i) {
			current = i;
			steps.forEach(function (s, idx) { s.classList.toggle('active', idx === i); });
			if (result) result.classList.remove('active');
			if (progressWrap) progressWrap.style.display = '';
			bars.forEach(function (b, idx) { b.classList.toggle('active', idx <= i); });
		}
		function pick() {
			var rules = D.quiz.rules || [];
			for (var r = 0; r < rules.length; r++) {
				var rule = rules[r], ok = true;
				for (var k in rule) {
					if (k !== 'id' && rule.hasOwnProperty(k) && answers[k] !== rule[k]) { ok = false; break; }
				}
				if (ok) return rule.id;
			}
			return null;
		}
		function render(id) {
			var p = (D.products || {})[id];
			if (!p || !result) return;
			var nameEl = qs('[data-quiz-result-name]', result);
			var tagEl = qs('[data-quiz-result-tagline]', result);
			var linkEl = qs('[data-quiz-result-link]', result);
			var imgWrap = qs('[data-quiz-result-image]', result);
			var specsEl = qs('[data-quiz-result-specs]', result);
			if (nameEl) nameEl.textContent = p.name;
			if (tagEl) tagEl.textContent = p.tagline;
			if (linkEl) linkEl.href = p.url;
			if (imgWrap) {
				imgWrap.innerHTML = p.image
					? '<div class="product-image is-loaded" style="aspect-ratio:16/10"><img src="' + escapeHTML(p.image) + '" alt="' + escapeHTML(p.name) + '" loading="lazy">' + (p.note ? '<span class="product-image__note">' + escapeHTML(p.note) + '</span>' : '') + '</div>'
					: '';
			}
			if (specsEl && p.specs) {
				specsEl.innerHTML = p.specs.map(function (s) { return '<span><strong>' + escapeHTML(s.value) + '</strong> ' + escapeHTML(s.label) + '</span>'; }).join('');
			}
		}
		function finish() {
			render(pick());
			steps.forEach(function (s) { s.classList.remove('active'); });
			if (progressWrap) progressWrap.style.display = 'none';
			if (result) result.classList.add('active');
		}

		steps.forEach(function (step, idx) {
			var key = step.getAttribute('data-key');
			qsa('.quiz-option-btn', step).forEach(function (btn) {
				btn.addEventListener('click', function () {
					answers[key] = btn.getAttribute('data-value');
					if (idx + 1 < steps.length) showStep(idx + 1); else finish();
				});
			});
			var back = qs('[data-quiz-back]', step);
			if (back) back.addEventListener('click', function () { if (current > 0) showStep(current - 1); });
		});
		var retake = qs('.quiz-retake-btn', section);
		if (retake) retake.addEventListener('click', function () { answers = {}; showStep(0); });
		if (steps.length) showStep(0);
	}

	/* ---------------------------------------------------------------------
	   Compare table: filter & sort
	--------------------------------------------------------------------- */
	function initCompareTable() {
		var table = qs('.compare-table');
		if (!table) return;
		var filterTabs = qsa('.filter-tab');
		var sortTabs = qsa('.sort-tab');
		var rows = qsa('tbody tr', table);
		var tbody = qs('tbody', table);
		var filter = 'All', sortKey = 'score', dir = 'desc';

		function num(v) { return parseFloat(String(v || '').replace(/[^\d.]/g, '')) || 0; }
		function render() {
			rows.forEach(function (r) {
				var cat = r.getAttribute('data-category') || '';
				r.setAttribute('data-hidden', (filter === 'All' || cat === filter) ? 'false' : 'true');
			});
			rows.slice().sort(function (a, b) {
				var av = num(a.getAttribute('data-' + sortKey)), bv = num(b.getAttribute('data-' + sortKey));
				return dir === 'asc' ? av - bv : bv - av;
			}).forEach(function (r) { tbody.appendChild(r); });
			sortTabs.forEach(function (t) {
				var active = t.getAttribute('data-sort') === sortKey;
				t.classList.toggle('active', active);
				var arrow = qs('.sort-arrow', t);
				if (arrow) arrow.textContent = active ? (dir === 'asc' ? ' ↑' : ' ↓') : '';
			});
		}
		filterTabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				filterTabs.forEach(function (t) { t.classList.remove('active'); });
				tab.classList.add('active');
				filter = tab.getAttribute('data-filter');
				render();
			});
		});
		sortTabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				var key = tab.getAttribute('data-sort');
				if (sortKey === key) dir = dir === 'asc' ? 'desc' : 'asc';
				else { sortKey = key; dir = tab.getAttribute('data-default-dir') || 'asc'; }
				render();
			});
		});
		render();
	}

	/* ---------------------------------------------------------------------
	   Compare drawer (pick cards → highlighted rows in the table)
	--------------------------------------------------------------------- */
	function initCompareDrawer() {
		var drawer = qs('.compare-drawer');
		if (!drawer) return;
		var addBtns = qsa('.pick-card__compare-btn');
		var itemsWrap = qs('.compare-drawer__items', drawer);
		var viewLink = qs('.compare-drawer__view', drawer);
		var clearBtn = qs('.compare-drawer__clear', drawer);
		var comparing = [];
		var MAX = 4;

		function product(id) { return (D.products || {})[id] || { id: id, name: id, color: '#FF7A1A' }; }
		function render() {
			drawer.classList.toggle('visible', comparing.length > 0);
			if (itemsWrap) {
				itemsWrap.innerHTML = comparing.map(function (id) {
					var p = product(id);
					return '<span class="compare-drawer__item"><span class="compare-drawer__dot" style="background:' + escapeHTML(p.color) + '"></span>' + escapeHTML(p.name.split(' ').slice(0, 3).join(' ')) +
						'<button type="button" class="compare-drawer__remove" data-id="' + escapeHTML(id) + '" aria-label="Remove ' + escapeHTML(p.name) + '">×</button></span>';
				}).join('');
				qsa('.compare-drawer__remove', itemsWrap).forEach(function (b) {
					b.addEventListener('click', function () { remove(b.getAttribute('data-id')); });
				});
			}
			addBtns.forEach(function (btn) {
				var id = btn.getAttribute('data-product-id');
				var on = comparing.indexOf(id) !== -1;
				btn.classList.toggle('added', on);
				btn.textContent = on ? (I18N.added || '✓ Added to compare') : (I18N.add || '+ Add to compare');
				btn.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			qsa('.compare-table tbody tr').forEach(function (r) {
				r.classList.toggle('is-selected', comparing.indexOf(r.getAttribute('data-id')) !== -1);
			});
		}
		function add(id) { if (comparing.indexOf(id) === -1) { if (comparing.length >= MAX) comparing.shift(); comparing.push(id); } render(); }
		function remove(id) { comparing = comparing.filter(function (x) { return x !== id; }); render(); }

		addBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var id = btn.getAttribute('data-product-id');
				if (!id) return;
				comparing.indexOf(id) !== -1 ? remove(id) : add(id);
			});
		});
		if (clearBtn) clearBtn.addEventListener('click', function () { comparing = []; render(); });
		if (viewLink) viewLink.addEventListener('click', function () {
			var all = qs('.filter-tab[data-filter="All"]');
			if (all) all.click();
		});
	}

	/* ---------------------------------------------------------------------
	   Load more posts (AJAX)
	--------------------------------------------------------------------- */
	function initLoadMore() {
		var btn = qs('[data-load-more]');
		var grid = qs('[data-load-more-grid]');
		if (!btn || !grid || !D.ajaxUrl) return;
		var label = btn.textContent;
		btn.addEventListener('click', function () {
			var page = parseInt(btn.getAttribute('data-page') || '1', 10) + 1;
			btn.disabled = true;
			btn.textContent = I18N.loading || 'Loading…';
			post('hbg_load_more', {
				page: page,
				per_page: btn.getAttribute('data-per-page') || 6,
				exclude: btn.getAttribute('data-exclude') || '',
				cat: btn.getAttribute('data-cat') || 0
			}).then(function (res) {
				if (!res || !res.success) throw new Error('bad response');
				var tmp = document.createElement('div');
				tmp.innerHTML = res.data.html;
				qsa('.card', tmp).forEach(function (card) { card.classList.add('is-new'); grid.appendChild(card); });
				btn.setAttribute('data-page', page);
				btn.disabled = false;
				btn.textContent = label;
				if (!res.data.has_more) { btn.textContent = I18N.noMore || 'You’ve reached the end'; btn.disabled = true; }
			}).catch(function () {
				btn.disabled = false;
				btn.textContent = I18N.error || 'Something went wrong. Please try again.';
			});
		});
	}

	/* ---------------------------------------------------------------------
	   Newsletter (AJAX forms only; external actions submit normally)
	--------------------------------------------------------------------- */
	function initNewsletter() {
		qsa('form[data-ajax-subscribe]').forEach(function (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				var input = qs('input[type="email"]', form);
				var msg = qs('.newsletter__msg', form);
				var btn = qs('button[type="submit"]', form);
				if (!input) return;
				if (!input.value || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
					if (msg) { msg.textContent = 'Please enter a valid email address.'; msg.className = 'newsletter__msg is-error'; }
					input.focus();
					return;
				}
				if (btn) btn.disabled = true;
				post('hbg_subscribe', { email: input.value, source: (qs('input[name="source"]', form) || {}).value || 'site', hbg_hp: (qs('input[name="hbg_hp"]', form) || {}).value || '' })
					.then(function (res) {
						if (msg) { msg.textContent = res.data && res.data.message ? res.data.message : ''; msg.className = 'newsletter__msg ' + (res.success ? 'is-success' : 'is-error'); }
						if (res.success) input.value = '';
					})
					.catch(function () { if (msg) { msg.textContent = I18N.error || 'Something went wrong.'; msg.className = 'newsletter__msg is-error'; } })
					.then(function () { if (btn) btn.disabled = false; });
			});
		});
	}

	/* ---------------------------------------------------------------------
	   Share: copy link + native share
	--------------------------------------------------------------------- */
	function initShare() {
		qsa('.share').forEach(function (bar) {
			var url = bar.getAttribute('data-url') || location.href;
			var title = bar.getAttribute('data-title') || document.title;
			var copy = qs('[data-copy]', bar);
			if (!copy) return;
			copy.addEventListener('click', function () {
				if (navigator.share && /Android|iPhone|iPad/i.test(navigator.userAgent)) {
					navigator.share({ title: title, url: url }).catch(function () {});
					return;
				}
				var done = function () {
					copy.classList.add('is-copied');
					copy.setAttribute('title', I18N.copied || 'Link copied');
					setTimeout(function () { copy.classList.remove('is-copied'); }, 1800);
				};
				if (navigator.clipboard) navigator.clipboard.writeText(url).then(done);
				else {
					var ta = document.createElement('textarea');
					ta.value = url; document.body.appendChild(ta); ta.select();
					try { document.execCommand('copy'); done(); } catch (e) {}
					document.body.removeChild(ta);
				}
			});
		});
	}

	/* ---------------------------------------------------------------------
	   Table of contents: collapse + active section highlight
	--------------------------------------------------------------------- */
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
		var targets = links.map(function (a) { return qs(a.getAttribute('href')); }).filter(Boolean);
		if (!('IntersectionObserver' in window) || !targets.length) return;
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) {
					links.forEach(function (a) { a.classList.toggle('is-active', a.getAttribute('href') === '#' + en.target.id); });
				}
			});
		}, { rootMargin: '-15% 0px -70% 0px', threshold: 0 });
		targets.forEach(function (t) { io.observe(t); });
	}

	/* ---------------------------------------------------------------------
	   View counter beacon (once per post per session)
	--------------------------------------------------------------------- */
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
		window.addEventListener('scroll', function onScroll() {
			if ((window.scrollY || 0) > window.innerHeight * 0.5) { send(); window.removeEventListener('scroll', onScroll); }
		}, { passive: true });
	}

	/* ---------------------------------------------------------------------
	   Reveal-on-scroll for cards and product grids
	--------------------------------------------------------------------- */
	function initReveal() {
		if (!('IntersectionObserver' in window)) return;
		var els = qsa('.card, .pick-card, .cat-card, .guide-card, .cat-tile, .mr');
		els.forEach(function (el, i) { el.classList.add('reveal'); el.style.transitionDelay = ((i % 4) * 60) + 'ms'; });
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
		}, { rootMargin: '0px 0px -8% 0px' });
		els.forEach(function (el) { io.observe(el); });
		// Safety net: anything still hidden after 2.5s becomes visible.
		setTimeout(function () { els.forEach(function (el) { el.classList.add('is-visible'); }); }, 2500);
	}

	/* ---------------------------------------------------------------------
	   Smooth anchor scrolling with header offset (opens tabs on the way)
	--------------------------------------------------------------------- */
	function scrollToTarget(target) {
		revealTarget(target);
		var header = qs('.site-header');
		var offset = (header ? header.offsetHeight : 0) + 16;
		var y = target.getBoundingClientRect().top + (window.scrollY || 0) - offset;
		window.scrollTo({ top: y, behavior: 'smooth' });
	}
	function initAnchors() {
		document.addEventListener('click', function (e) {
			var link = e.target.closest('a[href*="#"]');
			if (!link) return;
			var href = link.getAttribute('href') || '';
			var hashIndex = href.indexOf('#');
			if (hashIndex === -1 || hashIndex === href.length - 1) return;
			// Only handle links to the current page.
			var path = href.slice(0, hashIndex);
			if (path && path !== location.pathname && path !== location.href.split('#')[0] && path !== location.origin + location.pathname) return;
			var target = document.getElementById(href.slice(hashIndex + 1));
			if (!target) return;
			e.preventDefault();
			scrollToTarget(target);
			if (history.pushState) history.pushState(null, '', '#' + target.id);
		});
		// Arriving with a hash that points into a tab panel (e.g. /#scooters from another page).
		if (location.hash.length > 1) {
			var initial = document.getElementById(location.hash.slice(1));
			if (initial) setTimeout(function () { scrollToTarget(initial); }, 60);
		}
		window.addEventListener('hashchange', function () {
			var t = location.hash.length > 1 ? document.getElementById(location.hash.slice(1)) : null;
			if (t) revealTarget(t);
		});
	}

	function init() {
		initScrollUI();
		initTicker();
		initDrawer();
		initDesktopNav();
		initSearch();
		initTheme();
		initTabs();
		initImageStates();
		initFAQ();
		initQuiz();
		initCompareTable();
		initCompareDrawer();
		initLoadMore();
		initNewsletter();
		initShare();
		initTOC();
		initViews();
		initReveal();
		initAnchors();
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
}());
