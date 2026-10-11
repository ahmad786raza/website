#!/usr/bin/env python3
"""
Check a Benders Hub article source against the house rules.

Usage: python3 validate.py <slug> [<slug> ...]   (files in ../src/)
Exit code 1 if any ERROR. WARN lines are advisory.
"""
import html
import json
import os
import re
import sys

KIT = os.path.dirname(os.path.abspath(__file__))
SRC = os.path.join(os.path.dirname(KIT), 'src')
sys.path.insert(0, KIT)
import bhg_blocks  # noqa: E402

TOPICS = json.load(open(os.path.join(KIT, 'topics.json'), encoding='utf-8'))
TOPIC_SLUGS = {t['slug'] for t in TOPICS}
KNOWN_SLUGS = TOPIC_SLUGS | {'best-tube-bender-for-home-garage', 'diy-garage-workshop', 'conduit-bending-electricians'}
LIVE_PATHS = {'/quiz/', '/calculator/', '/visualizer/', '/compare/', '/database/', '/glossary/', '/safety/',
              '/methodology/', '/affiliate-disclosure/', '/about/', '/editorial-standards/', '/image-credits/',
              '/workflow/', '/compatibility/', '/prioritizer/', '/tracker/'}
SLOTS = set()
for c in bhg_blocks.load_catalog():
    SLOTS.update(c['slots'])
SLOTS.update({'cnc-bender', 'hydraulic-bender', 'notcher', 'offroad'})  # may be filled by later imports
DISCLOSURE = 'As an Amazon Associate I earn from qualifying purchases.'
AMZ_RE = re.compile(r'^https://www\.amazon\.com/dp/([A-Z0-9]{10})\?tag=polyfreeplanet-20$')
MANIFEST_KEYS = {'slug', 'title', 'excerpt', 'category', 'featured_slot', 'primary_keyword', 'asins', 'sources'}


def text_of(s):
    s = re.sub(r'<!--.*?-->', ' ', s, flags=re.S)
    s = re.sub(r'<[^>]+>', ' ', s)
    return ' '.join(html.unescape(s).split())


def words(s):
    return len(text_of(s).split())


