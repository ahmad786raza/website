# Local QA kit for the Hoverboards Guide theme

Used on 11 October 2026 against a local WordPress 7.1 + SQLite replica of hoverboardsguide.com.

- `import.php <www> <live-json-dir>` — imports the public REST export (categories, posts, pages) and activates the theme.
- `attach.php <www> <media-dir>` — attaches downloaded featured images to the imported posts.
- `shot.js <url> <out-prefix> [--proxy]` — desktop + mobile screenshots with overflow and console checks.
- `qa.js <base-url> [axe.min.js]` — 52 functional, SEO, mobile, keyboard, performance and axe-core checks (results in `docs/hoverboards-qa-results.json`).
- `admin-qa.js <base-url>` — logs into wp-admin and exercises the Setup tools, the Products editor and their Undo actions.

Requires Node 18+, Playwright (global) and optionally `npm install axe-core`.
