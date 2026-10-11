// Render each HTML file in a folder to a 1200x675 PNG. Usage: node render.js <srcdir> <outdir>
const { chromium } = require(require('child_process').execSync('npm root -g').toString().trim() + '/playwright');
const fs = require('fs'), path = require('path');
(async () => {
  const [src, out] = process.argv.slice(2);
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1200, height: 675 }, deviceScaleFactor: 1 });
  const p = await ctx.newPage();
  for (const f of fs.readdirSync(src).filter(f => f.endsWith('.html'))) {
    await p.goto('file://' + path.join(src, f)); await p.waitForTimeout(300);
    const o = path.join(out, f.replace('.html', '.png'));
    await p.screenshot({ path: o, type: 'png' });
    console.log('rendered', o, fs.statSync(o).size);
  }
  await b.close();
})().catch(e => { console.error('ERR', e.message); process.exit(1); });