def check(slug):
    errs, warns = [], []
    src_path = os.path.join(SRC, slug + '.src.html')
    man_path = os.path.join(SRC, slug + '.json')
    if not os.path.exists(src_path):
        return ['missing ' + src_path], warns
    src = open(src_path, encoding='utf-8').read()
    man = {}
    if not os.path.exists(man_path):
        errs.append('missing manifest ' + man_path)
    else:
        try:
            man = json.load(open(man_path, encoding='utf-8'))
        except Exception as e:  # noqa: BLE001
            errs.append('manifest is not valid JSON: %s' % e)
        missing = MANIFEST_KEYS - set(man)
        if missing:
            errs.append('manifest missing keys: %s' % ', '.join(sorted(missing)))
        if man.get('slug') != slug:
            errs.append('manifest slug does not match file name')
        if len(man.get('excerpt', '')) > 160:
            errs.append('excerpt is %d chars (max 160)' % len(man.get('excerpt', '')))
        if man.get('featured_slot') and man['featured_slot'] not in SLOTS:
            errs.append('unknown featured_slot "%s"' % man['featured_slot'])
        if re.search(r'\b(review)\b', man.get('title', ''), re.I) and not re.search(r'spec|research|who it', man.get('title', ''), re.I):
            warns.append('title says "review": make clear it is research-based, not hands-on')

    # conversion
    try:
        _, conv_warns, _ = bhg_blocks.convert(src)
        warns += conv_warns
    except Exception as e:  # noqa: BLE001
        errs.append('converter failed: %s' % e)

    # forbidden markup
    if re.search(r'<h1[\s>]', src, re.I):
        errs.append('contains <h1> (the post title is the H1)')
    if re.search(r'<(style|script|iframe)[\s>]', src, re.I):
        errs.append('contains <style>/<script>/<iframe>')
    if re.search(r'\sstyle="', src):
        errs.append('contains inline style attributes')
    for chunk in bhg_blocks.split_chunks(src):
        if not re.match(r'<(p|h2|h3|h4|ul|ol|table|blockquote|figure|div)[\s>]|<!--', chunk):
            errs.append('bad top-level block: ' + chunk[:60].replace('\n', ' '))

    # length
    total = words(src)
    if total < 3000:
        errs.append('only %d words (min 3000)' % total)
    parts = re.split(r'<h2 id="([^"]+)">', src)
    h2_ids = parts[1::2]
    for i in range(1, len(parts), 2):
        n = words(parts[i + 1])
        lim = 150 if re.search(r'faq|checklist', parts[i]) else 200
        if n < lim:
            errs.append('section #%s has %d words (min %d)' % (parts[i], n, lim))
    if words(parts[0]) < 120:
        warns.append('intro is short (%d words)' % words(parts[0]))
    if len(re.findall(r'<h2[\s>]', src)) != len(h2_ids):
        errs.append('every <h2> needs an id="..."')

    # required components
    req = {
        'quick answer box (.bhg-answer)': 'class="bhg-answer"',
        'table of contents (.bhg-toc)': 'class="bhg-toc"',
        'key insight quote (blockquote.bhg-quote)': 'class="bhg-quote"',
        'checklist (.bhg-checklist)': 'class="bhg-checklist"',
        'FAQ (.bhg-faq)': 'class="bhg-faq"',
        'CTA band (.bhg-cta)': 'class="bhg-cta"',
    }
    for name, needle in req.items():
        if needle not in src:
            errs.append('missing ' + name)
    toc = re.search(r'<div class="bhg-toc">(.*?)</div>', src, re.S)
    if toc:
        hrefs = re.findall(r'href="#([^"]+)"', toc.group(1))
        ids = set(re.findall(r'\sid="([^"]+)"', src))
        for h in hrefs:
            if h not in ids:
                errs.append('TOC link #%s has no matching id' % h)
        for h in h2_ids:
            if h not in hrefs and h != 'faq':
                warns.append('H2 #%s is not in the TOC' % h)
    if len(re.findall(r'<table[\s>]', src)) < 2:
        errs.append('needs at least 2 comparison tables')
    if len(re.findall(r'class="bhg-callout ', src)) < 3:
        errs.append('needs at least 3 callouts (.bhg-callout)')
    if len(re.findall(r'class="bhg-example"', src)) < 2:
        errs.append('needs at least 2 real-life examples (.bhg-example)')
    if not any('mistake' in h for h in h2_ids):
        errs.append('needs a "common mistakes" H2 (id containing "mistake")')
    if not any('tip' in h for h in h2_ids):
        errs.append('needs a "pro tips" H2 (id containing "tip")')
    faq_n = len(re.findall(r'class="bhg-faq-item"', src))
    if not 4 <= faq_n <= 6:
        errs.append('FAQ has %d items (need 4-6)' % faq_n)
    emo = sum(src.count(e) for e in ('✅', '⚠️', '💡'))
    if emo < 3:
        warns.append('few emojis (✅ ⚠️ 💡): %d' % emo)
    figs = re.findall(r'<figure class="bhg-figure" data-slot="([^"]+)"', src)
    if not 2 <= len(figs) <= 6:
        errs.append('needs 2-6 photos (figure.bhg-figure), found %d' % len(figs))
    filled = set()
    for c in bhg_blocks.load_catalog():
        filled.update(c['slots'])
    for s in figs:
        if s not in SLOTS:
            errs.append('unknown photo slot "%s"' % s)
    if len([s for s in figs if s in filled]) < 2:
        errs.append('at least 2 figures must use slots that already have photos: %s' % ', '.join(sorted(filled)))
    if '<figure' in src and re.search(r'<figure(?! class="bhg-figure" data-slot=)', src):
        errs.append('figures must be <figure class="bhg-figure" data-slot="..."> (no <img>, credit is automatic)')
    if '<img' in src:
        errs.append('do not use <img>: use figure data-slot')

    # links
    amz = re.findall(r'<a\s([^>]*href="https?://[^"]*amazon\.[^"]*"[^>]*)>', src)
    asins_used = set()
    for a in amz:
        href = re.search(r'href="([^"]+)"', a).group(1)
        m = AMZ_RE.match(href)
        if not m:
            errs.append('Amazon link not in the form https://www.amazon.com/dp/ASIN?tag=polyfreeplanet-20: ' + href)
            continue
        asins_used.add(m.group(1))
        rel = re.search(r'rel="([^"]+)"', a)
        if not rel or not all(x in rel.group(1).split() for x in ('nofollow', 'sponsored', 'noopener')):
            errs.append('Amazon link missing rel="nofollow sponsored noopener": ' + href)
        if 'target="_blank"' not in a:
            errs.append('Amazon link missing target="_blank": ' + href)
    if amz:
        pos_disc = src.find(DISCLOSURE)
        first_amz = src.find('amazon.com/dp/')
        if pos_disc < 0:
            errs.append('Amazon links present but the exact disclosure sentence is missing')
        elif first_amz < pos_disc:
            errs.append('the disclosure must appear before the first Amazon link (link to #product-ids above it instead)')
    man_asins = {a.get('asin') for a in man.get('asins', []) if isinstance(a, dict)}
    for a in asins_used - man_asins:
        errs.append('ASIN %s used but not listed (with evidence) in manifest' % a)
    for a in man.get('asins', []):
        if not isinstance(a, dict) or not a.get('asin') or not a.get('product') or not a.get('evidence'):
            errs.append('manifest asins entries need asin, product and evidence')
            break
    if not amz:
        warns.append('no Amazon product links')
    for slug_link in re.findall(r'data-bhg-link="([^"]+)"', src):
        if slug_link not in KNOWN_SLUGS:
            errs.append('data-bhg-link to unknown slug "%s"' % slug_link)
        if slug_link == slug:
            errs.append('article links to itself')
    n_internal = len(re.findall(r'data-bhg-link="', src))
    if n_internal < 3:
        warns.append('only %d article-to-article links (aim for 3-6)' % n_internal)
    for path in re.findall(r'href="(/[^"#]*)"', src):
        if path not in LIVE_PATHS and not re.match(r'/[a-z0-9-]+/$', path):
            errs.append('internal href "%s" is not a known live page' % path)
        elif path not in LIVE_PATHS:
            if not re.search(r'data-bhg-link="%s"[^>]*href="%s"|href="%s"[^>]*data-bhg-link=' % (re.escape(path.strip('/')), re.escape(path), re.escape(path)), src):
                errs.append('link to %s must use data-bhg-link (target is not live yet)' % path)
    for href in re.findall(r'href="(https?://[^"]+)"', src):
        if 'amazon.' in href:
            continue
        if 'bendershub.com' in href:
            errs.append('use relative links for bendershub.com: ' + href)

    # honesty / Amazon policy
    if re.search(r'\$\s?\d', text_of(src)):
        errs.append('contains a price ($ + digits); prices are not allowed')
    if re.search(r'★|\b\d(\.\d)?\s?(out of|/)\s?5\b|\bstar rating', text_of(src), re.I):
        errs.append('contains a star rating')
    if re.search(r'\bwe (tested|have tested|put .* through)\b|\bin our tests?\b|\bhands-on testing showed\b', text_of(src), re.I):
        errs.append('claims hands-on testing (not allowed: research-based only)')
    if re.search(r'Amazon\'?s Choice|best seller|limited time|deal of the day', text_of(src), re.I):
        errs.append('Amazon marketing claims are not allowed')

    # readability
    t = text_of(src)
    sents = [s for s in re.split(r'(?<=[.!?])\s+', t) if len(s.split()) > 2]
    avg = sum(len(s.split()) for s in sents) / max(1, len(sents))
    if avg > 17:
        warns.append('average sentence is %.1f words (aim for under 16)' % avg)
    kw = man.get('primary_keyword', '').lower()
    if kw:
        first = text_of(parts[0])[:900].lower()
        if kw not in first:
            warns.append('primary keyword not found verbatim in the intro')
        if kw not in man.get('title', '').lower():
            warns.append('primary keyword not found verbatim in the title')
        if not any(kw in text_of(h).lower() for h in re.findall(r'<h2[^>]*>(.*?)</h2>', src)):
            warns.append('primary keyword not in any H2')
    return errs, warns


if __name__ == '__main__':
    bad = 0
    for slug in sys.argv[1:]:
        e, w = check(slug)
        print('== %s: %s' % (slug, 'OK' if not e else '%d error(s)' % len(e)))
        for x in e:
            print('  ERROR:', x)
        for x in w:
            print('  WARN: ', x)
        bad += bool(e)
    sys.exit(1 if bad else 0)
