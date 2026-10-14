#!/usr/bin/env bash
# Builds a local WordPress replica of hoverboardsguide.com with the theme active, using SQLite (no MySQL needed).
# Usage: tools/local/setup-local.sh [target-dir]   (default: ./local-site)
# Needs: php 8.1+ (with sqlite3 and zip extensions), curl, unzip, node 18+, playwright installed globally.
# Written in a cloud session that could not reach wordpress.org, so the download steps are untested there; run it on your machine.
set -euo pipefail
REPO="$(cd "$(dirname "$0")/../.." && pwd)"
TARGET="${1:-$REPO/local-site}"
WWW="$TARGET/www"; LIVE="$TARGET/live"
mkdir -p "$TARGET" "$LIVE"

if [ ! -f "$WWW/wp-load.php" ]; then
  echo "== WordPress core"
  curl -fsSL https://wordpress.org/latest.zip -o "$TARGET/wordpress.zip"
  unzip -q "$TARGET/wordpress.zip" -d "$TARGET"
  mv "$TARGET/wordpress" "$WWW"
fi

if [ ! -d "$WWW/wp-content/plugins/sqlite-database-integration" ]; then
  echo "== SQLite database integration plugin"
  curl -fsSL https://downloads.wordpress.org/plugin/sqlite-database-integration.latest-stable.zip -o "$TARGET/sqlite.zip"
  unzip -q "$TARGET/sqlite.zip" -d "$WWW/wp-content/plugins"
  cp "$WWW/wp-content/plugins/sqlite-database-integration/db.copy" "$WWW/wp-content/db.php"
  sed -i.bak "s#{SQLITE_IMPLEMENTATION_FOLDER_PATH}#$WWW/wp-content/plugins/sqlite-database-integration#; s#{SQLITE_PLUGIN}#sqlite-database-integration/load.php#" "$WWW/wp-content/db.php" && rm -f "$WWW/wp-content/db.php.bak"
fi

if [ ! -f "$WWW/wp-config.php" ]; then
  echo "== wp-config.php"
  SALTS="$(curl -fsSL https://api.wordpress.org/secret-key/1.1/salt/ || true)"
  cat > "$WWW/wp-config.php" <<CFG
<?php
define( 'DB_NAME', 'local' ); define( 'DB_USER', '' ); define( 'DB_PASSWORD', '' ); define( 'DB_HOST', '' ); define( 'DB_CHARSET', 'utf8' ); define( 'DB_COLLATE', '' );
\$table_prefix = 'wp_';
$SALTS
define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true ); define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_HOME', 'http://127.0.0.1:8080' ); define( 'WP_SITEURL', 'http://127.0.0.1:8080' );
define( 'WP_AUTO_UPDATE_CORE', false ); define( 'AUTOMATIC_UPDATER_DISABLED', true ); define( 'DISABLE_WP_CRON', true );
if ( ! defined( 'ABSPATH' ) ) { define( 'ABSPATH', __DIR__ . '/' ); }
require_once ABSPATH . 'wp-settings.php';
CFG
fi

echo "== theme"
rm -rf "$WWW/wp-content/themes/hoverboards-guide"
cp -r "$REPO/hoverboards-guide" "$WWW/wp-content/themes/hoverboards-guide"

if [ ! -f "$WWW/wp-content/database/.ht.sqlite" ]; then
  echo "== install WordPress (admin / admin, local only)"
  php -r '$_SERVER["HTTP_HOST"]="127.0.0.1:8080"; define("WP_INSTALLING", true); require "'"$WWW"'/wp-load.php"; require_once ABSPATH."wp-admin/includes/upgrade.php"; wp_install("Hoverboards Guide", "admin", "admin@example.com", true, "", "admin");'
fi

if [ ! -f "$LIVE/categories.json" ]; then
  echo "== export live content through the public REST API"
  curl -fsSL "https://hoverboardsguide.com/wp-json/wp/v2/categories?per_page=100" -o "$LIVE/categories.json"
  for pg in 1 2 3; do curl -fsSL "https://hoverboardsguide.com/wp-json/wp/v2/posts?per_page=100&page=$pg" -o "$LIVE/posts-full-$pg.json" || echo "[]" > "$LIVE/posts-full-$pg.json"; done
  curl -fsSL "https://hoverboardsguide.com/wp-json/wp/v2/pages?per_page=100" -o "$LIVE/pages-full.json"
fi

echo "== import content and activate the theme"
php "$REPO/tools/hoverboards-qa/import.php" "$WWW" "$LIVE"
php "$REPO/tools/hoverboards-qa/hubs-local.php" "$WWW" || true

echo
echo "Done. Start the site with:   php -S 127.0.0.1:8080 -t \"$WWW\""
echo "Admin: http://127.0.0.1:8080/wp-admin/  (admin / admin, local only)"
echo "QA:    node tools/hoverboards-qa/qa.js http://127.0.0.1:8080"
