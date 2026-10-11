// Usage: node elshot.js <url> <selector> <outprefix> <width,width,...> [--proxy]
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
(async () => {
  const [url, sel, out, widths, proxyFlag] = process.argv.slice(2);
  const opts = {}; if (proxyFlag === '--proxy') opts.proxy = { server: process.env.HTTPS_PROXY };
  const b = await chromium.launch(opts);
  for (const w of widths.split(',').map(Number)) {
    const ctx = await b.newContext({ viewport: { width: w, height: 1000 }, isMobile: w < 500, deviceScaleFactor: 1, ignoreHTTPSErrors: true });
    const p = await ctx.newPage();
    try { await p.goto(url, { waitUntil: 'networkidle', timeout: 90000 }); } catch (e) { console.log('goto warning', e.message.slice(0, 80)); }
    await p.waitForTimeout(800);
    const el = await p.$(sel);
    if (!el) { console.log(w, 'no element', sel); await ctx.close(); continue; }
    await el.scrollIntoViewIfNeeded(); await p.waitForTimeout(1500);
    const info = await el.evaluate(e => { const t = e.querySelector('table'); const wrap = t && t.parentElement; return { box: e.getBoundingClientRect().width, table: t ? t.scrollWidth : null, wrap: wrap ? wrap.clientWidth : null, wrapOverflow: wrap ? getComputedStyle(wrap).overflowX : null }; });
    await el.screenshot({ path: `${out}-${w}.jpg`, type: 'jpeg', quality: 70 });
    console.log(w, JSON.stringify(info));
    await ctx.close();
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
