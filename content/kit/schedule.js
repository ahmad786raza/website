// Schedule drafts to publish one per day (status "future").
// Usage: BDH_USER=… BDH_PASS=… node schedule.js 2026-10-12 13:00
// Order comes from schedule-order.json; IDs from ../build/upload-log.json.
// Only posts that are still drafts (or already scheduled by this script) are touched.
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs');
const path = require('path');
const SITE = 'https://bendershub.com';
(async () => {
  const [startDay, hhmm] = process.argv.slice(2);
  const order = JSON.parse(fs.readFileSync(path.join(__dirname, 'schedule-order.json')));
  const log = JSON.parse(fs.readFileSync(path.join(__dirname, '..', 'build', 'upload-log.json')));
  const b = await chromium.launch({ proxy: { server: process.env.HTTPS_PROXY } });
  const p = await (await b.newContext()).newPage(); p.setDefaultTimeout(120000);
  await p.goto(SITE + '/wp-login.php', { waitUntil: 'networkidle' });
  await p.fill('#user_login', process.env.BDH_USER); await p.fill('#user_pass', process.env.BDH_PASS);
  await p.click('#wp-submit'); await p.waitForURL(/wp-admin/, { timeout: 60000 }).catch(() => {}); await p.waitForLoadState('networkidle');
  const nonce = (await (await p.request.get(SITE + '/wp-admin/admin-ajax.php?action=rest-nonce')).text()).trim();
  const api = async (method, url, data) => { const r = await p.request.fetch(SITE + '/wp-json' + url, { method, headers: { 'X-WP-Nonce': nonce, 'Content-Type': 'application/json' }, data: data ? JSON.stringify(data) : undefined }); let j = null; try { j = await r.json(); } catch (e) {} return { s: r.status(), j }; };
  const plan = [];
  let day = new Date(startDay + 'T' + hhmm + ':00Z');
  for (const slug of order) {
    const id = log[slug].id;
    const cur = await api('GET', `/wp/v2/posts/${id}?context=edit&_fields=id,status,title`);
    if (!['draft', 'future'].includes(cur.j.status)) { console.log(slug, '#' + id, 'is', cur.j.status, '- skipped'); continue; }
    const when = day.toISOString().slice(0, 19);
    const r = await api('POST', `/wp/v2/posts/${id}`, { status: 'future', date_gmt: when });
    plan.push({ date: when.replace('T', ' ') + ' UTC', id, slug, title: cur.j.title.raw, status: r.j.status });
    console.log(when, '#' + id, r.s, r.j.status, '|', cur.j.title.raw.slice(0, 70));
    day = new Date(day.getTime() + 24 * 3600 * 1000);
  }
  fs.writeFileSync(path.join(__dirname, '..', 'build', 'schedule.json'), JSON.stringify(plan, null, 1));
  const fut = await api('GET', '/wp/v2/posts?status=future&context=edit&per_page=100&_fields=id,date_gmt');
  console.log('scheduled on site:', fut.j.length, '| first', fut.j.map(x => x.date_gmt).sort()[0], '| last', fut.j.map(x => x.date_gmt).sort().slice(-1)[0]);
  const dr = await api('GET', '/wp/v2/posts?status=draft&context=edit&per_page=100&_fields=id,slug');
  console.log('still draft:', dr.j.map(x => '#' + x.id + ' ' + x.slug).join(', '));
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
