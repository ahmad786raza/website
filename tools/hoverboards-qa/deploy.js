// Live deployment helper. Usage: WP_USER=… WP_PASS=… node deploy.js <phase> ; phases: inventory | install | setup
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs');
const path = require('path');
const SITE = process.env.SITE || 'https://hoverboardsguide.com';
const OUT = path.join(__dirname, '..', 'deploy');
const phase = process.argv[2];
const ZIP = process.argv[3];

(async () => {
  const b = await chromium.launch({ proxy: { server: process.env.HTTPS_PROXY } });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 }, acceptDownloads: true });
  const p = await ctx.newPage();
  p.setDefaultTimeout(120000);
  const log = (...a) => console.log(...a);

  // ---- login
  await p.goto(SITE + '/wp-login.php', { waitUntil: 'domcontentloaded' });
  await p.fill('#user_login', process.env.WP_USER);
  await p.fill('#user_pass', process.env.WP_PASS);
  await Promise.all([p.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => {}), p.click('#wp-submit')]);
  await p.waitForTimeout(1500);
  const loggedIn = /wp-admin/.test(p.url()) && !(await p.$('#login_error'));
  log('login:', loggedIn ? 'ok' : 'FAILED (' + p.url() + ') ' + (await p.evaluate(() => (document.querySelector('#login_error') || {}).innerText || '')).slice(0, 200));
  if (!loggedIn) { await p.screenshot({ path: OUT + '/login-failed.png' }); await b.close(); process.exit(1); }

  if (phase === 'inventory') {
    const inv = {};
    await p.goto(SITE + '/wp-admin/themes.php', { waitUntil: 'domcontentloaded' });
    inv.themes = await p.evaluate(() => (window._wpThemeSettings && _wpThemeSettings.themes || []).map(t => ({ id: t.id, name: t.name, version: t.version, active: t.active, parent: t.parent })));
    await p.goto(SITE + '/wp-admin/plugins.php', { waitUntil: 'domcontentloaded' });
    inv.plugins = await p.evaluate(() => [...document.querySelectorAll('#the-list tr[data-plugin]')].map(r => ({ plugin: r.dataset.plugin, name: (r.querySelector('.plugin-title strong') || {}).innerText, version: ((r.querySelector('.plugin-version-author-uri') || {}).innerText || '').split('|')[0].trim(), active: r.classList.contains('active') })));
    await p.goto(SITE + '/wp-admin/options-reading.php', { waitUntil: 'domcontentloaded' });
    inv.reading = await p.evaluate(() => ({ show_on_front: (document.querySelector('input[name="show_on_front"]:checked') || {}).value, page_on_front: (document.querySelector('#page_on_front') || {}).value, page_for_posts: (document.querySelector('#page_for_posts') || {}).value, posts_per_page: (document.querySelector('#posts_per_page') || {}).value, blog_public: (document.querySelector('#blog_public') || {}).checked }));
    await p.goto(SITE + '/wp-admin/options-permalink.php', { waitUntil: 'domcontentloaded' });
    inv.permalinks = await p.evaluate(() => ({ selection: (document.querySelector('input[name="selection"]:checked') || {}).value, custom: (document.querySelector('#permalink_structure') || {}).value, category_base: (document.querySelector('#category_base') || {}).value }));
    await p.goto(SITE + '/wp-admin/options-general.php', { waitUntil: 'domcontentloaded' });
    inv.general = await p.evaluate(() => ({ blogname: document.querySelector('#blogname').value, blogdescription: document.querySelector('#blogdescription').value, siteurl: document.querySelector('#siteurl').value, home: document.querySelector('#home').value, timezone: (document.querySelector('#timezone_string') || {}).value }));
    await p.goto(SITE + '/wp-admin/nav-menus.php', { waitUntil: 'domcontentloaded' });
    inv.menus = await p.evaluate(() => ({ menus: [...document.querySelectorAll('#menu option, select[name="menu"] option')].map(o => o.textContent.trim()), locations_note: (document.querySelector('.manage-menus') || {}).innerText || '' }));
    await p.goto(SITE + '/wp-admin/edit.php?post_status=all&post_type=post', { waitUntil: 'domcontentloaded' });
    inv.post_counts = await p.evaluate(() => (document.querySelector('.subsubsub') || {}).innerText || '');
    await p.goto(SITE + '/wp-admin/edit.php?post_type=page', { waitUntil: 'domcontentloaded' });
    inv.page_counts = await p.evaluate(() => (document.querySelector('.subsubsub') || {}).innerText || '');
    await p.goto(SITE + '/wp-admin/users.php', { waitUntil: 'domcontentloaded' });
    inv.users = await p.evaluate(() => [...document.querySelectorAll('#the-list tr')].map(r => ({ login: (r.querySelector('.username strong a') || r.querySelector('.username')).innerText.trim(), name: (r.querySelector('.name') || {}).innerText, role: (r.querySelector('.role') || {}).innerText })));
    await p.goto(SITE + '/wp-admin/site-health.php?tab=debug', { waitUntil: 'domcontentloaded' }).catch(() => {});
    inv.site_health = await p.evaluate(() => { const t = document.querySelector('#health-check-debug'); return t ? t.innerText.replace(/\s+\n/g, '\n').slice(0, 6000) : ''; });
    fs.writeFileSync(OUT + '/inventory-before.json', JSON.stringify(inv, null, 1));
    log('inventory saved. active theme:', JSON.stringify(inv.themes.filter(t => t.active)), '| plugins:', inv.plugins.map(x => x.plugin + (x.active ? '(on)' : '(off)')).join(', '));
    log('reading:', JSON.stringify(inv.reading), '| permalinks:', JSON.stringify(inv.permalinks));
    // ---- content export (safety backup)
    await p.goto(SITE + '/wp-admin/export.php', { waitUntil: 'domcontentloaded' });
    const [dl] = await Promise.all([p.waitForEvent('download', { timeout: 300000 }), p.click('#submit')]);
    const file = OUT + '/content-export-before.xml';
    await dl.saveAs(file);
    log('export saved:', fs.statSync(file).size, 'bytes');
  }

  if (phase === 'install') {
    await p.goto(SITE + '/wp-admin/theme-install.php', { waitUntil: 'domcontentloaded' });
    const toggle = await p.$('.upload-view-toggle');
    if (toggle) { await toggle.click(); await p.waitForTimeout(600); }
    await p.setInputFiles('#themezip', ZIP);
    const visible = await p.$eval('#install-theme-submit', el => !!el.offsetParent);
    log('upload form visible:', visible);
    await Promise.all([p.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 300000 }), visible ? p.click('#install-theme-submit') : p.$eval('#install-theme-submit', el => el.form.submit())]);
    await p.waitForTimeout(1000);
    let txt = await p.evaluate(() => document.body.innerText);
    log('after upload page title:', await p.title());
    const overwrite = await p.$('a.update-from-upload-overwrite, .update-from-upload-overwrite');
    if (overwrite) {
      const cmp = await p.evaluate(() => [...document.querySelectorAll('.update-from-upload-comparison tr')].map(r => r.innerText.replace(/\s+/g, ' ')).join(' | '));
      log('comparison:', cmp.slice(0, 400));
      await Promise.all([p.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 300000 }), overwrite.click()]);
      await p.waitForTimeout(1000);
      txt = await p.evaluate(() => document.body.innerText);
    }
    log('result:', txt.replace(/\s+/g, ' ').slice(0, 500));
    await p.screenshot({ path: OUT + '/install-result.png', fullPage: true });
    await p.goto(SITE + '/wp-admin/themes.php', { waitUntil: 'domcontentloaded' });
    const themes = await p.evaluate(() => (window._wpThemeSettings && _wpThemeSettings.themes || []).map(t => ({ id: t.id, name: t.name, version: t.version, active: t.active })));
    log('themes now:', JSON.stringify(themes));
    const active = themes.find(t => t.active);
    if (!active || active.id !== 'hoverboards-guide') {
      // activate if needed
      const link = await p.$('div[data-slug="hoverboards-guide"] a.activate');
      if (link) { await Promise.all([p.waitForNavigation().catch(() => {}), link.click()]); log('activated via themes.php'); }
    }
    await p.goto(SITE + '/wp-admin/admin.php?page=hbg-setup', { waitUntil: 'domcontentloaded' });
    log('setup page:', (await p.evaluate(() => document.body.innerText)).replace(/\s+/g, ' ').slice(0, 1500));
    await p.screenshot({ path: OUT + '/setup-page.png', fullPage: true });
  }

  if (phase === 'status') {
    await p.goto(SITE + '/wp-admin/admin.php?page=hbg-setup', { waitUntil: 'domcontentloaded' });
    const txt = await p.evaluate(() => document.body.innerText);
    const i = txt.indexOf('1. Status');
    log('setup page:', txt.slice(i, i + 3500).replace(/\s+/g, ' '));
    await p.screenshot({ path: OUT + '/setup-status.png', fullPage: true });
  }
  if (phase === 'setup') {
    const actions = (process.argv[4] || 'hubs,menu,pages').split(',');
    for (const a of actions) {
      await p.goto(SITE + '/wp-admin/admin.php?page=hbg-setup', { waitUntil: 'domcontentloaded' });
      const btn = await p.$('button[name="do"][value="' + a + '"]');
      if (!btn) { log('no button for', a); continue; }
      await Promise.all([p.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 300000 }), btn.click()]);
      const notice = await p.evaluate(() => (document.querySelector('.notice') || {}).innerText || '');
      log(a + ':', notice.trim().split('\n')[0]);
    }
    await p.goto(SITE + '/wp-admin/admin.php?page=hbg-setup', { waitUntil: 'domcontentloaded' });
    log('status after:', (await p.evaluate(() => document.querySelectorAll('table')[1].innerText)).replace(/\s+/g, ' ').slice(0, 900));
    await p.screenshot({ path: OUT + '/setup-after.png', fullPage: true });
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message.slice(0, 500)); process.exit(1); });
