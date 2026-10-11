// Usage: node axe-only.js <base> <axe.min.js> [--proxy] path1 path2 ...
const fs = require('fs');
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
(async () => {
  const [base, axePath, ...rest] = process.argv.slice(2);
  const proxy = rest[0] === '--proxy'; const paths = proxy ? rest.slice(1) : rest;
  const axe = fs.readFileSync(axePath, 'utf8');
  const b = await chromium.launch(proxy ? { proxy: { server: process.env.HTTPS_PROXY } } : {});
  let bad = 0;
  for (const path of paths) {
    const ctx = await b.newContext({ viewport: { width: 1366, height: 900 }, ignoreHTTPSErrors: true });
    const p = await ctx.newPage();
    await p.goto(base + path, { waitUntil: 'networkidle', timeout: 90000 }).catch(() => {});
    await p.addScriptTag({ content: axe });
    const r = await p.evaluate(async () => { const res = await axe.run(document, { runOnly: { type: 'tag', values: ['wcag2a', 'wcag2aa', 'wcag21aa'] } }); return res.violations.map(v => ({ id: v.id, impact: v.impact, nodes: v.nodes.length, html: v.nodes.slice(0, 3).map(n => n.html.slice(0, 140)) })); });
    console.log((r.length ? 'FAIL ' : 'PASS ') + path, JSON.stringify(r));
    if (r.length) bad++;
    await ctx.close();
  }
  await b.close();
  process.exit(bad ? 1 : 0);
})();
