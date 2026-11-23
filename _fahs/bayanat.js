/**
 * فحص سلامة ملفات البيانات في data/ وتطابقها مع ملفات الموقع.
 *
 * يكشف ما لا يكشفه فتح الصفحات: فاصلة ناقصة في JSON، مقال بلا ملف،
 * جواب صحيح خارج مجال الخيارات، سؤال مكرر، اختبار بلا مجلد،
 * مصطلح يحيل على مصطلح غير موجود، وعدد مكتوب يدويًا تقادم عن مصدره.
 *
 * لا يحتاج خادمًا يعمل.
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..');

const TINTS = ['emerald', 'teal', 'lime', 'sky', 'amber', 'indigo', 'rose', 'orange'];
const GROUPS = ['concours', 'maktaba', 'main'];
/** الوسوم المسموحة داخل نصوص المحتوى — لا أصناف Tailwind ولا وسوم تخطيط */
const OK_TAGS = ['b', 'i', 'em', 'strong', 'br', 'code', 'p', 'a', 'ul', 'ol', 'li'];

const errs = [];
const warns = [];
const err = (m) => errs.push(m);
const warn = (m) => warns.push(m);

/** يقرأ ملف JSON ويبلّغ عن خطأ صياغة بدل أن ينهار */
function readJson(name) {
  const p = path.join(ROOT, 'data', name + '.json');
  if (!fs.existsSync(p)) {
    err(`data/${name}.json غير موجود`);

    return null;
  }
  try {
    return JSON.parse(fs.readFileSync(p, 'utf8'));
  } catch (e) {
    err(`data/${name}.json صياغته معطوبة: ${e.message}`);

    return null;
  }
}

/** يتحقق أن نصًّا لا يحمل أصناف Tailwind ولا وسومًا خارج المسموح */
function checkHtml(text, where) {
  if (/class\s*=/.test(text)) {
    err(`${where}: يحتوي class= — الـCSS مُصرَّف مسبقًا فلا تُستعمل أصناف في المحتوى`);
  }
  const tags = [...text.matchAll(/<\/?([a-z0-9]+)/gi)].map((m) => m[1].toLowerCase());
  const bad = [...new Set(tags.filter((t) => !OK_TAGS.includes(t)))];
  if (bad.length) {
    err(`${where}: وسوم غير مسموحة ${bad.join(', ')}`);
  }
}

function checkSections(S) {
  if (!Array.isArray(S)) { return err('sections.json ليس مصفوفة'); }
  const seen = new Set();
  for (const s of S) {
    for (const k of ['slug', 'title', 'nav', 'group', 'icon', 'tint', 'desc']) {
      if (!s[k]) { err(`قسم ${s.slug || '?'}: حقل ناقص ${k}`); }
    }
    if (seen.has(s.slug)) { err(`قسم مكرر: ${s.slug}`); }
    seen.add(s.slug);
    if (!/^[a-z0-9-]+$/.test(s.slug || '')) { err(`قسم باسم غير لاتيني: ${s.slug}`); }
    if (!TINTS.includes(s.tint)) { err(`قسم ${s.slug}: لون غير مسموح "${s.tint}"`); }
    if (!GROUPS.includes(s.group)) { err(`قسم ${s.slug}: مجموعة غير معروفة "${s.group}"`); }
    if (!fs.existsSync(path.join(ROOT, s.slug, 'index.php'))) {
      err(`قسم ${s.slug}: لا يوجد مجلد ${s.slug}/index.php`);
    }
  }
}

function checkArticles(A, S) {
  if (!Array.isArray(A)) { return err('articles.json ليس مصفوفة'); }
  const slugs = new Set();
  const secs = new Set((S || []).map((s) => s.slug));
  for (const a of A) {
    const at = `مقال ${a.slug || '?'}`;
    for (const k of ['slug', 'section', 'title', 'description', 'date']) {
      if (!a[k]) { err(`${at}: حقل ناقص ${k}`); }
    }
    if (slugs.has(a.slug)) { err(`slug مكرر: ${a.slug}`); }
    slugs.add(a.slug);
    if (!/^[a-z0-9-]+$/.test(a.slug || '')) { err(`${at}: slug غير لاتيني`); }
    if (a.section && !secs.has(a.section)) { err(`${at}: قسم غير معرَّف "${a.section}"`); }
    if (a.date && !/^\d{4}-\d{2}-\d{2}$/.test(a.date)) { err(`${at}: تاريخ بصيغة خاطئة "${a.date}"`); }
    if (!fs.existsSync(path.join(ROOT, 'maqalat', a.slug + '.php'))) {
      err(`${at}: لا يوجد ملف maqalat/${a.slug}.php`);
    }
    const d = (a.description || '').length;
    if (d && (d < 80 || d > 160)) { warn(`${at}: الوصف ${d} حرفًا (المثالي 80–160)`); }
  }
}

