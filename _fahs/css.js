/**
 * فحص أصناف Tailwind.
 *
 * ⚠️ لماذا هذا الفحص موجود أصلًا؟
 * ملف assets/css/tarbiaa.css **مُصرَّف مسبقًا**. والصنف الذي لا وجود له فيه
 * لا يُنتج خطأ ولا تحذيرًا — يُهمَل بصمت فيختل الشكل دون أن يلاحظ أحد.
 * وقع هذا فعلًا: quiz.php كان يولّد `sm:grid-cols-5` ديناميكيًا، وهي غير
 * مُصرَّفة أصلًا لأن ماسح Tailwind لا يرى الأصناف المُركَّبة في PHP،
 * فبقيت أزرار عدد الأسئلة عمودين على الحاسوب شهورًا بلا أن يظهر أي عطل.
 *
 * لا يحتاج خادمًا يعمل.
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..');

/** أصناف تأتي من FontAwesome أو Alpine لا من Tailwind */
const EXTERNAL = /^(fa|fas|far|fab)$|^fa-|^x-/;

/** أصناف علامة فقط: يُمسك بها JavaScript ولا تحمل تنسيقًا */
const MARKERS = ['term'];

/** يبني قائمة كل الأصناف الموجودة فعلًا في الملف المُصرَّف */
function compiledClasses() {
  const css = fs.readFileSync(path.join(ROOT, 'assets/css/tarbiaa.css'), 'utf8');
  const set = new Set();
  /* اسم الصنف = حروف/أرقام/شرطات أو محرف مهروب؛ ينتهي عند أول محرف غير مهروب.
     الملف مُصغَّر، فالتعبير يجب ألا يبتلع `>` و`:` و`(` التي تلي اسم الصنف. */
  for (const m of css.matchAll(/\.((?:[-\w]|\\.)+)/g)) {
    set.add(m[1].replace(/\\/g, ''));
  }

  return set;
}

/** كل ملفات الموقع التي قد تحمل أصنافًا، عدا المجلدات المحلية */
function sourceFiles(dir = ROOT, out = []) {
  for (const name of fs.readdirSync(dir)) {
    if (name.startsWith('_') || name === 'node_modules' || name === '.git' || name === 'assets') {
      continue;
    }
    const p = path.join(dir, name);
    if (fs.statSync(p).isDirectory()) { sourceFiles(p, out); } else if (/\.(php|html)$/.test(name)) { out.push(p); }
  }

  return out;
}

/**
 * يستخرج الأصناف من تعبير برمجي (بلوك PHP أو تعبير Alpine).
 *
 * ⚠️ لا تُؤخذ كل السلاسل المفردة: التعبير يحمل سلاسل مقارنة أيضًا
 * مثل `$active === 'home' ? 'text-primary' : ''` — و«home» ليست صنفًا.
 * لذلك تُؤخذ **فرعا الشرط الثلاثي** وما يلي `&&` فقط، وهما موضعا الأصناف.
 */
function classesInExpression(expr, out) {
  for (const m of expr.matchAll(/\?\s*'([^']*)'\s*:\s*'([^']*)'/g)) {
    m[1].split(/\s+/).forEach((c) => out.add(c));
    m[2].split(/\s+/).forEach((c) => out.add(c));
  }
  for (const m of expr.matchAll(/&&\s*'([^']*)'/g)) {
    m[1].split(/\s+/).forEach((c) => out.add(c));
  }
}

/** يستخرج الأصناف من ملف واحد */
function classesIn(src) {
  const found = new Set();

  /* class="..." الثابتة — يُستثنى :class و x-bind:class */
  for (const m of src.matchAll(/(?<![:@\w-])class="([^"]*)"/g)) {
    const value = m[1];
    /* بلوكات PHP داخل قيمة class تُعامل كتعابير */
    (value.match(/<\?[\s\S]*?\?>/g) || []).forEach((blk) => classesInExpression(blk, found));
    /* والنص الثابت خارجها يُقسم على المسافات */
    value.replace(/<\?[\s\S]*?\?>/g, ' ').split(/\s+/).forEach((c) => found.add(c));
  }

  /* :class="..." تعبير Alpine دائمًا */
  for (const m of src.matchAll(/:class="([^"]*)"/g)) {
    classesInExpression(m[1], found);
  }

  return found;
}

/**
 * هل يستحق هذا الرمز أن يُبلَّغ عنه؟
 * أدوات Tailwind تحمل شرطة أو نقطتين أو قوسًا مربعًا تقريبًا دائمًا
 * (text-xl · md:flex · aspect-[16/9]). أما الكلمة المفردة فقد تكون
 * قيمة برمجية تسربت، فتُتجاهل تفاديًا للإنذار الكاذب.
 */
function reportable(c, defined) {
  if (!c || EXTERNAL.test(c) || MARKERS.includes(c)) { return false; }
  if (/[<>$?=(){}'"]|&&|\?\?/.test(c)) { return false; }
  if (!/^-?[a-z]/i.test(c) || c.length < 2) { return false; }   /* رمز شارد لا صنف */
  if (!/[-:[]/.test(c)) { return false; }

  return !defined.has(c);
}

function run() {
  const defined = compiledClasses();
  const files = sourceFiles();
  const problems = [];
  let scanned = 0;

  for (const f of files) {
    const src = fs.readFileSync(f, 'utf8');
    const rel = path.relative(ROOT, f).replace(/\\/g, '/');

    /* صنف يُبنى من PHP لن يراه ماسح Tailwind أبدًا — عطل صامت مؤكد */
    for (const m of src.matchAll(/class="[^"]*?[\w-]+-<\?=?[^"]*"/g)) {
      problems.push(`${rel}: صنف مُولَّد ديناميكيًا لن يُصرَّف — ${m[0].slice(0, 70)}`);
    }

    const missing = [...classesIn(src)].filter((c) => reportable(c, defined));
    scanned++;
    missing.forEach((c) => problems.push(`${rel}: الصنف «${c}» غير موجود في الـCSS المُصرَّف`));
  }

  console.log('── فحص أصناف CSS ──');
  console.log(`   ${defined.size} صنفًا مُصرَّفًا · فُحص ${scanned} ملفًا`);
  problems.forEach((p) => console.log('   ✗ ' + p));
  console.log(problems.length ? `   ✗ ${problems.length} مشكلة` : '   ✓ كل الأصناف مُصرَّفة');

  if (problems.length) {
    console.log('   ملاحظة: إن كان الصنف مطلوبًا فعلًا، أعد التصريف:');
    console.log('   npx tailwindcss@3.4.17 -c tailwind.config.js -i assets/css/src.css -o assets/css/tarbiaa.css --minify');
  }

  return problems.length === 0;
}

module.exports = { run, compiledClasses };

if (require.main === module) { process.exit(run() ? 0 : 1); }
