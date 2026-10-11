// Logged-in preview: saves HTML and viewport clips. Usage: WP_USER=… WP_PASS=… node preview.js <url> <outprefix>
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs');
const SITE = process.env.SITE || 'https://hoverboardsguide.com';
const [url, out] = process.argv.slice(2);
(async () => {
  const b = await chromium.launch({ proxy: { server: process.env.HTTPS_PROXY } });
  for (const [name, vp, mobile, steps] of [['desktop', { width: 1366, height: 900 }, false, 12], ['mobile', { width: 390, height: 844 }, true, 6]]) {
    const ctx = await b.newContext({ viewport: vp, isMobile: mobile, deviceScaleFactor: 1 });
    const p = await ctx.newPage(); p.setDefaultTimeout(120000);
    await p.goto(SITE + '/wp-login.php', { waitUntil: 'domcontentloaded' });
    await p.fill('#user_login', process.env.WP_USER); await p.fill('#user_pass', process.env.WP_PASS);
    await Promise.all([p.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => {}), p.click('#wp-submit')]);
    try { await p.goto(url, { waitUntil: 'networkidle', timeout: 90000 }); } catch (e) { console.log('goto warning', e.message.slice(0, 80)); }
    await p.waitForTimeout(1200);
    if (name === 'desktop') fs.writeFileSync(out + '.html', await p.content());
    const total = await p.evaluate(() => document.documentElement.scrollHeight);
    const broken = await p.evaluate(() => [...document.querySelectorAll('.post-content img')].filter(i => !i.complete || i.naturalWidth === 0).map(i => i.src));
    const overflow = await p.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth);
    for (let i = 0; i < steps; i++) { const y = i * vp.height; if (y > total) break; await p.evaluate(y => window.scrollTo(0, y), y); await p.waitForTimeout(400); await p.screenshot({ path: `${out}-${name}-${i}.jpg`, type: 'jpeg', quality: 65 }); }
    console.log(name, 'height', total, 'broken imgs', JSON.stringify(broken), 'overflow', overflow);
    await ctx.close();
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