function checkMoujam(M) {
  if (!M || !Array.isArray(M.terms)) { return err('moujam.json بلا terms'); }
  const cats = new Set((M.cats || []).map((c) => c.slug));
  const byName = new Set(M.terms.map((t) => t.t));
  const slugs = new Set();
  for (const t of M.terms) {
    const at = `مصطلح ${t.t || '?'}`;
    for (const k of ['t', 's', 'c', 'd']) {
      if (!t[k]) { err(`${at}: حقل ناقص ${k}`); }
    }
    if (slugs.has(t.s)) { err(`معرّف مصطلح مكرر: ${t.s}`); }
    slugs.add(t.s);
    if (!/^[a-z0-9-]+$/.test(t.s || '')) { err(`${at}: معرّف غير لاتيني "${t.s}"`); }
    if (t.c && !cats.has(t.c)) { err(`${at}: فئة غير معرَّفة "${t.c}"`); }
    for (const r of t.r || []) {
      if (!byName.has(r)) { warn(`${at}: يحيل على مصطلح غير موجود «${r}»`); }
    }
  }
  for (const c of M.cats || []) {
    if (!TINTS.includes(c.tint)) { err(`فئة معجم ${c.slug}: لون غير مسموح "${c.tint}"`); }
  }
}

function checkIkhtibarat(Q) {
  if (!Q || !Array.isArray(Q.quizzes)) { return err('ikhtibarat.json بلا quizzes'); }
  const seenQ = new Map();
  const slugs = new Set();
  for (const z of Q.quizzes) {
    for (const k of ['slug', 'title', 'desc', 'icon', 'tint', 'qs']) {
      if (!z[k]) { err(`اختبار ${z.slug || '?'}: حقل ناقص ${k}`); }
    }
    if (slugs.has(z.slug)) { err(`معرّف اختبار مكرر: ${z.slug}`); }
    slugs.add(z.slug);
    if (!TINTS.includes(z.tint)) { err(`اختبار ${z.slug}: لون غير مسموح "${z.tint}"`); }
    if (!/^fa-[a-z0-9-]+$/.test(z.icon || '')) { err(`اختبار ${z.slug}: أيقونة مشبوهة "${z.icon}"`); }
    if (!fs.existsSync(path.join(ROOT, 'ikhtibarat', z.slug, 'index.php'))) {
      err(`اختبار ${z.slug}: لا يوجد مجلد ikhtibarat/${z.slug}/index.php`);
    }

    (z.qs || []).forEach((q, i) => {
      const at = `${z.slug}[${i}]`;
      if (Object.keys(q).sort().join(',') !== 'c,e,o,q') {
        err(`${at}: مفاتيح غير متوقعة (${Object.keys(q).join(',')}) — المنتظر q,o,c,e`);
      }
      if (!Array.isArray(q.o) || q.o.length !== 4) {
        err(`${at}: عدد الخيارات ${q.o ? q.o.length : 0} — المنتظر 4`);
      } else if (new Set(q.o).size !== q.o.length) {
        err(`${at}: خيارات مكررة`);
      }
      if (!Number.isInteger(q.c) || q.c < 0 || q.c >= (q.o || []).length) {
        err(`${at}: c=${q.c} خارج مجال الخيارات`);
      }
      if (!q.e || q.e.length < 20) { err(`${at}: الشرح ناقص`); }
      checkHtml(q.e || '', at);

      const key = (q.q || '').replace(/\s+/g, ' ').trim();
      if (seenQ.has(key)) { err(`سؤال مكرر: «${key.slice(0, 55)}…» في ${at} و ${seenQ.get(key)}`); }
      seenQ.set(key, at);
    });
  }
}

