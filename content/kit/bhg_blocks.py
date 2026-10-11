#!/usr/bin/env python3
"""
Convert a Benders Hub article source file (*.src.html) into WordPress block
markup, resolving photo slots from the live image catalogue.

Source format: top-level elements separated by ONE blank line. Allowed:
<p>, <h2 id>, <h3>, <ul>, <ol>, <table>, <blockquote class="bhg-quote">,
<figure class="bhg-figure" data-slot="..."><figcaption>..</figcaption></figure>,
<div class="bhg-...">...</div> components and <!-- editor notes -->.

Usage: python3 bhg_blocks.py in.src.html out.blocks.html [--featured ID]
"""
import html
import json
import os
import re
import sys

KIT = os.path.dirname(os.path.abspath(__file__))
CATALOG_PATH = os.path.join(KIT, 'image-catalog.json')


def load_catalog():
    with open(CATALOG_PATH, encoding='utf-8') as f:
        return json.load(f)


def split_chunks(src):
    return [c.strip() for c in re.split(r'\n\s*\n', src.strip()) if c.strip()]


def _attr(tag, name):
    m = re.search(r'\s' + name + r'="([^"]*)"', tag)
    return m.group(1) if m else None


def _json(d):
    return (' ' + json.dumps(d, ensure_ascii=False, separators=(',', ':'))) if d else ''


def _list_items(inner):
    items = re.findall(r'<li>(.*?)</li>', inner, re.S)
    return ''.join('<!-- wp:list-item -->\n<li>%s</li>\n<!-- /wp:list-item -->' % i for i in items)


def credit_html(img):
    creator = html.escape(img['creator'])
    if img.get('source'):
        creator = '<a href="%s" target="_blank" rel="noopener">%s</a>' % (html.escape(img['source']), creator)
    lic = html.escape(img['license'] or 'see source')
    if img.get('license_url'):
        lic = '<a href="%s" target="_blank" rel="license noopener">%s</a>' % (html.escape(img['license_url']), lic)
    return 'Photo: %s, %s.' % (creator, lic)


def pick_image(catalog, slot, used, seed=0):
    pool = [c for c in catalog if slot in c['slots']]
    if not pool:
        return None
    pool = pool[seed % len(pool):] + pool[:seed % len(pool)]
    for c in pool:
        if c['id'] not in used:
            return c
    return None


