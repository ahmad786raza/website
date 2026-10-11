// Usage: node sections.js <url> <outprefix> [--proxy]  → element screenshots per homepage section, desktop + tablet + mobile
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
(async () => {
  const [url, out, proxyFlag] = process.argv.slice(2);
  const opts = {};
  if (proxyFlag === '--proxy') opts.proxy = { server: process.env.HTTPS_PROXY };
  const b = await chromium.launch(opts);
  const sels = ['header.site-header', '.hero', '#picks', '#topics', '#guide', '#reviews', '#compare', '#learn', '#finder', '#faq', '#about', 'footer.site-footer'];
  for (const [name, vp, mobile] of [['desktop', { width: 1366, height: 900 }, false], ['tablet', { width: 820, height: 1180 }, true], ['mobile', { width: 390, height: 844 }, true]]) {
    const ctx = await b.newContext({ viewport: vp, isMobile: mobile, deviceScaleFactor: 1, ignoreHTTPSErrors: true });
    const p = await ctx.newPage();
    try { await p.goto(url, { waitUntil: 'networkidle', timeout: 90000 }); } catch (e) { console.log(name, 'goto warning:', e.message.slice(0, 100)); }
    await p.waitForTimeout(1200);
    await p.screenshot({ path: `${out}-${name}-top.jpg`, type: 'jpeg', quality: 70 });
    for (const s of sels) {
      const el = await p.$(s);
      if (!el) { console.log(name, 'missing', s); continue; }
      try { await el.scrollIntoViewIfNeeded(); await p.waitForTimeout(300); await el.screenshot({ path: `${out}-${name}-${s.replace(/[^a-z]/gi, '')}.jpg`, type: 'jpeg', quality: 65 }); } catch (e) { console.log(name, s, e.message.slice(0, 80)); }
    }
    await ctx.close();
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
