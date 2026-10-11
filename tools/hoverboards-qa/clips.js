// Usage: node clips.js <url> <outprefix> [--proxy]  — viewport clips down the page at desktop and mobile widths
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
(async () => {
  const [url, out, proxyFlag] = process.argv.slice(2);
  const opts = {}; if (proxyFlag === '--proxy') opts.proxy = { server: process.env.HTTPS_PROXY };
  const b = await chromium.launch(opts);
  for (const [name, vp, mobile, steps] of [['desktop', {width:1366,height:900}, false, 8], ['mobile', {width:390,height:844}, true, 5]]) {
    const ctx = await b.newContext({ viewport: vp, isMobile: mobile, deviceScaleFactor: 1, ignoreHTTPSErrors: true });
    const p = await ctx.newPage();
    try { await p.goto(url, { waitUntil: 'networkidle', timeout: 90000 }); } catch (e) { console.log(name, 'goto warning:', e.message.slice(0, 100)); }
    await p.waitForTimeout(1200);
    const total = await p.evaluate(() => document.documentElement.scrollHeight);
    for (let i = 0; i < steps; i++) {
      const y = i * vp.height;
      if (y > total) break;
      await p.evaluate(y => window.scrollTo(0, y), y);
      await p.waitForTimeout(500);
      await p.screenshot({ path: `${out}-${name}-${i}.jpg`, type: 'jpeg', quality: 70 });
    }
    console.log(name, 'height', total);
    await ctx.close();
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
