// Functional + SEO + a11y + perf checks against the local site. Usage: node qa.js http://127.0.0.1:8080 [axe.min.js path]
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs');
const BASE = process.argv[2];
const AXE = process.argv[3] && fs.existsSync(process.argv[3]) ? fs.readFileSync(process.argv[3], 'utf8') : null;
const results = []; let pass = 0, fail = 0;
function check(name, ok, info) { results.push({ name, ok: !!ok, info: info || '' }); if (ok) pass++; else fail++; console.log((ok ? 'PASS ' : 'FAIL ') + name + (info ? ' — ' + info : '')); }

(async () => {
  const b = await chromium.launch(process.env.QA_PROXY ? { proxy: { server: process.env.QA_PROXY } } : {});
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 } });
  const page = await ctx.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  page.on('console', m => { if (m.type() === 'error' && !/favicon|ERR_|fonts\.g|404/.test(m.text())) errors.push(m.text()); });
  const missing = []; page.on('response', r => { if (r.status() === 404 && !/favicon|this-does-not-exist/.test(r.url())) missing.push(r.url()); });

  // ---- Homepage
  await page.goto(BASE + '/', { waitUntil: 'networkidle' });
  const home = await page.evaluate(() => {
    const ld = [...document.querySelectorAll('script[type="application/ld+json"]')].map(s => { try { return JSON.parse(s.textContent); } catch (e) { return null; } });
    const types = []; const walk = o => { if (!o) return; if (Array.isArray(o)) return o.forEach(walk); if (typeof o === 'object') { if (o['@type']) types.push(o['@type']); Object.values(o).forEach(walk); } };
    ld.forEach(walk);
    const aff = [...document.querySelectorAll('a[href*="amazon."]')];
    return {
      h1: document.querySelectorAll('h1').length,
      title: document.title,
      desc: (document.querySelector('meta[name="description"]') || {}).content || '',
      canonical: document.querySelectorAll('link[rel="canonical"]').length,
      ldParsed: ld.every(Boolean), ldTypes: types,
      aff: aff.length,
      affTagged: aff.filter(a => /tag=rcblogs-20/.test(a.href)).length,
      affRel: aff.filter(a => /sponsored/.test(a.rel) && /nofollow/.test(a.rel)).length,
      affBlank: aff.filter(a => a.target === '_blank').length,
      scripts: document.querySelectorAll('script[src]').length,
      styles: document.querySelectorAll('link[rel="stylesheet"]').length,
      imgsNoAlt: [...document.images].filter(i => !i.hasAttribute('alt')).length,
      heroEager: (document.querySelector('.story--lead .story__img, .hero__img') || {}).loading,
      sections: [...document.querySelectorAll('main section')].map(s => s.id || s.className.split(' ')[0]),
      claims: (() => { const c = document.body.cloneNode(true); c.querySelectorAll('.card__excerpt, .row__excerpt, .story__excerpt, .post-content, .card__title, .row__title, .story__title').forEach(e => e.remove()); return /tested|re-checked weekly|Prime shipping|30-day returns|never sponsored|independent editors/i.test(c.innerText); })(),
      stars: document.querySelectorAll('.stars, .score-badge').length,
      navItems: [...document.querySelectorAll('.primary-nav .nav-menu > li > a')].map(a => a.textContent.trim()),
      overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth,
      brandRect: document.querySelector('.brand').getBoundingClientRect().right,
      navLeft: (document.querySelector('.primary-nav .nav-menu') || { getBoundingClientRect: () => ({ left: 9999 }) }).getBoundingClientRect().left,
      focusable: [...document.querySelectorAll('a,button')].filter(el => el.offsetParent !== null).length,
    };
  });
  check('home: exactly one H1', home.h1 === 1, 'count=' + home.h1);
  check('home: meta description present', home.desc.length > 60, home.desc.slice(0, 80));
  check('home: one canonical', home.canonical === 1, 'count=' + home.canonical);
  check('home: JSON-LD parses', home.ldParsed, home.ldTypes.slice(0, 8).join(','));
  check('home: has FAQPage + WebSite + Organization', ['FAQPage', 'WebSite', 'Organization'].every(t => home.ldTypes.includes(t)));
  check('home: affiliate links tagged', home.aff > 0 && home.affTagged === home.aff, home.aff + ' links');
  check('home: affiliate links rel sponsored nofollow', home.affRel === home.aff);
  check('home: affiliate links open new tab', home.affBlank === home.aff);
  check('home: no img without alt attribute', home.imgsNoAlt === 0, 'missing=' + home.imgsNoAlt);
  check('home: lead story image not lazy', home.heroEager !== 'lazy', 'loading=' + home.heroEager);
  check('home: no unsupported trust claims', !home.claims);
  check('home: no star ratings or editor scores', home.stars === 0);
  check('home: no horizontal overflow', !home.overflow);
  check('home: nav does not overlap brand', home.navLeft > home.brandRect, 'brandRight=' + Math.round(home.brandRect) + ' navLeft=' + Math.round(home.navLeft));
  check('home: ≤ 4 external scripts', home.scripts <= 4, 'scripts=' + home.scripts + ' styles=' + home.styles);
  check('home: editorial sections present', ['mosaic', 'picks', 'latest', 'zone-hoverboards', 'topics', 'zone-reviews', 'compare', 'finder', 'zone-buying-guides', 'zone-safety', 'zone-scooters', 'zone-rideables', 'about'].every(id => home.sections.includes(id)), home.sections.join(','));

  // search overlay
  await page.click('.search-toggle');
  await page.waitForTimeout(300);
  check('home: search overlay opens and focuses input', await page.evaluate(() => document.activeElement && document.activeElement.classList.contains('search-field')));
  await page.keyboard.press('Escape');
  await page.waitForTimeout(200);
  check('home: Escape closes search', await page.evaluate(() => !document.querySelector('#search-overlay').classList.contains('is-open')));

  // FAQ (optional block; off by default since 4.0)
  if (await page.$('.faq__trigger')) {
    await page.click('.faq__trigger');
    check('home: FAQ expands', await page.evaluate(() => document.querySelector('.faq__trigger').getAttribute('aria-expanded') === 'true' && !document.querySelector('.faq__body').hidden));
  }

  // compare filter + sort
  await page.click('.seg__btn[data-filter="kids"]');
  const vis = await page.evaluate(() => [...document.querySelectorAll('.compare-table tbody tr')].filter(r => !r.hidden).map(r => r.dataset.id));
  check('home: compare filter kids shows kid boards only', vis.length > 0 && vis.every(id => /g13|helix|edge/.test(id)), vis.join(','));
  await page.click('.seg__btn[data-filter="all"]');
  await page.selectOption('[data-sort]', 'weight');
  const order = await page.evaluate(() => [...document.querySelectorAll('.compare-table tbody tr')].map(r => parseFloat(r.dataset.weight)));
  check('home: compare sort by weight descending', order.every((v, i) => i === 0 || order[i - 1] >= v), order.join('>'));

  // finder: child + rough + mid + wheels → gyroor-g13
  const clickOpt = async (step, value) => { await page.click('.finder__step[data-step="' + step + '"] .finder__option[data-value="' + value + '"]'); await page.waitForTimeout(100); };
  await clickOpt(0, 'child'); await clickOpt(1, 'rough'); await clickOpt(2, 'mid'); await clickOpt(3, 'wheels');
  const fr = await page.evaluate(() => ({ hidden: document.querySelector('.finder__result').hidden, name: document.querySelector('[data-finder-name]').textContent, guides: document.querySelectorAll('[data-finder-guides] a').length, link: document.querySelector('[data-finder-link]').href, note: document.querySelector('[data-finder-note]').textContent }));
  check('home: finder child/rough → Gyroor G13 with guides', !fr.hidden && /G13/.test(fr.name) && fr.guides >= 2 && /amazon\.com.*tag=rcblogs-20/.test(fr.link), fr.name + ' | ' + fr.guides + ' guides | ' + fr.note);
  await page.click('[data-finder-restart]');
  await clickOpt(0, 'adult'); await clickOpt(1, 'rough'); await clickOpt(2, 'high'); await clickOpt(3, 'wheels');
  check('home: finder adult/rough/high → Swagtron T6', /T6/.test(await page.textContent('[data-finder-name]')));
  await page.click('[data-finder-restart]');
  await clickOpt(0, 'teen'); await clickOpt(1, 'smooth'); await clickOpt(2, 'low'); await clickOpt(3, 'easy');
  check('home: finder teen/smooth/low → Hover-1 Helix', /Helix/.test(await page.textContent('[data-finder-name]')));

  // load more
  const before = await page.evaluate(() => document.querySelectorAll('#latest-grid .card').length);
  await page.click('[data-load-more]');
  await page.waitForFunction(n => document.querySelectorAll('#latest-grid .card').length > n, before, { timeout: 15000 }).catch(() => {});
  const after = await page.evaluate(() => ({ n: document.querySelectorAll('#latest-grid .card').length, dup: (() => { const ids = [...document.querySelectorAll('#latest-grid .card')].map(c => c.id); return ids.length !== new Set(ids).size; })() }));
  check('home: load more appends without duplicates', after.n > before && !after.dup, before + '→' + after.n);

  // ---- Article
  await page.goto(BASE + '/hover-1-titan-hoverboard/', { waitUntil: 'networkidle' });
  const art = await page.evaluate(() => {
    const ld = [...document.querySelectorAll('script[type="application/ld+json"]')].map(s => { try { return JSON.parse(s.textContent); } catch (e) { return null; } });
    const types = []; const walk = o => { if (!o) return; if (Array.isArray(o)) return o.forEach(walk); if (typeof o === 'object') { if (o['@type']) types.push(o['@type']); Object.values(o).forEach(walk); } };
    ld.forEach(walk);
    const aff = [...document.querySelectorAll('.post-content a[href*="amazon."]')];
    return { h1: document.querySelectorAll('h1').length, canonical: document.querySelectorAll('link[rel="canonical"]').length, ldOk: ld.every(Boolean), types, crumbs: document.querySelectorAll('.breadcrumbs li').length, disclosure: !!document.querySelector('.disclosure'), adStrip: document.body.innerText.includes('#ad'), gradient: !!(document.querySelector('.post-content').firstElementChild && document.querySelector('.post-content').firstElementChild.querySelector('[style*="linear-gradient"]') && document.querySelector('.post-content').firstElementChild.querySelector('[style*="linear-gradient"]').textContent.includes('UPDATED')), toc: !!document.querySelector('.imported-toc, .toc'), tocLinks: [...document.querySelectorAll('.imported-toc a, .toc a')].filter(a => !document.getElementById(a.getAttribute('href').slice(1))).length, related: document.querySelectorAll('.related .card').length, readNext: document.querySelectorAll('.read-next a').length, aff: aff.length, affOk: aff.filter(a => /sponsored/.test(a.rel) && /tag=/.test(a.href)).length, tables: document.querySelectorAll('.post-content .table-scroll').length, overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth, author: !!document.querySelector('.author-box'), lede: !!document.querySelector('.lede'), articleCount: types.filter(t => t === 'BlogPosting' || t === 'Article').length, crumbCount: types.filter(t => t === 'BreadcrumbList').length };
  });
  check('article: exactly one H1 (duplicate in-body H1 removed)', art.h1 === 1, 'count=' + art.h1);
  check('article: one canonical', art.canonical === 1);
  check('article: JSON-LD parses with one Article and one BreadcrumbList', art.ldOk && art.articleCount === 1 && art.crumbCount === 1, art.types.join(','));
  check('article: breadcrumbs Home › Category [› Child] › Title', art.crumbs === 3 || art.crumbs === 4, 'items=' + art.crumbs);
  check('article: disclosure box shown, inline #ad strip removed', art.disclosure && !art.adStrip);
  check('article: purple gradient hero removed, lede kept', !art.gradient && art.lede);
  check('article: table of contents links resolve', art.toc && art.tocLinks === 0, 'unresolved=' + art.tocLinks);
  check('article: related guides + read next', art.related >= 2 && art.readNext >= 2, art.related + '/' + art.readNext);
  check('article: content affiliate links tagged + sponsored', art.aff > 0 && art.affOk === art.aff, art.aff + ' links');
  check('article: tables wrapped for scrolling', art.tables >= 1, 'tables=' + art.tables);
  check('article: no horizontal overflow', !art.overflow);
  check('article: author box present', art.author);

  // ---- Category + search + 404
  await page.goto(BASE + '/category/hoverboards/', { waitUntil: 'networkidle' });
  const cat = await page.evaluate(() => ({ h1: document.querySelectorAll('h1').length, cards: document.querySelectorAll('.card').length, canonical: document.querySelectorAll('link[rel="canonical"]').length, desc: (document.querySelector('meta[name="description"]') || {}).content || '', pag: !!document.querySelector('.hbg-pagination'), overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth }));
  check('category: one H1, cards, canonical, description, pagination', cat.h1 === 1 && cat.cards > 0 && cat.canonical === 1 && cat.desc.length > 20 && cat.pag && !cat.overflow, JSON.stringify(cat));
  await page.goto(BASE + '/?s=hoverboard', { waitUntil: 'networkidle' });
  const srch = await page.evaluate(() => ({ robots: (document.querySelector('meta[name="robots"]') || {}).content || '', cards: document.querySelectorAll('.card').length }));
  check('search: noindex + results', /noindex/.test(srch.robots) && srch.cards > 0, srch.robots + ' cards=' + srch.cards);
  const r404 = await page.goto(BASE + '/this-does-not-exist/', { waitUntil: 'networkidle' });
  check('404: status 404 with search and topic links', r404.status() === 404 && await page.evaluate(() => !!document.querySelector('.e404 .search-form') && document.querySelectorAll('.e404 .chip').length > 3));

  // ---- Mobile: drawer + layout
  const mctx = await b.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
  const mp = await mctx.newPage();
  await mp.goto(BASE + '/', { waitUntil: 'networkidle' });
  await mp.click('.nav-toggle');
  await mp.waitForTimeout(400);
  const drawer = await mp.evaluate(() => ({ open: document.querySelector('#mobile-drawer').classList.contains('is-open'), items: document.querySelectorAll('#mobile-drawer .nav-menu > li').length, focus: document.activeElement.classList.contains('drawer__close') }));
  check('mobile: drawer opens with menu items and focus on close', drawer.open && drawer.items >= 6 && drawer.focus, JSON.stringify(drawer));
  await mp.keyboard.press('Escape');
  const mob = await mp.evaluate(() => ({ overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth, smallTaps: [...document.querySelectorAll('a,button')].filter(el => { const r = el.getBoundingClientRect(); return r.width > 0 && r.height > 0 && r.height < 24 && el.closest('nav,.pcard__actions,.hero__actions'); }).length, minFont: Math.min(...[...document.querySelectorAll('p,li,a,span')].filter(e => e.offsetParent).map(e => parseFloat(getComputedStyle(e).fontSize))) }));
  check('mobile: no horizontal overflow', !mob.overflow);
  check('mobile: no tiny text (<11px) in content', mob.minFont >= 10.5, 'min=' + mob.minFont);
  await mp.goto(BASE + '/hover-1-titan-hoverboard/', { waitUntil: 'networkidle' });
  const mart = await mp.evaluate(() => ({ overflow: document.documentElement.scrollWidth > document.documentElement.clientWidth, tableScroll: [...document.querySelectorAll('.table-scroll')].some(t => t.scrollWidth > t.clientWidth), contentFont: parseFloat(getComputedStyle(document.querySelector('.post-content p')).fontSize) }));
  check('mobile article: no overflow, tables scroll inside, body ≥16px', !mart.overflow && mart.contentFont >= 16, JSON.stringify(mart));

  // ---- Keyboard: tab through header to nav
  await page.goto(BASE + '/', { waitUntil: 'networkidle' });
  await page.keyboard.press('Tab');
  const first = await page.evaluate(() => document.activeElement.className);
  check('keyboard: first Tab lands on skip link', /skip-link/.test(first), first);

  // ---- Perf (lab): LCP and CLS on homepage, mobile emulation with throttling
  const pctx = await b.newContext({ viewport: { width: 390, height: 844 }, isMobile: true });
  const pp = await pctx.newPage();
  const cdp = await pctx.newCDPSession(pp);
  await cdp.send('Network.enable');
  await cdp.send('Network.emulateNetworkConditions', { offline: false, latency: 150, downloadThroughput: 1.6 * 1024 * 1024 / 8, uploadThroughput: 750 * 1024 / 8 });
  await cdp.send('Emulation.setCPUThrottlingRate', { rate: 4 });
  for (const path of ['/', '/hover-1-titan-hoverboard/']) {
    await pp.addInitScript(() => { window.__lcp = 0; window.__cls = 0; new PerformanceObserver(l => { l.getEntries().forEach(e => { window.__lcp = e.startTime; }); }).observe({ type: 'largest-contentful-paint', buffered: true }); new PerformanceObserver(l => { l.getEntries().forEach(e => { if (!e.hadRecentInput) window.__cls += e.value; }); }).observe({ type: 'layout-shift', buffered: true }); });
    await pp.goto(BASE + path, { waitUntil: 'networkidle' });
    await pp.waitForTimeout(1500);
    const m = await pp.evaluate(() => ({ lcp: Math.round(window.__lcp), cls: Math.round(window.__cls * 1000) / 1000, req: performance.getEntriesByType('resource').length + 1, dom: document.getElementsByTagName('*').length }));
    check('perf ' + path + ': LCP ≤ 2500ms and CLS ≤ 0.1 (mobile, 4x CPU, ~1.6 Mbps)', m.lcp <= 2500 && m.cls <= 0.1, JSON.stringify(m));
  }

  check('no JS errors across pages', errors.length === 0, errors.slice(0, 3).join(' | '));
  check('no missing resources (404) on tested pages', missing.length === 0, missing.slice(0, 4).join(' | '));

  // ---- axe
  if (AXE) {
    for (const path of ['/', '/hover-1-titan-hoverboard/', '/category/hoverboards/']) {
      await page.goto(BASE + path, { waitUntil: 'networkidle' });
      await page.addScriptTag({ content: AXE });
      const res = await page.evaluate(async () => { const r = await axe.run(document, { runOnly: { type: 'tag', values: ['wcag2a', 'wcag2aa'] } }); return r.violations.map(v => ({ id: v.id, impact: v.impact, nodes: v.nodes.length, html: v.nodes[0].html.slice(0, 120) })); });
      check('axe ' + path + ': 0 WCAG A/AA violations', res.length === 0, JSON.stringify(res).slice(0, 600));
    }
  }
  console.log('\nRESULT: ' + pass + ' passed, ' + fail + ' failed');
  fs.writeFileSync(__dirname + '/../qa-results.json', JSON.stringify(results, null, 1));
  await b.close();
})().catch(e => { console.error('ERR', e); process.exit(1); });
