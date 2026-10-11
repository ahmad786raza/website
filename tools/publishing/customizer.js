// Read or set one Customizer setting on the live site. Usage: WP_USER=… WP_PASS=… node customizer.js <setting> [value]
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const SITE = process.env.SITE || 'https://hoverboardsguide.com';
const [setting, value] = process.argv.slice(2);
(async () => {
  const b = await chromium.launch({ proxy: { server: process.env.HTTPS_PROXY } });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 } });
  const p = await ctx.newPage();
  p.setDefaultTimeout(120000);
  await p.goto(SITE + '/wp-login.php', { waitUntil: 'domcontentloaded' });
  await p.fill('#user_login', process.env.WP_USER);
  await p.fill('#user_pass', process.env.WP_PASS);
  await Promise.all([p.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => {}), p.click('#wp-submit')]);
  await p.waitForTimeout(1200);
  if (!/wp-admin/.test(p.url())) { console.log('login FAILED'); await b.close(); process.exit(1); }
  console.log('login: ok');
  await p.goto(SITE + '/wp-admin/customize.php', { waitUntil: 'domcontentloaded' });
  await p.waitForFunction(() => window.wp && wp.customize && wp.customize.state && wp.customize.state('saved') !== undefined, null, { timeout: 120000 });
  await p.waitForFunction(s => !!wp.customize(s), setting, { timeout: 120000 }).catch(() => {});
  const before = await p.evaluate(s => { const o = wp.customize(s); return o ? o.get() : '(no such setting)'; }, setting);
  console.log('current', setting, '=', JSON.stringify(before));
  if (value !== undefined) {
    await p.evaluate(([s, v]) => { wp.customize(s).set(v); }, [setting, value]);
    await p.evaluate(() => wp.customize.previewer.save());
    await p.waitForFunction(() => wp.customize.state('saved').get() === true, null, { timeout: 120000 });
    await p.waitForTimeout(800);
    const after = await p.evaluate(s => wp.customize(s).get(), setting);
    console.log('saved', setting, '=', JSON.stringify(after));
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
