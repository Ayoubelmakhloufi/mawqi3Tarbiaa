/**
 * فاحص منصة تربية — شغّل الثلاثة دفعة واحدة.
 *
 *   node _fahs/fahs.js
 *
 * ولتشغيل فحص بعينه:
 *   node _fahs/bayanat.js      سلامة ملفات data/ وتطابقها مع الملفات
 *   node _fahs/css.js          أصناف Tailwind الموجودة فعلًا في الـCSS المُصرَّف
 *   node _fahs/safahat.js      الصفحات على الخادم المحلي (200 + تحذيرات PHP)
 *
 * يخرج برمز 1 إن فشل أي فحص، فيصلح للاستعمال قبل كل رفع.
 */

const bayanat = require('./bayanat');
const css = require('./css');
const safahat = require('./safahat');

(async () => {
  const results = [];

  results.push(['البيانات', bayanat.run()]);
  console.log('');
  results.push(['أصناف CSS', css.run()]);
  console.log('');
  results.push(['الصفحات', await safahat.run()]);

  const failed = results.filter(([, ok]) => !ok);

  console.log('\n══════════════════════════════');
  results.forEach(([name, ok]) => console.log(`  ${ok ? '✓' : '✗'} ${name}`));

  if (failed.length) {
    console.log(`\n  ✗ فشل ${failed.length} من ${results.length} — لا ترفع قبل الإصلاح.`);
    process.exit(1);
  }

  console.log('\n  ✓ كل الفحوص نظيفة — جاهز للرفع.');
})();
