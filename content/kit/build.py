#!/usr/bin/env python3
"""
Build WordPress payloads for validated articles.

Usage: python3 build.py <slug> [<slug> ...]
Writes ../build/<slug>.payload.json  ({title, slug, excerpt, content,
categories, featured_media, post_id}) for upload.js. Featured photos are
spread across the library: the least-used photo in the article's featured
slot wins, and that photo is not repeated inside the article body.
"""
import json
import os
import sys

KIT = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(KIT)
SRC = os.path.join(ROOT, 'src')
BUILD = os.path.join(ROOT, 'build')
sys.path.insert(0, KIT)
import bhg_blocks  # noqa: E402
import validate  # noqa: E402

CATS = {'guides': 3, 'fabrication-theory': 8, 'safety': 46, 'tutorials': 18, 'workshop': 33}
TOPICS = {t['slug']: t for t in json.load(open(os.path.join(KIT, 'topics.json'), encoding='utf-8'))}
EXISTING = {'best-tube-bender-for-home-garage': 95}
USAGE_PATH = os.path.join(BUILD, 'featured-usage.json')


def main(slugs):
    os.makedirs(BUILD, exist_ok=True)
    catalog = bhg_blocks.load_catalog()
    usage = json.load(open(USAGE_PATH)) if os.path.exists(USAGE_PATH) else {}
    ok = True
    for slug in slugs:
        errs, _ = validate.check(slug)
        if errs:
            print('SKIP %s: %d validation error(s)' % (slug, len(errs)))
            ok = False
            continue
        man = json.load(open(os.path.join(SRC, slug + '.json'), encoding='utf-8'))
        src = open(os.path.join(SRC, slug + '.src.html'), encoding='utf-8').read()
        slot = man.get('featured_slot') or TOPICS.get(slug, {}).get('featured_slot')
        pool = [c for c in catalog if slot in c['slots']] or [c for c in catalog if 'workshop' in c['slots']]
        prev = usage.get(slug)
        if prev and any(c['id'] == prev for c in pool):
            feat = prev
        else:
            feat = min(pool, key=lambda c: (usage.get('_count', {}).get(str(c['id']), 0), c['id']))['id']
            counts = usage.setdefault('_count', {})
            counts[str(feat)] = counts.get(str(feat), 0) + 1
            usage[slug] = feat
        content, warns, used = bhg_blocks.convert(src, catalog, featured_id=feat, seed=sum(map(ord, slug)))
        topic = TOPICS.get(slug, {})
        payload = {
            'slug': slug,
            'title': man['title'],
            'excerpt': man['excerpt'],
            'content': content,
            'categories': [CATS[man.get('category') or topic.get('category')]],
            'featured_media': feat,
            'post_id': topic.get('rewrite_post_id') or EXISTING.get(slug),
        }
        json.dump(payload, open(os.path.join(BUILD, slug + '.payload.json'), 'w', encoding='utf-8'), ensure_ascii=False)
        print('built %s: featured #%d, %d body photos%s' % (slug, feat, len(used) - 1, ('; ' + '; '.join(warns)) if warns else ''))
    json.dump(usage, open(USAGE_PATH, 'w'), indent=1)
    return ok


if __name__ == '__main__':
    sys.exit(0 if main(sys.argv[1:]) else 1)
