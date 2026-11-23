/**
 * فحص الصفحات على خادم المعاينة المحلي.
 *
 * يجلب كل رابط في sitemap.xml ثم يتحقق من أمرين:
 *   • رمز الحالة 200 (و404 لصفحة غير موجودة عمدًا)
 *   • خلوّ الصفحة من أي أثر لخطأ PHP — لا الأخطاء القاتلة فقط،
 *     بل Warning و Notice و Undefined و Deprecated أيضًا.
 *
 * يحتاج الخادم يعمل:  php -S localhost:8000 _dev-router.php
 * ولإظهار التحذيرات في جسم الصفحة شغّله هكذا:
 *   php -d error_reporting=E_ALL -d display_errors=1 -S localhost:8000 _dev-router.php
 */

const http = require('http');

const HOST = 'localhost';
const PORT = 8000;

const PHP_ERR = /(Warning|Notice|Deprecated|Fatal error|Parse error|Undefined|Uncaught|TypeError|ValueError|ArgumentCountError)\s*:/i;

/** روابط تُفحص دائمًا ولو لم تكن في خريطة الموقع */
const EXTRA = ['/sitemap.xml', '/rss.xml', '/baht/', '/tatbiq/'];
/** روابط يجب أن تُحجب */
const BLOCKED = ['/data/articles.json', '/partials/lib.php', '/_dev-router.php'];
/**
 * روابط غير موجودة عمدًا للتأكد أن صفحة 404 تعمل.
 *
 * ⚠️ المسارات الديناميكية مُدرجة عمدًا: صفحاتها تستدعي `404.php` من داخلها،
 * وقد وقع فعلًا Fatal error (Cannot redeclare) بسبب تضمين `lib.php` مرتين.
 * ورمز الحالة يبقى 404 في تلك الحالة، فلا يكشفها إلا فحص **جسم** الصفحة.
 */
const NOT_FOUND = [
  '/hadha-arrabit-la-yujad-abadan',
  '/moujam/maslah-la-yujad',
  '/moujam/majal/majal-la-yujad',
];

function get(p) {
  return new Promise((resolve) => {
    const req = http.get({ host: HOST, port: PORT, path: encodeURI(p) }, (res) => {
      let b = '';
      res.setEncoding('utf8');
      res.on('data', (d) => { b += d; });
      res.on('end', () => resolve({ s: res.statusCode, b }));
    });
    req.on('error', (e) => resolve({ s: 0, b: '', e: e.message }));
    req.setTimeout(30000, () => { req.destroy(); resolve({ s: -1, b: '', e: 'timeout' }); });
  });
}

async function run() {
  console.log('── فحص الصفحات ──');

  const probe = await get('/');
  if (probe.s !== 200) {
    console.log(`   ✗ الخادم لا يستجيب على http://${HOST}:${PORT}`);
    console.log('     شغّله أولًا:  php -S localhost:8000 _dev-router.php');

    return false;
  }

  const sm = await get('/sitemap.xml');
  const urls = [...sm.b.matchAll(/<loc>([^<]+)<\/loc>/g)]
    .map((m) => decodeURIComponent(m[1].replace(/^https?:\/\/[^/]+/, '')));

  if (!urls.length) { console.log('   ✗ خريطة الموقع فارغة أو معطوبة'); }

  const all = [...new Set([...urls, ...EXTRA])];
  const bad = [];
  const warned = [];

  for (const u of all) {
    const r = await get(u);
    if (r.s !== 200) { bad.push(`${u} → ${r.s}${r.e ? ' (' + r.e + ')' : ''}`); }
    const hit = r.b.match(PHP_ERR);
    if (hit) {
      const i = Math.max(0, r.b.search(PHP_ERR) - 80);
      warned.push(`${u} → ${r.b.slice(i, i + 220).replace(/\s+/g, ' ')}`);
    }
  }

  /* صفحات 404 — الحالة والجسم معًا */
  for (const u of NOT_FOUND) {
    const nf = await get(u);
    if (nf.s !== 404) { bad.push(`${u} → ${nf.s} (المنتظر 404)`); }
    const hit = nf.b.match(PHP_ERR);
    if (hit) {
      const i = Math.max(0, nf.b.search(PHP_ERR) - 80);
      warned.push(`${u} (صفحة 404) → ${nf.b.slice(i, i + 220).replace(/\s+/g, ' ')}`);
    }
  }

  /* حجب ملفات النظام */
  for (const u of BLOCKED) {
    const r = await get(u);
    if (r.s !== 403) { bad.push(`${u} → ${r.s} (المنتظر 403 — ملف نظام مكشوف!)`); }
  }

  console.log(`   ${all.length} رابطًا في الخريطة والإضافات · ${BLOCKED.length} ملف نظام · ${NOT_FOUND.length} صفحات 404`);
  bad.forEach((b) => console.log('   ✗ ' + b));
  warned.forEach((w) => console.log('   ✗ تحذير PHP في ' + w));

  const ok = bad.length === 0 && warned.length === 0;
  console.log(ok
    ? '   ✓ كل الصفحات 200 · صفر تحذيرات PHP'
    : `   ✗ ${bad.length} خطأ حالة · ${warned.length} تحذير PHP`);

  return ok;
}

module.exports = { run };

if (require.main === module) { run().then((ok) => process.exit(ok ? 0 : 1)); }
