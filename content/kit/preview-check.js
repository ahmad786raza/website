// Open each draft's preview as the logged-in admin and check what readers would see.
// Usage: BDH_USER=… BDH_PASS=… node preview-check.js <slug> [<slug> …]   (IDs from ../build/upload-log.json)
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs');
const path = require('path');
const SITE = 'https://bendershub.com';
const BUILD = path.join(__dirname, '..', 'build');
const SHOTS = path.join(BUILD, 'shots');

(async () => {
  fs.mkdirSync(SHOTS, { recursive: true });
  const log = JSON.parse(fs.readFileSync(path.join(BUILD, 'upload-log.json')));
  const b = await chromium.launch({ proxy: { server: process.env.HTTPS_PROXY } });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 } });
  const p = await ctx.newPage();
  p.setDefaultTimeout(120000);
  await p.goto(SITE + '/wp-login.php', { waitUntil: 'networkidle' });
  await p.fill('#user_login', process.env.BDH_USER); await p.fill('#user_pass', process.env.BDH_PASS);
  await p.click('#wp-submit');
  await p.waitForURL(/wp-admin/, { timeout: 60000 }).catch(() => {});
  const results = {};
  for (const slug of process.argv.slice(2)) {
    const id = log[slug] && log[slug].id;
    if (!id) { console.log(slug, ': not uploaded'); continue; }
    const errs = [];
    const onErr = e => errs.push(e.message);
    p.on('pageerror', onErr);
    await p.goto(SITE + '/?p=' + id + '&preview=true', { waitUntil: 'networkidle' });
    await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 700) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 40)); } window.scrollTo(0, 0); });
    await p.waitForTimeout(500);
    const r = await p.evaluate(() => {
      const prose = document.querySelector('.bdh-prose');
      const amz = [...document.querySelectorAll('.bdh-prose a[href*="amazon."]')];
      return {
        h1: document.querySelectorAll('h1').length,
        cat: (document.querySelector('.bdh-breadcrumbs, .bdh-breadcrumb') || {}).innerText || '',
        php: /(Fatal error|Warning:|Notice:|Deprecated:)/.test(document.body.innerText),
        inlineToc: !!document.querySelector('.bdh-toc--inline'),
        tocBroken: [...document.querySelectorAll('.bhg-toc a')].filter(a => !document.getElementById(a.getAttribute('href').slice(1))).length,
        amazon: amz.length,
        amazonBad: amz.filter(a => !/^https:\/\/www\.amazon\.com\/dp\/[A-Z0-9]{10}\?tag=polyfreeplanet-20$/.test(a.href) || !/sponsored/.test(a.rel)).length,
        disclosure: /As an Amazon Associate I earn from qualifying purchases\./.test(prose ? prose.innerText : ''),
        imgs: [...document.querySelectorAll('.bdh-prose img, .bdh-article-figure img')].map(i => i.complete && i.naturalWidth > 0),
        leftoverLinks: document.querySelectorAll('[data-bhg-link]').length,
        products: document.querySelectorAll('.bhg-product').length,
        credits: document.querySelectorAll('.bdh-prose figcaption a[rel~="license"], .bdh-prose figcaption').length,
        overflow: document.documentElement.scrollWidth > window.innerWidth,
      };
    });
    p.off('pageerror', onErr);
    r.jsErrors = errs.length;
    r.imgsOk = r.imgs.every(Boolean); r.imgCount = r.imgs.length; delete r.imgs;
    const problems = [];
    if (r.h1 !== 1) problems.push('h1=' + r.h1);
    if (r.php) problems.push('PHP notice on page');
    if (r.inlineToc) problems.push('theme inline TOC shown');
    if (r.tocBroken) problems.push(r.tocBroken + ' broken TOC links');
    if (r.amazonBad) problems.push(r.amazonBad + ' bad Amazon links');
    if (r.amazon && !r.disclosure) problems.push('missing disclosure');
    if (!r.imgsOk) problems.push('image failed to load');
    if (r.leftoverLinks) problems.push('unresolved data-bhg-link');
    if (r.overflow) problems.push('horizontal overflow');
    if (r.jsErrors) problems.push(r.jsErrors + ' JS errors');
    results[slug] = { id, ...r, problems };
    await p.screenshot({ path: path.join(SHOTS, slug + '.png'), fullPage: true });
    console.log(slug, '#' + id, problems.length ? 'PROBLEMS: ' + problems.join(', ') : 'OK', `| ${r.imgCount} imgs, ${r.amazon} amazon links, ${r.products} product boxes | ${r.cat.replace(/\n/g, ' > ').slice(0, 60)}`);
  }
  fs.writeFileSync(path.join(BUILD, 'preview-results.json'), JSON.stringify(Object.assign(fs.existsSync(path.join(BUILD, 'preview-results.json')) ? JSON.parse(fs.readFileSync(path.join(BUILD, 'preview-results.json'))) : {}, results), null, 1));
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
