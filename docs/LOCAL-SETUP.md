# Working on the theme locally

Everything from the cloud sessions is in this repository (branch `claude/cool-cray-7i4mr0`). This page is the hand-over for continuing on your own computer, with Claude Code or without it.

## 1. Get the code

```bash
git clone https://github.com/ahmad786raza/website.git
cd website
git checkout claude/cool-cray-7i4mr0
```

To continue with Claude on your machine: open the folder in the Claude Desktop app, or run `claude remote-control` in it; the session then appears in the Claude Code app. A cloud session cannot reach your computer, so this is the only way to run things locally with Claude.

## 2. What is where

| Path | What |
|---|---|
| `hoverboards-guide/` | Theme source, version 3.5.3 (the version live on hoverboardsguide.com) |
| `dist/hoverboards-guide-*.zip` | Installable zips: 3.5.3 current, 3.4.1 / 3.3.0 / 3.2.0 / 3.0.0 rollbacks, 4.0.0 reference |
| `docs/HOVERBOARDS-REPORT.md` | Full report; the status notes at the top are the change log, newest first |
| `docs/articles/` | Sources of the two published articles and their graphic templates |
| `docs/screenshots/` | Live screenshots per release |
| `tools/hoverboards-qa/` | Deploy, QA (52 checks), screenshot and local-replica scripts |
| `tools/publishing/` | REST publishing helper, logged-in preview, HTML-to-PNG renderer, Customizer read/set |
| `tools/local/setup-local.sh` | Builds the local WordPress replica described below |

## 3. Local WordPress replica (no MySQL)

Prerequisites: PHP 8.1+ with the sqlite3 extension, curl, unzip, Node 18+, Playwright (`npm i -g playwright && npx playwright install chromium`).

```bash
tools/local/setup-local.sh            # creates ./local-site/www with WordPress + SQLite, the theme, and the live content imported through the public REST API
php -S 127.0.0.1:8080 -t local-site/www
```

Admin is `http://127.0.0.1:8080/wp-admin/` with `admin` / `admin` (local only). Featured images are not downloaded by the script; the theme falls back to its bundled photos, which is what the cloud replica used too. `tools/hoverboards-qa/attach.php <www> <media-dir>` attaches images if you download them.

The script was written in a cloud session that could not reach wordpress.org, so its download steps are untested there. If a step fails, the manual equivalent is: unzip WordPress into `local-site/www`, unzip the "SQLite Database Integration" plugin into `wp-content/plugins`, copy its `db.copy` to `wp-content/db.php` with the two placeholders filled in, and create `wp-config.php` from the block in the script.

## 4. Everyday commands

```bash
# copy the theme into the replica after editing
rm -rf local-site/www/wp-content/themes/hoverboards-guide && cp -r hoverboards-guide local-site/www/wp-content/themes/

# QA (52 checks) against the replica, or the live site
node tools/hoverboards-qa/qa.js http://127.0.0.1:8080
QA_PROXY= node tools/hoverboards-qa/qa.js https://hoverboardsguide.com

# screenshots
node tools/hoverboards-qa/clips.js http://127.0.0.1:8080/hoverboard-buying-guide/ shots/guide
node tools/hoverboards-qa/elshot.js http://127.0.0.1:8080/ '#compare' shots/compare 1920,1366,390

# build a release zip (bump Version in style.css and HBG_VERSION in functions.php first)
zip -qr dist/hoverboards-guide-X.Y.Z.zip hoverboards-guide -x '*.DS_Store'

# deploy to the live site (credentials from the environment only; never commit them)
WP_USER=... WP_PASS=... node tools/hoverboards-qa/deploy.js install dist/hoverboards-guide-X.Y.Z.zip

# publish an article: upload images, create a draft, preview, then publish
WP_USER=... WP_PASS=... node tools/publishing/publish.js upload-many manifest.json uploaded.json
WP_USER=... WP_PASS=... node tools/publishing/publish.js create post.json
WP_USER=... WP_PASS=... node tools/publishing/preview.js "https://hoverboardsguide.com/?p=ID&preview=true" shots/p
WP_USER=... WP_PASS=... node tools/publishing/publish.js update ID post-publish.json
```

The scripts in the cloud used `HTTPS_PROXY` because the container had to; on your machine leave it unset, and pass `--proxy` to nothing.

## 5. Things only you can do

- Change the WordPress password that was shared in chat, then use the new one through `WP_USER` / `WP_PASS`.
- Confirm `hoverboardsgu-20` is an approved tracking ID in Amazon Associates.
- Click the three ASIN links in the teenagers article once (Amazon was unreachable from the cloud).
- Add the two new articles to the home page's curated "Reviews & comparisons" list in the Customizer if you want them there.
- Upload real product photos under Hoverboards Guide → Products to replace the comparison-table tiles.
