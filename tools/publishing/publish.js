// Publish helper via the WordPress REST API from a logged-in browser session.
// Usage: WP_USER=… WP_PASS=… node publish.js whoami
//        … node publish.js upload <file> [title] [alt] [caption]      -> prints JSON {id, source_url}
//        … node publish.js create <post.json>                         -> creates a post from JSON (status as given)
//        … node publish.js update <id> <post.json>
//        … node publish.js get <id>
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs'); const path = require('path');
const SITE = process.env.SITE || 'https://hoverboardsguide.com';
const [cmd, a1, a2, a3, a4] = process.argv.slice(2);
(async () => {
  const b = await chromium.launch({ proxy: { server: process.env.HTTPS_PROXY } });
  const ctx = await b.newContext({ viewport: { width: 1366, height: 900 } });
  const p = await ctx.newPage(); p.setDefaultTimeout(120000);
  await p.goto(SITE + '/wp-login.php', { waitUntil: 'domcontentloaded' });
  await p.fill('#user_login', process.env.WP_USER); await p.fill('#user_pass', process.env.WP_PASS);
  await Promise.all([p.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => {}), p.click('#wp-submit')]);
  await p.waitForTimeout(1000);
  if (!/wp-admin/.test(p.url())) { console.log('login FAILED'); await b.close(); process.exit(1); }
  const nonce = (await (await p.request.get(SITE + '/wp-admin/admin-ajax.php?action=rest-nonce')).text()).trim();
  if (!/^[a-f0-9]{10}$/.test(nonce)) { console.log('nonce FAILED', nonce.slice(0, 100)); await b.close(); process.exit(1); }
  const H = { 'X-WP-Nonce': nonce };
  const out = (r) => r.text().then(t => { try { return JSON.parse(t); } catch (e) { return { raw: t.slice(0, 500), status: r.status() }; } });
  if (cmd === 'whoami') {
    const r = await p.request.get(SITE + '/wp-json/wp/v2/users/me?context=edit', { headers: H });
    const j = await out(r); console.log(r.status(), JSON.stringify({ id: j.id, name: j.name, roles: j.roles }));
  } else if (cmd === 'upload') {
    const file = a1; const mime = /\.png$/i.test(file) ? 'image/png' : /\.webp$/i.test(file) ? 'image/webp' : /\.svg$/i.test(file) ? 'image/svg+xml' : 'image/jpeg';
    const r = await p.request.post(SITE + '/wp-json/wp/v2/media', { headers: H, multipart: { file: { name: path.basename(file), mimeType: mime, buffer: fs.readFileSync(file) }, title: a2 || path.basename(file), alt_text: a3 || '', caption: a4 || '' } });
    const j = await out(r); console.log(r.status(), JSON.stringify({ id: j.id, source_url: j.source_url, raw: j.raw }));
  } else if (cmd === 'upload-many') {
    const items = JSON.parse(fs.readFileSync(a1, 'utf8')); const res = {};
    for (const it of items) {
      const mime = /\.png$/i.test(it.file) ? 'image/png' : /\.webp$/i.test(it.file) ? 'image/webp' : 'image/jpeg';
      const r = await p.request.post(SITE + '/wp-json/wp/v2/media', { headers: H, multipart: { file: { name: path.basename(it.file), mimeType: mime, buffer: fs.readFileSync(it.file) }, title: it.title, alt_text: it.alt, caption: it.caption } });
      const j = await out(r); res[path.basename(it.file).replace(/\.(png|jpg|webp)$/i, '')] = { id: j.id, url: j.source_url, w: j.media_details && j.media_details.width, h: j.media_details && j.media_details.height, status: r.status(), raw: j.raw };
      console.log('uploaded', path.basename(it.file), r.status(), j.id || j.raw);
    }
    fs.writeFileSync(a2, JSON.stringify(res, null, 1)); console.log('wrote', a2);
  } else if (cmd === 'create' || cmd === 'update') {
    const body = JSON.parse(fs.readFileSync(cmd === 'create' ? a1 : a2, 'utf8'));
    const url = SITE + '/wp-json/wp/v2/posts' + (cmd === 'update' ? '/' + a1 : '');
    const r = await p.request.post(url, { headers: Object.assign({ 'Content-Type': 'application/json' }, H), data: body });
    const j = await out(r); console.log(r.status(), JSON.stringify({ id: j.id, status: j.status, link: j.link, raw: j.raw, code: j.code, message: j.message }));
  } else if (cmd === 'get') {
    const r = await p.request.get(SITE + '/wp-json/wp/v2/posts/' + a1 + '?context=edit', { headers: H });
    const j = await out(r); console.log(r.status(), JSON.stringify({ id: j.id, status: j.status, link: j.link, title: j.title && j.title.raw, featured_media: j.featured_media, categories: j.categories }));
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