def convert(src, catalog=None, featured_id=None, seed=0):
    """Return (block_markup, warnings, used_image_ids)."""
    catalog = catalog if catalog is not None else load_catalog()
    used = set([featured_id]) if featured_id else set()
    warnings = []
    out = []
    for c in split_chunks(src):
        if c.startswith('<!--'):
            out.append('<!-- wp:html -->\n%s\n<!-- /wp:html -->' % c)
            continue
        m = re.match(r'<(\w+)([^>]*)>', c)
        if not m:
            raise ValueError('Block does not start with a tag: ' + c[:80])
        tag, attrs = m.group(1), m.group(2)
        cls = _attr(attrs, 'class')
        if tag == 'p':
            d = {'className': cls} if cls else {}
            out.append('<!-- wp:paragraph%s -->\n%s\n<!-- /wp:paragraph -->' % (_json(d), c))
        elif tag in ('h2', 'h3', 'h4'):
            level = int(tag[1])
            d = {}
            if level != 2:
                d['level'] = level
            if cls:
                d['className'] = cls
            inner = re.match(r'<h\d[^>]*>(.*)</h\d>$', c, re.S)
            if not inner:
                raise ValueError('Bad heading: ' + c[:80])
            idv = _attr(attrs, 'id')
            klass = 'wp-block-heading' + (' ' + cls if cls else '')
            out.append('<!-- wp:heading%s -->\n<%s class="%s"%s>%s</%s>\n<!-- /wp:heading -->' % (
                _json(d), tag, klass, (' id="%s"' % idv) if idv else '', inner.group(1), tag))
        elif tag in ('ul', 'ol'):
            d = {}
            if tag == 'ol':
                d['ordered'] = True
            if cls:
                d['className'] = cls
            inner = re.match(r'<(?:ul|ol)[^>]*>(.*)</(?:ul|ol)>$', c, re.S)
            if not inner:
                raise ValueError('Bad list: ' + c[:80])
            klass = 'wp-block-list' + (' ' + cls if cls else '')
            out.append('<!-- wp:list%s -->\n<%s class="%s">%s</%s>\n<!-- /wp:list -->' % (
                _json(d), tag, klass, _list_items(inner.group(1)), tag))
        elif tag == 'table':
            inner = re.match(r'<table[^>]*>(.*)</table>$', c, re.S)
            if not inner:
                raise ValueError('Bad table: ' + c[:80])
            out.append('<!-- wp:table -->\n<figure class="wp-block-table"><table class="has-fixed-layout">%s</table></figure>\n<!-- /wp:table -->' % inner.group(1))
        elif tag == 'blockquote':
            inner = re.match(r'<blockquote[^>]*>(.*)</blockquote>$', c, re.S)
            paras = re.findall(r'<p>.*?</p>', inner.group(1), re.S)
            body = ''.join('<!-- wp:paragraph -->\n%s\n<!-- /wp:paragraph -->' % p for p in paras)
            d = {'className': cls} if cls else {}
            out.append('<!-- wp:quote%s -->\n<blockquote class="wp-block-quote%s">%s</blockquote>\n<!-- /wp:quote -->' % (
                _json(d), (' ' + cls) if cls else '', body))
        elif tag == 'figure':
            slot = _attr(attrs, 'data-slot')
            cap_m = re.search(r'<figcaption>(.*)</figcaption>', c, re.S)
            cap = cap_m.group(1).strip() if cap_m else ''
            img = pick_image(catalog, slot, used, seed) if slot else None
            if not img:
                warnings.append('No unused photo for slot "%s": figure skipped.' % slot)
                continue
            used.add(img['id'])
            size = 'large'
            src_i = img['large'] or img['lead']
            if src_i is None:
                warnings.append('Photo %d has no large size: skipped.' % img['id'])
                continue
            if src_i['h'] > src_i['w'] and img.get('lead'):
                size, src_i = 'bdh-lead', img['lead']
            caption = (cap + ' ' if cap else '') + credit_html(img)
            d = {'id': img['id'], 'sizeSlug': size, 'linkDestination': 'none', 'className': 'bhg-figure'}
            out.append('<!-- wp:image%s -->\n<figure class="wp-block-image size-%s bhg-figure"><img src="%s" alt="%s" class="wp-image-%d"/><figcaption class="wp-element-caption">%s</figcaption></figure>\n<!-- /wp:image -->' % (
                _json(d), size, src_i['url'], html.escape(img['alt'], quote=True), img['id'], caption))
        elif tag == 'div':
            out.append('<!-- wp:html -->\n%s\n<!-- /wp:html -->' % c)
        else:
            raise ValueError('Unsupported top-level tag <%s>: %s' % (tag, c[:80]))
    return '\n\n'.join(out) + '\n', warnings, used


def featured_for(slot, catalog=None, seed=0):
    catalog = catalog if catalog is not None else load_catalog()
    img = pick_image(catalog, slot, set(), seed)
    return img['id'] if img else None


if __name__ == '__main__':
    args = sys.argv[1:]
    feat = None
    if '--featured' in args:
        i = args.index('--featured')
        feat = int(args[i + 1])
        del args[i:i + 2]
    with open(args[0], encoding='utf-8') as f:
        markup, warns, _ = convert(f.read(), featured_id=feat)
    with open(args[1], 'w', encoding='utf-8') as f:
        f.write(markup)
    for w in warns:
        print('WARNING:', w)
