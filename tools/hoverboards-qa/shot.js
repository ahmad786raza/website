// Usage: node shot.js <url> <outprefix> [--proxy]
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
(async () => {
  const [url, out, proxyFlag] = process.argv.slice(2);
  const opts = {};
  if (proxyFlag === '--proxy') opts.proxy = { server: process.env.HTTPS_PROXY };
  const b = await chromium.launch(opts);
  for (const [name, vp, mobile] of [['desktop', {width:1366,height:900}, false], ['mobile', {width:390,height:844}, true]]) {
    const ctx = await b.newContext({ viewport: vp, isMobile: mobile, deviceScaleFactor: 1, ignoreHTTPSErrors: true });
    const p = await ctx.newPage();
    const errors = [];
    p.on('pageerror', e => errors.push('pageerror: ' + e.message));
    p.on('console', m => { if (m.type() === 'error') errors.push('console: ' + m.text()); });
    try {
      await p.goto(url, { waitUntil: 'networkidle', timeout: 90000 });
    } catch (e) { console.log(name, 'goto warning:', e.message.slice(0, 120)); }
    await p.waitForTimeout(1500);
    const metrics = await p.evaluate(() => ({ scrollW: document.documentElement.scrollWidth, clientW: document.documentElement.clientWidth, height: document.documentElement.scrollHeight, h1: [...document.querySelectorAll('h1')].map(h => h.textContent.trim().slice(0, 80)), imgs: document.images.length, title: document.title }));
    await p.screenshot({ path: `${out}-${name}-full.jpg`, fullPage: true, type: 'jpeg', quality: 60 });
    await p.screenshot({ path: `${out}-${name}-top.jpg`, fullPage: false, type: 'jpeg', quality: 70 });
    console.log(name, JSON.stringify(metrics), 'errors:', errors.slice(0, 5));
    await ctx.close();
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