function checkWathaiq(W) {
  if (!W || !Array.isArray(W.docs)) { return err('wathaiq.json بلا docs'); }
  const secs = new Set((W.sections || []).map((s) => s.slug));
  for (const s of W.sections || []) {
    if (!TINTS.includes(s.tint)) { err(`ملف وثائق ${s.slug}: لون غير مسموح "${s.tint}"`); }
    if (!fs.existsSync(path.join(ROOT, 'maktaba', 'wathaiq', s.slug, 'index.php'))) {
      err(`ملف وثائق ${s.slug}: لا يوجد maktaba/wathaiq/${s.slug}/index.php`);
    }
  }
  const titles = new Map();
  W.docs.forEach((d, i) => {
    const at = `وثيقة[${i + 1}]`;
    for (const k of ['s', 't', 'f', 'kb', 'h']) {
      if (d[k] === undefined || d[k] === '') { err(`${at}: حقل ناقص ${k}`); }
    }
    if (!secs.has(d.s)) { err(`${at}: ملف غير معرَّف "${d.s}"`); }
    if (!['archive', 'local'].includes(d.h)) { err(`${at}: استضافة غير معروفة "${d.h}"`); }
    if (typeof d.kb !== 'number' || d.kb <= 0) { err(`${at}: حجم غير صالح "${d.kb}"`); }
    if (/[^\x20-\x7E]/.test(d.f || '')) { err(`${at}: اسم ملف غير لاتيني "${d.f}" — cPanel يُشوّهه`); }
    if (titles.has(d.t)) { warn(`${at}: عنوان مطابق للوثيقة ${titles.get(d.t)}`); }
    titles.set(d.t, i + 1);
  });
  if (W.docs.some((d) => d.h === 'archive') && !W.archiveBase) {
    err('wathaiq.json: توجد وثائق على الأرشيف بلا archiveBase');
  }
}

/**
 * أعداد مكتوبة يدويًا في صفحات PHP قد تتقادم عن مصدرها.
 * وقع هذا فعلًا مرتين: «52 سؤالًا» بعد أن صارت 350، و«300 وثيقة» بعد أن صارت 162.
 */
function checkStaleCounts(totals) {
  const units = [
    [/(\d+)\s*سؤالًا/g, totals.questions, 'أسئلة الاختبارات'],
    [/(\d+)\s*وثيقة(?!\s*رسمية بصيغة)/g, totals.docs, 'وثائق الحقيبة'],
    [/(\d+)\s*مصطلحًا/g, totals.terms, 'مصطلحات المعجم'],
    [/(\d+)\s*مقالًا/g, totals.articles, 'المقالات'],
  ];

  for (const f of phpFiles()) {
    const src = fs.readFileSync(f, 'utf8');
    const rel = path.relative(ROOT, f).replace(/\\/g, '/');
    for (const [re, actual, label] of units) {
      re.lastIndex = 0;
      let m;
      while ((m = re.exec(src)) !== null) {
        /* تجاهل ما كان مُولَّدًا من PHP: <?= ... ?> قبل الرقم مباشرة */
        const before = src.slice(Math.max(0, m.index - 60), m.index);
        if (/\?>\s*$/.test(before)) { continue; }
        if (+m[1] !== actual) {
          warn(`${rel}: مكتوب «${m[0].trim()}» والعدد الحقيقي ${actual} (${label})`);
        }
      }
    }
  }
}

/** كل ملفات PHP في الموقع، عدا المجلدات المحلية التي لا تُرفع */
function phpFiles(dir = ROOT, out = []) {
  for (const name of fs.readdirSync(dir)) {
    if (name.startsWith('_') || name === 'node_modules' || name === '.git') { continue; }
    const p = path.join(dir, name);
    const st = fs.statSync(p);
    if (st.isDirectory()) { phpFiles(p, out); } else if (name.endsWith('.php')) { out.push(p); }
  }

  return out;
}

function run() {
  errs.length = 0;
  warns.length = 0;

  const S = readJson('sections');
  const A = readJson('articles');
  const M = readJson('moujam');
  const Q = readJson('ikhtibarat');
  const W = readJson('wathaiq');

  if (S) { checkSections(S); }
  if (A) { checkArticles(A, S); }
  if (M) { checkMoujam(M); }
  if (Q) { checkIkhtibarat(Q); }
  if (W) { checkWathaiq(W); }

  const totals = {
    articles: (A || []).length,
    terms: ((M || {}).terms || []).length,
    questions: ((Q || {}).quizzes || []).reduce((n, z) => n + (z.qs || []).length, 0),
    docs: ((W || {}).docs || []).length,
  };
  if (A && M && Q && W) { checkStaleCounts(totals); }

  console.log('── فحص البيانات ──');
  console.log(`   ${totals.articles} مقالًا · ${totals.terms} مصطلحًا · `
    + `${totals.questions} سؤالًا في ${((Q || {}).quizzes || []).length} بنوك · ${totals.docs} وثيقة`);
  warns.forEach((w) => console.log('   ⚠ ' + w));
  errs.forEach((e) => console.log('   ✗ ' + e));
  console.log(errs.length ? `   ✗ ${errs.length} خطأ` : '   ✓ سليم'
    + (warns.length ? ` (${warns.length} تنبيهًا)` : ''));

  return errs.length === 0;
}

module.exports = { run };

if (require.main === module) { process.exit(run() ? 0 : 1); }
