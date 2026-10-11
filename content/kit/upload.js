// Upload built articles to bendershub.com as DRAFTS.
// Usage: BDH_USER=… BDH_PASS=… node upload.js <slug> [<slug> …]
// - Rewrites (post_id set) only touch posts that are still drafts.
// - New articles are created as drafts; re-runs update the draft with the same slug.
// - Anything already published is skipped and reported, never overwritten.
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs');
const path = require('path');
const SITE = 'https://bendershub.com';
const BUILD = path.join(__dirname, '..', 'build');

(async () => {
  const slugs = process.argv.slice(2);
  const b = await chromium.launch({ proxy: { server: process.env.HTTPS_PROXY } });
  const p = await (await b.newContext()).newPage();
  p.setDefaultTimeout(120000);
  await p.goto(SITE + '/wp-login.php', { waitUntil: 'networkidle' });
  await p.fill('#user_login', process.env.BDH_USER); await p.fill('#user_pass', process.env.BDH_PASS);
  await p.click('#wp-submit');
  await p.waitForURL(/wp-admin/, { timeout: 60000 }).catch(() => {});
  await p.waitForLoadState('networkidle');
  const nonce = (await (await p.request.get(SITE + '/wp-admin/admin-ajax.php?action=rest-nonce')).text()).trim();
  if (!/^[a-f0-9]{10}$/.test(nonce)) { console.log('login failed'); await b.close(); process.exit(1); }
  const api = async (method, url, data) => {
    const r = await p.request.fetch(SITE + '/wp-json' + url, { method, headers: { 'X-WP-Nonce': nonce, 'Content-Type': 'application/json' }, data: data ? JSON.stringify(data) : undefined });
    let j = null; try { j = await r.json(); } catch (e) {}
    return { s: r.status(), j };
  };
  const logPath = path.join(BUILD, 'upload-log.json');
  const log = fs.existsSync(logPath) ? JSON.parse(fs.readFileSync(logPath)) : {};
  for (const slug of slugs) {
    const f = path.join(BUILD, slug + '.payload.json');
    if (!fs.existsSync(f)) { console.log(slug, ': no payload, skipped'); continue; }
    const pl = JSON.parse(fs.readFileSync(f));
    let id = pl.post_id;
    if (!id) {
      const ex = await api('GET', '/wp/v2/posts?slug=' + slug + '&status=draft,publish,pending,private,future&context=edit&_fields=id,status');
      if (ex.j && ex.j.length) id = ex.j[0].id;
    }
    if (id) {
      const cur = await api('GET', '/wp/v2/posts/' + id + '?context=edit&_fields=id,status,slug');
      if (cur.s !== 200) { console.log(slug, ': post', id, 'not found (HTTP', cur.s + '), skipped'); continue; }
      if (cur.j.status !== 'draft') { console.log(slug, ': post', id, 'is', cur.j.status, '- NOT overwritten'); continue; }
    }
    const body = { title: pl.title, excerpt: pl.excerpt, content: pl.content, categories: pl.categories, featured_media: pl.featured_media, status: 'draft' };
    if (!pl.post_id) body.slug = slug;
    const r = id ? await api('POST', '/wp/v2/posts/' + id, body) : await api('POST', '/wp/v2/posts', body);
    if (r.s >= 300) { console.log(slug, ': SAVE FAILED', r.s, JSON.stringify(r.j).slice(0, 300)); continue; }
    const g = await api('GET', '/wp/v2/posts/' + r.j.id + '?context=edit');
    const same = g.j.content.raw === pl.content;
    log[slug] = { id: r.j.id, status: g.j.status, identical: same, featured: g.j.featured_media, categories: g.j.categories, at: new Date().toISOString() };
    console.log(slug, ':', id ? 'updated' : 'created', '#' + r.j.id, '|', g.j.status, '| content identical:', same, '| featured', g.j.featured_media, '| cats', g.j.categories.join(','));
  }
  fs.writeFileSync(logPath, JSON.stringify(log, null, 1));
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
