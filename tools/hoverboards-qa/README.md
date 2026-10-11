# Local QA kit for the Hoverboards Guide theme

Used on 11 October 2026 against a local WordPress 7.1 + SQLite replica of hoverboardsguide.com.

- `import.php <www> <live-json-dir>` — imports the public REST export (categories, posts, pages) and activates the theme.
- `attach.php <www> <media-dir>` — attaches downloaded featured images to the imported posts.
- `shot.js <url> <out-prefix> [--proxy]` — desktop + mobile screenshots with overflow and console checks.
- `qa.js <base-url> [axe.min.js]` — 52 functional, SEO, mobile, keyboard, performance and axe-core checks (results in `docs/hoverboards-qa-results.json`).
- `admin-qa.js <base-url>` — logs into wp-admin and exercises the Setup tools, the Products editor and their Undo actions.

Requires Node 18+, Playwright (global) and optionally `npm install axe-core`.

## Scripts added during the October 2026 sessions

All take credentials only from `WP_USER` / `WP_PASS` environment variables and route through `HTTPS_PROXY` when the `--proxy` flag or the deploy script is used.

- `deploy.js` — `inventory | install <zip> | status | setup x <actions>` against the live admin (Playwright).
- `qa.js` — 52 functional/SEO/a11y/perf checks; `node qa.js http://127.0.0.1:8080 [axe.min.js]`, `QA_PROXY=$HTTPS_PROXY` for the live site.
- `sections.js`, `clips.js`, `elshot.js`, `shot.js` — screenshots per section, viewport clips down a page, one element at several widths, full page.
- `axe-only.js` — accessibility pass only. `hubs-local.php` — creates the hub categories on a local replica.
- `../publishing/` — REST publishing helper, logged-in preview, HTML-to-PNG renderer, Customizer read/set.
