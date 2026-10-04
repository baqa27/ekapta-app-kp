#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
ekapta_audit.py - Audit statis proyek Laravel (hanya butuh Python 3.8+, tanpa PHP/composer).

Perintah:
  audit [PATH] [--out laporan.md] [--orphans]
      Periksa route, controller, method, view, middleware, import class, huruf besar/kecil
      nama file (penting untuk hosting Linux), env produksi, dan file sisa.
  routes-diff A B [--repo PATH]
      Bandingkan route antara dua folder proyek atau dua git ref (branch/commit).
      Berguna untuk melihat route yang hilang atau kembali ke versi lama.
  manifest [PATH] > lokal.txt
      Cetak daftar "sha1  path" untuk dibandingkan dengan server.
  compare lokal.txt server.txt [--all]
      Bandingkan dua manifest: file yang belum terupload, file lebih di server, file berbeda.

Kode keluar 1 jika ada temuan ERROR (cocok dipakai sebelum deploy).
"""
import argparse
import difflib
import fnmatch
import hashlib
import os
import re
import subprocess
import sys
import tempfile
from collections import defaultdict
from functools import lru_cache

BUILTIN_MW = {'auth', 'auth.basic', 'auth.session', 'cache.headers', 'can', 'guest',
              'password.confirm', 'signed', 'throttle', 'verified', 'bindings', 'web', 'api'}
BACKUPISH = re.compile(r'(backup|_bak|\.bak|_old|\bold\b|copy|_v\d|_\d+\.php$|\bbs\b)', re.I)
NOT_ROUTE_EXT = {'php', 'com', 'png', 'jpg', 'jpeg', 'gif', 'js', 'css', 'pdf', 'xlsx', 'docx',
                  'env', 'json', 'svg', 'ico', 'txt', 'zip', 'html', 'md', 'sql'}
GLOBAL_ALIASES = {'Auth', 'DB', 'Route', 'Storage', 'Log', 'Validator', 'Hash', 'Mail', 'Cache',
                  'Session', 'Str', 'Arr', 'Gate', 'Schema', 'Http', 'Redirect', 'Response', 'URL',
                  'View', 'File', 'Config', 'Cookie', 'Crypt', 'Event', 'Lang', 'Queue', 'Request',
                  'Artisan', 'Blade', 'Bus', 'Password', 'Notification', 'Redis', 'RateLimiter'}


# ----------------------------------------------------------------------------- util
def read(path):
    with open(path, 'r', encoding='utf-8', errors='replace') as f:
        return f.read()


def line_of(text, pos):
    return text.count('\n', 0, pos) + 1


def scan_php(text):
    """Kembalikan (clean, mask). clean: komentar dikosongkan. mask: isi string juga dikosongkan.
    Panjang dan posisi baris tetap sama dengan teks asli."""
    n = len(text)
    clean = list(text)
    mask = list(text)
    i = 0
    state = None
    while i < n:
        c = text[i]
        nx = text[i + 1] if i + 1 < n else ''
        if state is None:
            if (c == '/' and nx == '/') or (c == '#' and nx != '['):
                j = i
                while j < n and text[j] != '\n':
                    clean[j] = ' '
                    mask[j] = ' '
                    j += 1
                i = j
                continue
            if c == '/' and nx == '*':
                j = text.find('*/', i + 2)
                j = n if j < 0 else j + 2
                for k in range(i, j):
                    if text[k] != '\n':
                        clean[k] = ' '
                        mask[k] = ' '
                i = j
                continue
            if c in '\'"':
                state = c
            i += 1
        else:
            if c == '\\':
                mask[i] = '_'
                if i + 1 < n and text[i + 1] != '\n':
                    mask[i + 1] = '_'
                i += 2
                continue
            if c == state:
                state = None
            elif c != '\n':
                mask[i] = '_'
            i += 1
    return ''.join(clean), ''.join(mask)


def clean_blade(text):
    return re.sub(r'\{\{--.*?--\}\}', lambda m: re.sub(r'[^\n]', ' ', m.group(0)), text, flags=re.S)


@lru_cache(maxsize=None)
def _ls(path):
    try:
        return tuple(os.listdir(path))
    except OSError:
        return ()


def cs_check(root, rel):
    """Cek keberadaan file dengan huruf besar/kecil persis (seperti Linux).
    Hasil: ('ok'|'case'|'missing', path_asli)."""
    cur = root
    actual = []
    diff = False
    for part in rel.split('/'):
        names = _ls(cur)
        if part in names:
            actual.append(part)
            cur = os.path.join(cur, part)
            continue
        alt = [x for x in names if x.lower() == part.lower()]
        if alt:
            actual.append(alt[0])
            cur = os.path.join(cur, alt[0])
            diff = True
            continue
        return 'missing', None
    return ('case' if diff else 'ok'), '/'.join(actual)


STR_RE = re.compile(r'''(['"])((?:\\.|(?!\1).)*)\1''')


def strings(s):
    return [m.group(2) for m in STR_RE.finditer(s)]


def join_uri(*parts):
    p = [x.strip('/') for x in parts if x and x.strip('/')]
    return '/' + '/'.join(p)


class Report:
    def __init__(self):
        self.items = defaultdict(list)

    def add(self, sev, cat, msg, loc=''):
        self.items[(sev, cat, msg)].append(loc)

    def count(self, sev):
        return sum(1 for k in self.items if k[0] == sev)

    def render(self, max_locs=8):
        out = []
        for sev in ('ERROR', 'WARN', 'INFO'):
            keys = sorted([k for k in self.items if k[0] == sev], key=lambda k: (k[1], k[2]))
            if not keys:
                continue
            out.append('\n## %s (%d)\n' % (sev, len(keys)))
            for (_, cat, msg) in keys:
                locs = [x for x in self.items[(sev, cat, msg)] if x]
                out.append('- **[%s]** %s' % (cat, msg))
                for loc in locs[:max_locs]:
                    out.append('    - `%s`' % loc)
                if len(locs) > max_locs:
                    out.append('    - ... dan %d lokasi lain' % (len(locs) - max_locs))
        return '\n'.join(out)


# ----------------------------------------------------------------------------- php meta
USE_RE = re.compile(r'^\s*use\s+([^;]+);', re.M)
NS_RE = re.compile(r'^\s*namespace\s+([\\\w]+)\s*;', re.M)
DECL_RE = re.compile(r'(?<![\w$>:])(?:abstract\s+|final\s+)?(class|interface|trait)\s+(\w+)(?:\s+extends\s+([\\\w]+))?')
FUNC_RE = re.compile(r'(?:(public|protected|private)\s+)?(?:static\s+)?function\s+&?(\w+)\s*\(')


def parse_uses(head):
    aliases = {}

    def add(spec, base=''):
        m = re.match(r'\\?([\w\\]+)(?:\s+as\s+(\w+))?$', spec.strip())
        if not m:
            return
        fq = (base + '\\' + m.group(1)) if base else m.group(1)
        aliases[m.group(2) or fq.split('\\')[-1]] = fq

    for m in USE_RE.finditer(head):
        body = m.group(1).strip()
        if not re.match(r'\\?\w', body) or body.startswith(('function ', 'const ')):
            continue
        g = re.match(r'\\?([\w\\]+)\\\{(.+)\}$', body, re.S)
        if g:
            for part in g.group(2).split(','):
                if part.strip():
                    add(part, g.group(1))
        else:
            for part in body.split(','):
                add(part)
    return aliases


def resolve_name(name, aliases, ns):
    if name.startswith('\\'):
        return name[1:]
    first, _, rest = name.partition('\\')
    if first in aliases:
        return aliases[first] + ('\\' + rest if rest else '')
    return (ns + '\\' if ns else '') + name


def class_rel(fqcn):
    if fqcn.startswith('App\\'):
        return 'app/' + fqcn[4:].replace('\\', '/') + '.php'
    return None


class Project:
    def __init__(self, root):
        self.root = os.path.abspath(root)
        self._cls = {}

    def abs(self, rel):
        return os.path.join(self.root, *rel.split('/'))

    def files(self, dirs, exts=('.php',)):
        out = []
        for d in dirs:
            for dp, dn, fn in os.walk(self.abs(d)):
                dn[:] = [x for x in dn if x not in ('vendor', 'node_modules', '.git')]
                for f in fn:
                    if f.endswith(exts):
                        out.append(os.path.relpath(os.path.join(dp, f), self.root).replace(os.sep, '/'))
        return sorted(out)

    def parse_file(self, rel):
        text = read(self.abs(rel))
        clean, mask = scan_php(text)
        nsm = NS_RE.search(mask)
        ns = nsm.group(1) if nsm else ''
        decls = [(m.group(1), m.group(2), m.group(3), m.start()) for m in DECL_RE.finditer(mask)]
        first = decls[0][3] if decls else len(mask)
        aliases = parse_uses(clean[:first])
        return dict(text=text, clean=clean, mask=mask, ns=ns, decls=decls, aliases=aliases, first=first)

    def get_class(self, fqcn):
        if fqcn in self._cls:
            return self._cls[fqcn]
        info = None
        rel = class_rel(fqcn)
        if rel:
            st, actual = cs_check(self.root, rel)
            if st == 'ok':
                p = self.parse_file(rel)
                if p['decls']:
                    kind, name, parent, start = p['decls'][0]
                    body = p['mask'][start:]
                    traits = []
                    for m in re.finditer(r'^\s*use\s+([\\\w,\s]+);', body, re.M):
                        for t in m.group(1).split(','):
                            if t.strip():
                                traits.append(resolve_name(t.strip(), p['aliases'], p['ns']))
                    methods = {}
                    for m in FUNC_RE.finditer(body):
                        methods[m.group(2).lower()] = m.group(1) or 'public'
                    info = dict(kind=kind, name=name, ns=p['ns'],
                                parent=resolve_name(parent, p['aliases'], p['ns']) if parent else None,
                                traits=traits, methods=methods)
        self._cls[fqcn] = info
        return info

    def methods_of(self, fqcn, depth=0):
        info = self.get_class(fqcn)
        if info is None or depth > 6:
            return None
        ms = dict(info['methods'])
        for t in info['traits']:
            tm = self.methods_of(t, depth + 1)
            if tm:
                for k, v in tm.items():
                    ms.setdefault(k, v)
        if info['parent']:
            pm = self.methods_of(info['parent'], depth + 1)
            if pm:
                for k, v in pm.items():
                    ms.setdefault(k, v)
            else:
                ms['__vendor_parent__'] = 'public'
        return ms


# ----------------------------------------------------------------------------- routes
VERBS = 'get|post|put|patch|delete|options|any|match|resource|apiResource|view|redirect'
VERB_RE = re.compile(r'(?:Route::|->)\s*(' + VERBS + r')\s*\(')
ROUTE_START = re.compile(r'Route::\w+')


def split_args(clean, mask, open_idx):
    depth = 0
    args = []
    start = open_idx + 1
    n = len(mask)
    for j in range(open_idx, n):
        c = mask[j]
        if c in '([{':
            depth += 1
        elif c in ')]}':
            depth -= 1
            if depth == 0:
                args.append(clean[start:j].strip())
                return args, j
        elif c == ',' and depth == 1:
            args.append(clean[start:j].strip())
            start = j + 1
    args.append(clean[start:].strip())
    return args, n


def find_stmt(mask, i, end):
    depth = 0
    j = i
    while j < end:
        c = mask[j]
        if c in '([':
            depth += 1
        elif c in ')]':
            depth -= 1
        elif c == ';' and depth <= 0:
            return ('stmt', j)
        elif (c == 'g' and depth == 0 and mask[max(0, j - 2):j] in ('->', '::')
              and re.match(r'group\s*\(', mask[j:j + 12])):
            p = mask.index('(', j)
            b = mask.find('{', p, end)
            if b >= 0 and re.search(r'\b(function|fn)\b', mask[p:b]):
                d = 0
                k = b
                while k < end:
                    if mask[k] == '{':
                        d += 1
                    elif mask[k] == '}':
                        d -= 1
                        if d == 0:
                            break
                    k += 1
                return ('group', j, b, k)
        j += 1
    return ('stmt', end - 1)


def group_ctx(header, parent):
    ctx = dict(parent)
    names = re.findall(r"""(?:->|::)name\(\s*['"]([^'"]*)['"]""", header)
    names += re.findall(r"""['"]as['"]\s*=>\s*['"]([^'"]*)['"]""", header)
    ctx['name'] = parent['name'] + ''.join(names)
    pref = re.findall(r"""(?:->|::)prefix\(\s*['"]([^'"]*)['"]""", header)
    pref += re.findall(r"""['"]prefix['"]\s*=>\s*['"]([^'"]*)['"]""", header)
    ctx['prefix'] = join_uri(parent['prefix'], *pref)
    mws = list(parent['mw'])
    for m in re.finditer(r"""(?:->|::)middleware\(\s*(\[[^\]]*\]|['"][^'"]*['"])""", header):
        mws += strings(m.group(1))
    for m in re.finditer(r"""['"]middleware['"]\s*=>\s*(\[[^\]]*\]|['"][^'"]*['"])""", header):
        mws += strings(m.group(1))
    ctx['mw'] = mws
    cm = re.search(r"""(?:->|::)controller\(\s*([\\\w]+)::class""", header)
    if cm:
        ctx['controller'] = cm.group(1)
    nm = re.findall(r"""(?:->|::)namespace\(\s*['"]([^'"]*)['"]""", header)
    nm += re.findall(r"""['"]namespace['"]\s*=>\s*['"]([^'"]*)['"]""", header)
    if nm:
        ctx['ns'] = (parent['ns'] + '\\' if parent['ns'] else '') + '\\'.join(nm)
    return ctx


def parse_handler(h, ctx):
    if not h:
        return None
    if re.match(r'(static\s+)?(function|fn)\b', h):
        return ('closure', None, False)
    m = re.match(r"""\[\s*([\\\w]+)::class\s*,\s*['"](\w+)['"]\s*\]""", h, re.S)
    if m:
        return (m.group(1), m.group(2), False)
    m = re.match(r"""['"]([\\\w]+)@(\w+)['"]$""", h)
    if m:
        return (m.group(1), m.group(2), True)
    m = re.match(r"""([\\\w]+)::class$""", h)
    if m:
        return (m.group(1), '__invoke', False)
    m = re.match(r"""['"](\w+)['"]$""", h)
    if m and ctx.get('controller'):
        return (ctx['controller'], m.group(1), False)
    return None


def resolve_controller(raw, aliases, ns_ctx, is_string=False):
    raw = raw.strip()
    if raw.startswith('\\'):
        return raw[1:]
    first, _, rest = raw.partition('\\')
    if first in aliases:
        return aliases[first] + ('\\' + rest if rest else '')
    if not is_string:
        return raw  # sintaks X::class di file tanpa namespace = nama global
    base = 'App\\Http\\Controllers\\'
    if ns_ctx:
        base += ns_ctx.strip('\\') + '\\'
    return base + raw


def parse_routes_text(text, relfile):
    clean, mask = scan_php(text)
    first_decl = DECL_RE.search(mask)
    aliases = parse_uses(clean[:first_decl.start()] if first_decl else clean)
    routes = []

    def stmt(i, e, ctx):
        sc = clean[i:e + 1]
        sm = mask[i:e + 1]
        vm = VERB_RE.search(sm)
        if not vm:
            return
        verb = vm.group(1)
        args, _ = split_args(sc, sm, vm.end() - 1)
        line = line_of(clean, i)
        names = re.findall(r"""->(?:name|as)\(\s*['"]([^'"]*)['"]""", sc[vm.end():])
        mw = list(ctx['mw'])
        for m in re.finditer(r"""(?:->|::)middleware\(\s*(\[[^\]]*\]|['"][^'"]*['"])""", sc):
            mw += strings(m.group(1))
        rec = dict(file=relfile, line=line, mw=mw, name=None, cls=None, action=None,
                   kind='controller', view=None, ns=ctx['ns'], aliases=aliases, strcls=False)
        if verb in ('resource', 'apiResource'):
            res = strings(args[0])[0] if args and strings(args[0]) else None
            h = parse_handler(args[1], ctx) if len(args) > 1 else None
            if not res or not h:
                return
            acts = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']
            if verb == 'apiResource':
                acts = [a for a in acts if a not in ('create', 'edit')]
            om = re.search(r"->only\(\s*\[([^\]]*)\]", sc)
            xm = re.search(r"->except\(\s*\[([^\]]*)\]", sc)
            if om:
                acts = [a for a in acts if a in strings(om.group(1))]
            if xm:
                acts = [a for a in acts if a not in strings(xm.group(1))]
            for a in acts:
                r = dict(rec)
                r.update(verbs=['RESOURCE'], uri=join_uri(ctx['prefix'], res),
                         name=ctx['name'] + res + '.' + a, cls=h[0], action=a, strcls=h[2])
                routes.append(r)
            return
        if verb == 'match':
            verbs = [v.upper() for v in strings(args[0])] if args else []
            uri_i, h_i = 1, 2
        else:
            verbs = ['ANY'] if verb == 'any' else [verb.upper()]
            uri_i, h_i = 0, 1
        us = strings(args[uri_i]) if len(args) > uri_i else []
        uri = join_uri(ctx['prefix'], us[0] if us else '')
        rec.update(verbs=verbs, uri=uri, name=(ctx['name'] + names[-1]) if names else None)
        if verb == 'view':
            vs = strings(args[h_i]) if len(args) > h_i else []
            rec.update(kind='view', view=vs[0] if vs else None)
        elif verb == 'redirect':
            rec.update(kind='redirect')
        else:
            h = parse_handler(args[h_i], ctx) if len(args) > h_i else None
            if h is None:
                rec.update(kind='unknown')
            elif h[0] == 'closure':
                rec.update(kind='closure')
            else:
                rec.update(cls=h[0], action=h[1], strcls=h[2])
        routes.append(rec)

    def block(start, end, ctx):
        pos = start
        while True:
            m = ROUTE_START.search(mask, pos, end)
            if not m:
                break
            i = m.start()
            st = find_stmt(mask, i, end)
            if st[0] == 'group':
                _, g, b, k = st
                block(b + 1, k, group_ctx(clean[i:b], ctx))
                pos = k + 1
            else:
                stmt(i, st[1], ctx)
                pos = st[1] + 1

    block(0, len(mask), dict(name='', prefix='/', mw=[], controller=None, ns=''))
    return routes


def load_routes(root):
    out = []
    rdir = os.path.join(root, 'routes')
    if not os.path.isdir(rdir):
        return out
    for f in sorted(os.listdir(rdir)):
        if f.endswith('.php') and f not in ('console.php', 'channels.php'):
            out += parse_routes_text(read(os.path.join(rdir, f)), 'routes/' + f)
    return out


def route_key(r):
    return r['name'] or ('%s %s' % ('|'.join(r['verbs']), r['uri']))


# ----------------------------------------------------------------------------- audit
def similar(name, pool, n=3):
    return difflib.get_close_matches(name, pool, n=n, cutoff=0.75)


def audit(root, orphans=False):
    P = Project(root)
    R = Report()
    routes = load_routes(P.root)
    defined = defaultdict(list)
    for r in routes:
        if r['name']:
            defined[r['name']].append(r)
    all_names = list(defined)

    # --- 1. nama route ganda & URI ganda
    for name, lst in defined.items():
        if len(lst) > 1:
            actions = {(x['cls'], x['action'], x['kind']) for x in lst}
            sev = 'ERROR' if len(actions) > 1 else 'WARN'
            for x in lst:
                R.add(sev, 'route-ganda', 'Nama route `%s` didefinisikan %d kali%s' %
                      (name, len(lst), ' dengan tujuan berbeda' if len(actions) > 1 else ''),
                      '%s:%d  %s' % (x['file'], x['line'], x['uri']))
    seen = {}
    for r in routes:
        for v in r['verbs']:
            k = (v, r['uri'])
            if k in seen and v != 'RESOURCE':
                R.add('WARN', 'uri-ganda', 'URI sama `%s %s` terdaftar lebih dari sekali (yang pertama menang)' % k,
                      '%s:%d  (pertama di baris %d)' % (r['file'], r['line'], seen[k]))
            else:
                seen[k] = r['line']
    nclosure = sum(1 for r in routes if r['kind'] == 'closure')
    if nclosure:
        R.add('INFO', 'closure', 'Ada %d route berbentuk Closure; `php artisan route:cache` tidak bisa dipakai' % nclosure)

    # --- 2. controller & method
    used_actions = defaultdict(set)
    for r in routes:
        if r['kind'] == 'view' and r['view']:
            continue
        if not r['cls']:
            continue
        fq = resolve_controller(r['cls'], r['aliases'], r['ns'], r.get('strcls', False))
        used_actions[fq].add(r['action'])
        loc = '%s:%d  %s %s' % (r['file'], r['line'], '|'.join(r['verbs']), r['uri'])
        rel = class_rel(fq)
        if not rel:
            continue
        st, actual = cs_check(P.root, rel)
        if st == 'missing':
            base = os.path.basename(rel)
            near = [p for p in P.files(['app']) if os.path.basename(p) == base]
            hint = (' Ada file bernama sama di: %s' % ', '.join(near)) if near else ''
            R.add('ERROR', 'controller', 'Target class [%s] tidak ada (file %s tidak ditemukan).%s' % (fq, rel, hint), loc)
            continue
        if st == 'case':
            R.add('ERROR', 'huruf-besar-kecil', 'Class [%s] dicari di `%s` tetapi file aslinya `%s`. '
                  'Jalan di Windows, gagal di hosting Linux.' % (fq, rel, actual), loc)
            continue
        info = P.get_class(fq)
        if info and ((info['ns'] + '\\' + info['name']) != fq):
            R.add('ERROR', 'controller', 'File `%s` mendeklarasikan `%s\\%s`, bukan `%s`' %
                  (rel, info['ns'], info['name'], fq), loc)
            continue
        ms = P.methods_of(fq)
        act = r['action']
        al = act.lower()
        if ms is not None and al not in ms:
            R.add('ERROR', 'method', 'Route menunjuk ke method yang tidak ada: `%s::%s()` (BadMethodCallException)' % (fq, act), loc)
        elif ms is not None and ms[al] != 'public':
            R.add('ERROR', 'method', 'Method `%s::%s()` bukan public' % (fq, act), loc)

    # --- 3. view dari route::view
    def view_exists(name):
        rel = 'resources/views/' + name.replace('.', '/')
        s1, a1 = cs_check(P.root, rel + '.blade.php')
        if s1 != 'missing':
            return s1, a1
        return cs_check(P.root, rel + '.php')

    for r in routes:
        if r['kind'] == 'view' and r['view'] and '::' not in r['view']:
            st, actual = view_exists(r['view'])
            if st == 'missing':
                R.add('ERROR', 'view', 'View `%s` (Route::view) tidak ada' % r['view'], '%s:%d' % (r['file'], r['line']))

    # --- 4. middleware
    aliases_mw = {}
    kernel_rel = 'app/Http/Kernel.php'
    groups = set()
    if os.path.exists(P.abs(kernel_rel)):
        kp = P.parse_file(kernel_rel)
        kt = kp['clean']
        for prop in ('routeMiddleware', 'middlewareAliases'):
            m = re.search(r'\$' + prop + r'\s*=\s*\[(.*?)\];', kt, re.S)
            if m:
                for am in re.finditer(r"""['"]([^'"]+)['"]\s*=>\s*([\\\w]+)::class""", m.group(1)):
                    aliases_mw[am.group(1)] = resolve_name(am.group(2), kp['aliases'], kp['ns'])
        m = re.search(r'\$middlewareGroups\s*=\s*\[(.*)', kt, re.S)
        if m:
            groups = set(re.findall(r"""^\s*['"](\w+)['"]\s*=>\s*\[""", m.group(1), re.M))
        for al, fq in aliases_mw.items():
            rel = class_rel(fq)
            if not rel:
                continue
            st, actual = cs_check(P.root, rel)
            if st == 'missing':
                R.add('ERROR', 'middleware', 'Alias `%s` menunjuk ke [%s] tetapi file `%s` tidak ada' % (al, fq, rel), kernel_rel)
            elif st == 'case':
                R.add('ERROR', 'huruf-besar-kecil', 'Alias middleware `%s` -> [%s] dicari di `%s`, file aslinya `%s`. '
                      'Gagal di Linux.' % (al, fq, rel, actual), kernel_rel)
    known_mw = set(aliases_mw) | groups | BUILTIN_MW
    mw_uses = []
    for r in routes:
        for m in r['mw']:
            mw_uses.append((m, '%s:%d' % (r['file'], r['line'])))
    ctrl_files = P.files(['app/Http/Controllers'])
    for rel in ctrl_files:
        p = P.parse_file(rel)
        for m in re.finditer(r"""->middleware\(\s*(\[[^\]]*\]|['"][^'"]*['"])""", p['clean']):
            for s in strings(m.group(1)):
                mw_uses.append((s, '%s:%d' % (rel, line_of(p['clean'], m.start()))))
    for m, loc in mw_uses:
        alias = m.split(':')[0]
        if '\\' in alias:
            continue
        if alias not in known_mw:
            R.add('ERROR', 'middleware', 'Middleware `%s` tidak terdaftar di Kernel (Laravel: Target class [%s] does not exist)' % (alias, alias), loc)

    # --- 5. pemakaian nama route
    scan_files = P.files(['app', 'resources', 'routes'])
    call_re = re.compile(r"""(?<![\w$])(?:route|to_route)\s*\(\s*(['"])([^'"$\n{}]+)\1""")
    has_re = re.compile(r"""Route::has\(\s*(['"])([^'"$\n]+)\1""")
    is_re = re.compile(r"""routeIs\s*\(([^)]*)\)""")
    cur_re = re.compile(r"""(?:currentRouteName\(\)\s*[!=]==?\s*|currentRouteNamed\(\s*)(['"])([^'"]+)\1""")
    cand_re = re.compile(r"""^[a-z][a-z0-9_-]*(\.[a-z0-9_-]+)+$""")
    first_segs = {n.split('.')[0] for n in all_names}
    view_names_cache = {}
    checked = set()

    def check_route_name(name, loc, strict, orphan_view=False):
        if name in defined:
            return
        sug = similar(name, all_names)
        alt = []
        if name.startswith('kp.') and name[3:] in defined:
            alt.append(name[3:])
        if ('kp.' + name) in defined:
            alt.append('kp.' + name)
        hint = ''
        pool = []
        for a in alt + sug:
            if a not in pool:
                pool.append(a)
        if pool:
            hint = ' Mirip/terdekat: ' + ', '.join('`%s`' % x for x in pool[:3])
        sev = 'ERROR' if (strict or '.' in name) else 'INFO'
        msg = 'Route [%s] tidak terdefinisi (RouteNotFoundException).%s' % (name, hint)
        if sev == 'INFO' and not orphan_view:
            msg = 'Pemanggilan ->route(\'%s\') tanpa titik, mungkin parameter route, bukan nama route' % name
        if orphan_view:
            sev = 'INFO'
            msg = 'Route [%s] tidak terdefinisi, tetapi view ini tidak pernah dipanggil dari mana pun (kemungkinan view bawaan/sisa)' % name
        R.add(sev, 'route-hilang', msg, loc)

    ref_views = set()
    _vr = re.compile(r"""(?:(?<![\w$])(?:view|View::make)\s*\(|@(?:include|includeIf|extends|component|each|includeFirst)\s*\()\s*(['"])([^'"$\n{}]+)\1""")
    for rel in scan_files:
        raw = read(P.abs(rel))
        t = clean_blade(raw) if rel.endswith('.blade.php') else scan_php(raw)[0]
        for m in _vr.finditer(t):
            ref_views.add(m.group(2))
    for r in routes:
        if r['kind'] == 'view' and r['view']:
            ref_views.add(r['view'])

    def view_name_of(rel):
        pre = 'resources/views/'
        if rel.startswith(pre) and rel.endswith('.blade.php'):
            return rel[len(pre):-len('.blade.php')].replace('/', '.')
        return None

    for rel in scan_files:
        raw = read(P.abs(rel))
        is_blade = rel.endswith('.blade.php')
        vname = view_name_of(rel)
        orphan_view = bool(vname) and vname not in ref_views
        text = clean_blade(raw) if is_blade else scan_php(raw)[0]
        for m in call_re.finditer(text):
            before = text[max(0, m.start() - 12):m.start()]
            strict = not before.rstrip().endswith('->') or before.rstrip().endswith('redirect()->')
            loc = '%s:%d' % (rel, line_of(text, m.start()))
            checked.add((rel, line_of(text, m.start()), m.group(2)))
            check_route_name(m.group(2), loc, strict, orphan_view)
        for m in cur_re.finditer(text):
            loc = '%s:%d' % (rel, line_of(text, m.start()))
            checked.add((rel, line_of(text, m.start()), m.group(2)))
            check_route_name(m.group(2), loc, True, orphan_view)
        for m in is_re.finditer(text):
            for s in strings(m.group(1)):
                checked.add((rel, line_of(text, m.start()), s))
                if not any(fnmatch.fnmatch(n, s) for n in all_names):
                    R.add('WARN', 'routeIs', 'routeIs(\'%s\') tidak cocok dengan nama route mana pun (menu aktif tidak akan menyala)' % s,
                          '%s:%d' % (rel, line_of(text, m.start())))
        # kandidat nama route yang disimpan di variabel/array (mis. 'createRoute' => '...')
        if not rel.startswith('routes/'):
            for ln_no, ln in enumerate(text.split('\n'), 1):
                if not re.search(r'route', ln, re.I):
                    continue
                for s in strings(ln):
                    if not cand_re.match(s) or s.split('.')[-1] in NOT_ROUTE_EXT:
                        continue
                    if s.split('.')[0] not in first_segs or (rel, ln_no, s) in checked:
                        continue
                    if s in defined:
                        continue
                    if s not in view_names_cache:
                        view_names_cache[s] = view_exists(s)[0] != 'missing'
                    if view_names_cache[s]:
                        continue
                    sug = similar(s, all_names)
                    R.add('WARN', 'route-kandidat',
                          'String `%s` terlihat seperti nama route (disimpan di variabel/array) tetapi tidak terdefinisi.%s' %
                          (s, (' Mirip: ' + ', '.join('`%s`' % x for x in sug)) if sug else ''),
                          '%s:%d' % (rel, ln_no))

    # --- 6. view()/@include/@extends
    view_re = re.compile(r"""(?<![\w$])(?:view|View::make)\s*\(\s*(['"])([^'"$\n{}]+)\1""")
    dir_re = re.compile(r"""@(?:include|extends|component|each|includeFirst)\s*\(\s*(['"])([^'"$\n{}]+)\1""")
    for rel in scan_files:
        if rel.startswith('routes/'):
            continue
        raw = read(P.abs(rel))
        text = clean_blade(raw) if rel.endswith('.blade.php') else scan_php(raw)[0]
        for rx in (view_re, dir_re):
            for m in rx.finditer(text):
                name = m.group(2)
                if '::' in name or name.startswith(('http', '/')):
                    continue
                st, actual = view_exists(name)
                loc = '%s:%d' % (rel, line_of(text, m.start()))
                if st == 'missing':
                    R.add('ERROR', 'view', 'View [%s] tidak ditemukan di resources/views' % name, loc)
                elif st == 'case':
                    R.add('ERROR', 'huruf-besar-kecil', 'View [%s] ada sebagai `%s` (huruf beda). Gagal di Linux.' % (name, actual), loc)

    # --- 7. PSR-4, duplikasi class, import, kelas tanpa import
    declared = defaultdict(list)
    for rel in P.files(['app']):
        p = P.parse_file(rel)
        if not p['decls']:
            continue
        kind, name, parent, start = p['decls'][0]
        declfq = (p['ns'] + '\\' if p['ns'] else '') + name
        expected = 'App\\' + rel[4:-4].replace('/', '\\')
        declared[declfq].append(rel)
        if declfq != expected:
            sev = 'WARN' if BACKUPISH.search(rel) else 'ERROR'
            R.add(sev, 'psr4', 'File mendeklarasikan `%s` tetapi path mengharapkan `%s`%s' %
                  (declfq, expected, ' (tampak seperti file backup)' if sev == 'WARN' else ''), rel)
    for fq, lst in declared.items():
        if len(lst) > 1:
            R.add('WARN', 'class-ganda', 'Class `%s` dideklarasikan di %d file (rawan "Ambiguous class resolution")' % (fq, len(lst)),
                  ', '.join(lst))
    code_files = P.files(['app', 'routes', 'database', 'config'])
    for rel in code_files:
        p = P.parse_file(rel)
        clean, mask, ns = p['clean'], p['mask'], p['ns']
        aliases = p['aliases']
        # import yang rusak
        for alias, fq in aliases.items():
            crel = class_rel(fq)
            if not crel:
                continue
            st, actual = cs_check(P.root, crel)
            if st == 'ok':
                continue
            used = len(re.findall(r'(?<![\w\\$])' + re.escape(alias) + r'\b', mask)) > 1
            ln = 0
            for m in USE_RE.finditer(clean):
                if fq.split('\\')[-1] in m.group(1) or alias in m.group(1):
                    ln = line_of(clean, m.start())
                    break
            if st == 'missing':
                R.add('ERROR' if used else 'WARN', 'import',
                      'use %s; menunjuk class yang tidak ada (`%s`)%s' % (fq, crel, '' if used else ' (tidak dipakai di file ini)'),
                      '%s:%d' % (rel, ln))
            else:
                R.add('ERROR' if used else 'WARN', 'huruf-besar-kecil',
                      'use %s; huruf beda dengan file asli `%s`. Gagal di Linux.' % (fq, actual), '%s:%d' % (rel, ln))
        # class dipakai tanpa import (hanya file ber-namespace)
        if not ns or rel.startswith(('database/', 'config/')):
            continue
        declared_here = {d[1] for d in p['decls']}
        seen_tok = set()
        for m in re.finditer(r'(?<![\w\\$>:])((?:new\s+)?)([A-Z][A-Za-z0-9_]*)\s*(::|\()', mask):
            if m.group(3) == '(' and not m.group(1):
                continue
            tok = m.group(2)
            if tok in seen_tok or tok in aliases or tok in declared_here or tok in ('self', 'static', 'parent'):
                continue
            seen_tok.add(tok)
            crel = class_rel(ns + '\\' + tok)
            if crel and cs_check(P.root, crel)[0] == 'ok':
                continue
            R.add('ERROR', 'tanpa-import', 'Class `%s` dipakai di namespace `%s` tanpa `use` (Class "%s\\%s" not found)' %
                  (tok, ns, ns, tok), '%s:%d' % (rel, line_of(mask, m.start())))

    # --- 8. controller tanpa route / method tanpa route
    if ctrl_files:
        routed = set(used_actions)
        un_ctrl = []
        un_methods = 0
        for rel in ctrl_files:
            fq = 'App\\' + rel[4:-4].replace('/', '\\')
            if fq.endswith('\\Controller'):
                continue
            info = P.get_class(fq)
            if not info or info['kind'] != 'class':
                continue
            if fq not in routed:
                un_ctrl.append(rel)
                continue
            for mname, vis in info['methods'].items():
                if vis == 'public' and not mname.startswith('__') and mname not in used_actions[fq]:
                    un_methods += 1
                    if orphans:
                        R.add('INFO', 'method-tanpa-route', 'Method publik `%s::%s()` tidak dipanggil dari route mana pun' % (fq, mname), rel)
        for rel in un_ctrl:
            R.add('INFO', 'controller-tanpa-route', 'Controller tidak dipakai oleh route mana pun (belum terhubung atau sudah usang)', rel)
        if un_methods and not orphans:
            R.add('INFO', 'method-tanpa-route', '%d method publik di controller yang dipakai tidak punya route (jalankan lagi dengan --orphans untuk daftar)' % un_methods)

    # --- 9. env & cache
    for envf in ('.env', '.env.example'):
        if os.path.exists(P.abs(envf)):
            kv = {}
            for ln in read(P.abs(envf)).splitlines():
                m = re.match(r'\s*([A-Z_]+)\s*=\s*(.*?)\s*$', ln)
                if m:
                    kv[m.group(1)] = m.group(2).strip('"\'')
            dbg = kv.get('APP_DEBUG', '').lower() in ('true', '1')
            env = kv.get('APP_ENV', '')
            if dbg and env not in ('local', 'testing'):
                R.add('ERROR', 'env', 'APP_DEBUG=true dengan APP_ENV=%s (peringatan Ignition: rawan eksekusi kode jarak jauh)' % env, envf)
            elif dbg:
                R.add('WARN' if envf == '.env.example' else 'INFO', 'env', 'APP_DEBUG=true (APP_ENV=%s); di server produksi harus APP_DEBUG=false dan APP_ENV=production' % env, envf)
    cdir = P.abs('bootstrap/cache')
    for f in _ls(cdir):
        if f.endswith('.php'):
            R.add('WARN', 'cache', 'Cache `%s` ada. Route/config yang diubah tidak akan terbaca sampai `php artisan optimize:clear`' % f, 'bootstrap/cache/' + f)

    # --- 10. file sisa
    stray = []
    for dp, dn, fn in os.walk(P.root):
        dn[:] = [x for x in dn if x not in ('vendor', 'node_modules', '.git', 'storage')]
        for f in fn + dn:
            rel = os.path.relpath(os.path.join(dp, f), P.root).replace(os.sep, '/')
            top = rel.split('/')[0]
            if top not in ('app', 'routes', 'resources', 'config', 'database', 'public', 'bootstrap') and '/' in rel:
                continue
            if re.search(r'(\.(old|bak|backup|orig)$|_backup|\bbackup\b| old\b| new\b| v\d| bs\b|\(\d+\))', f, re.I) or (' ' in f and top in ('app', 'routes', 'config')):
                stray.append(rel)
    if stray:
        R.add('WARN', 'file-sisa', '%d file/folder backup atau bernama janggal di dalam kode aplikasi (tidak dipakai, menyulitkan audit)' % len(stray),
              ', '.join(stray[:6]))
    for f in _ls(P.abs('public')):
        if f.endswith('.php') and f != 'index.php':
            R.add('ERROR', 'public-php', 'File PHP di folder public selain index.php dapat dijalankan dari browser', 'public/' + f)
    for f in _ls(P.root):
        if f.lower().endswith(('.sql', '.zip', '.rar', '.sql.gz')):
            R.add('WARN', 'dump', 'File dump/arsip di root proyek; pastikan tidak ikut terupload ke hosting', f)

    summary = dict(routes=len(routes), named=len(defined), closures=nclosure, middleware_alias=len(aliases_mw))
    return R, summary


# ----------------------------------------------------------------------------- routes-diff
def materialize(spec, repo):
    if os.path.isdir(spec) and os.path.isdir(os.path.join(spec, 'routes')):
        return os.path.abspath(spec)
    tmp = tempfile.mkdtemp(prefix='routesdiff_')
    arc = subprocess.run(['git', '-C', repo, 'archive', spec, 'routes'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    if arc.returncode != 0:
        sys.exit('git archive gagal untuk "%s": %s' % (spec, arc.stderr.decode(errors='replace')))
    subprocess.run(['tar', '-x', '-C', tmp], input=arc.stdout, check=True)
    return tmp


def cmd_routes_diff(a, b, repo):
    ra = load_routes(materialize(a, repo))
    rb = load_routes(materialize(b, repo))
    ma = {route_key(r): r for r in ra}
    mb = {route_key(r): r for r in rb}
    only_a = sorted(set(ma) - set(mb))
    only_b = sorted(set(mb) - set(ma))
    changed = []
    for k in sorted(set(ma) & set(mb)):
        x, y = ma[k], mb[k]
        if (x['uri'], x['verbs'], x['cls'], x['action']) != (y['uri'], y['verbs'], y['cls'], y['action']):
            changed.append((k, x, y))
    print('# Perbandingan route: %s  ->  %s' % (a, b))
    print('\nRoute di A: %d, di B: %d\n' % (len(ra), len(rb)))
    print('## Hilang di B (ada di A): %d' % len(only_a))
    for k in only_a:
        r = ma[k]
        print('- `%s`  %s %s  -> %s@%s' % (k, '|'.join(r['verbs']), r['uri'], r['cls'], r['action']))
    print('\n## Baru di B (tidak ada di A): %d' % len(only_b))
    for k in only_b:
        r = mb[k]
        print('- `%s`  %s %s  -> %s@%s' % (k, '|'.join(r['verbs']), r['uri'], r['cls'], r['action']))
    print('\n## Berubah (URI/verb/tujuan): %d' % len(changed))
    for k, x, y in changed:
        print('- `%s`\n    - A: %s %s -> %s@%s\n    - B: %s %s -> %s@%s' % (
            k, '|'.join(x['verbs']), x['uri'], x['cls'], x['action'],
            '|'.join(y['verbs']), y['uri'], y['cls'], y['action']))
    return 0


# ----------------------------------------------------------------------------- manifest
MAN_DIRS = ['app', 'routes', 'resources', 'config', 'database', 'public', 'bootstrap']
MAN_FILES = ['composer.json', 'composer.lock', 'artisan', '.env.example']
MAN_SKIP = ('bootstrap/cache/', 'public/lampirans/', 'public/storage/', 'public/lampiran/')


def cmd_manifest(root):
    root = os.path.abspath(root)
    rows = []
    paths = []
    for d in MAN_DIRS:
        for dp, dn, fn in os.walk(os.path.join(root, d)):
            dn[:] = [x for x in dn if x not in ('node_modules', '.git', 'vendor')]
            for f in fn:
                paths.append(os.path.join(dp, f))
    paths += [os.path.join(root, f) for f in MAN_FILES if os.path.exists(os.path.join(root, f))]
    for p in paths:
        rel = os.path.relpath(p, root).replace(os.sep, '/')
        if rel.startswith(MAN_SKIP):
            continue
        with open(p, 'rb') as fh:
            data = fh.read().replace(b'\r', b'')
        rows.append((hashlib.sha1(data).hexdigest(), rel))
    for h, rel in sorted(rows, key=lambda x: x[1]):
        print('%s  %s' % (h, rel))
    return 0


def load_manifest(path):
    d = {}
    for ln in read(path).splitlines():
        m = re.match(r'^([0-9a-f]{40})\s+\*?(.+)$', ln.strip())
        if m:
            d[re.sub(r'^\./', '', m.group(2))] = m.group(1)
    return d


def cmd_compare(local, server, show_all):
    a, b = load_manifest(local), load_manifest(server)
    only_a = sorted(set(a) - set(b))
    only_b = sorted(set(b) - set(a))
    diff = sorted(k for k in set(a) & set(b) if a[k] != b[k])
    lim = None if show_all else 60

    def section(title, items):
        print('\n## %s: %d' % (title, len(items)))
        for k in items[:lim]:
            print('- `%s`' % k)
        if lim and len(items) > lim:
            print('- ... dan %d lainnya (pakai --all)' % (len(items) - lim))

    print('# Perbandingan manifest lokal vs server')
    print('\nLokal: %d file, Server: %d file, Sama persis: %d' % (len(a), len(b), len(a) - len(only_a) - len(diff)))
    section('Ada di LOKAL, tidak ada di SERVER (belum terupload / hilang)', only_a)
    section('Ada di SERVER, tidak ada di LOKAL (file lama/sisa)', only_b)
    section('Ada di keduanya tetapi ISI BERBEDA', diff)
    return 1 if (only_a or diff) else 0


# ----------------------------------------------------------------------------- main
def main():
    ap = argparse.ArgumentParser(description='Audit statis proyek Laravel (EKAPTA).')
    sp = ap.add_subparsers(dest='cmd', required=True)
    a = sp.add_parser('audit')
    a.add_argument('path', nargs='?', default='.')
    a.add_argument('--out')
    a.add_argument('--orphans', action='store_true')
    d = sp.add_parser('routes-diff')
    d.add_argument('a')
    d.add_argument('b')
    d.add_argument('--repo', default='.')
    m = sp.add_parser('manifest')
    m.add_argument('path', nargs='?', default='.')
    c = sp.add_parser('compare')
    c.add_argument('local')
    c.add_argument('server')
    c.add_argument('--all', action='store_true')
    args = ap.parse_args()

    if args.cmd == 'audit':
        if not os.path.isdir(os.path.join(args.path, 'routes')):
            sys.exit('Folder "%s" bukan root proyek Laravel (tidak ada routes/).' % args.path)
        R, s = audit(args.path, args.orphans)
        head = ['# Laporan audit proyek Laravel', '',
                'Folder: `%s`' % os.path.abspath(args.path), '',
                'Route terbaca: %(routes)d (bernama: %(named)d, closure: %(closures)d), alias middleware: %(middleware_alias)d' % s, '',
                'Temuan: **%d ERROR**, %d WARN, %d INFO' % (R.count('ERROR'), R.count('WARN'), R.count('INFO'))]
        text = '\n'.join(head) + '\n' + R.render() + '\n'
        print(text)
        if args.out:
            with open(args.out, 'w', encoding='utf-8') as f:
                f.write(text)
        return 1 if R.count('ERROR') else 0
    if args.cmd == 'routes-diff':
        return cmd_routes_diff(args.a, args.b, args.repo)
    if args.cmd == 'manifest':
        return cmd_manifest(args.path)
    if args.cmd == 'compare':
        return cmd_compare(args.local, args.server, args.all)


if __name__ == '__main__':
    try:
        sys.exit(main())
    except BrokenPipeError:
        sys.exit(0)
