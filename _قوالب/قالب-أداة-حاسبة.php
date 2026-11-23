<?php
/* ============================================================
   قالب أداة / حاسبة

   ضعه في الجذر باسم لاتيني، مثلًا:  hisab-nuqta-mihaniya.php
   ← الرابط /hisab-nuqta-mihaniya

   تريد رابطًا عربيًا؟ أبقِ اسم الملف لاتينيًا وأضف في .htaccess:
       RewriteRule ^حساب_النقطة_المهنية/?$ hisab-nuqta-mihaniya.php [L]
   ثم اجعل 'canonical' أدناه هو الرابط العربي.
   (لا تسمِّ الملف بالعربية أبدًا — تُشوّهه أدوات فك الضغط.)

   ولا تنسَ:
   ‏- إضافة الأداة إلى بطاقات adawat/index.php
   ‏- إضافة سطر في sitemap.php
   ============================================================ */

require __DIR__ . '/partials/lib.php';

$meta = [
    'title'       => 'اسم الأداة — وصف قصير',
    'description' => 'ماذا تفعل الأداة بالضبط، في 80–160 حرفًا، بصيغة تجيب عن سؤال الباحث.',
    'canonical'   => '/hisab-nuqta-mihaniya',
    'image'       => '/assets/img/og-adawat.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'اسم الأداة', 'url' => '/hisab-nuqta-mihaniya'],
    ],
    /* بيانات منظمة: تجعل جوجل يعرفها كتطبيق ويب مجاني */
    'jsonld'      => [[
        '@context'            => 'https://schema.org',
        '@type'               => 'WebApplication',
        'name'                => 'اسم الأداة',
        'url'                 => tb_abs('/hisab-nuqta-mihaniya'),
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem'     => 'All',
        'inLanguage'          => 'ar',
        'description'         => 'وصف الأداة.',
        'offers'              => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'MAD'],
        'publisher'           => ['@id' => SITE_URL . '/#organization'],
    ]],
];

require __DIR__ . '/partials/header.php';
?>

<!-- ترويسة -->
<section class="gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-accent transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/adawat/" class="hover:text-accent transition-colors">الأدوات والحاسبات</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">اسم الأداة</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">اسم الأداة</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-6">جملة تشرح ما تفعله الأداة.</p>
      <p class="inline-flex flex-wrap items-center gap-2 bg-white/10 text-accent rounded-2xl px-5 py-3 font-bold text-sm md:text-base">
        <i class="fas fa-wand-magic-sparkles"></i>
        الصيغة المعتمدة = اكتبها هنا
      </p>
    </div>
  </div>
</section>

<div class="container mx-auto px-4 md:px-6 py-12 md:py-16">
  <div class="grid gap-8 lg:grid-cols-5">

    <!-- النموذج -->
    <div class="lg:col-span-3 space-y-6">
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 shadow-sm">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
          <i class="fas fa-calculator text-accent"></i> أدخل معطياتك
        </h2>

        <div class="space-y-5">
          <div>
            <label for="opt" class="block font-bold text-slate-700 mb-2">١. اختيار من قائمة</label>
            <select id="opt" class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
              <option value="">— اختر —</option>
            </select>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="val1" class="block font-bold text-slate-700 mb-2">٢. القيمة الأولى</label>
              <input id="val1" type="number" inputmode="decimal" min="0" max="20" step="0.01" placeholder="مثال: 14"
                     class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
            </div>
            <div>
              <label for="val2" class="block font-bold text-slate-700 mb-2">٣. القيمة الثانية</label>
              <input id="val2" type="number" inputmode="decimal" min="0" max="20" step="0.01" placeholder="مثال: 14"
                     class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
            </div>
          </div>

          <p id="calc-error" hidden class="text-sm font-bold text-red-600 bg-red-50 rounded-xl px-4 py-3"></p>

          <div class="flex gap-3 pt-1">
            <button type="button" id="calc" disabled
                    class="flex-1 bg-primary text-white py-4 rounded-xl font-black hover:bg-secondary disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-lg shadow-primary/20">
              احسب
            </button>
            <button type="button" id="reset"
                    class="px-6 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">
              <i class="fas fa-rotate-left ml-1.5"></i>مسح
            </button>
          </div>
        </div>
      </div>

      <!-- النتيجة -->
      <div id="result" hidden class="rounded-3xl overflow-hidden ring-1 ring-slate-200 shadow-sm">
        <div class="bg-primary text-white text-center px-6 py-8">
          <p class="text-white/70 font-medium">النتيجة</p>
          <p class="text-5xl md:text-6xl font-black text-gold my-2" id="score">0.00</p>
          <p id="msg" class="font-bold"></p>
        </div>
        <div class="bg-white p-6 md:p-8">
          <h3 class="font-black text-primary flex items-center gap-2 mb-4"><i class="fas fa-circle-info text-accent"></i> تفصيل الحساب</h3>
          <ol id="steps" class="space-y-2.5 pr-5 list-decimal marker:text-accent marker:font-black text-slate-700"></ol>
        </div>
      </div>
    </div>

    <!-- الشرح -->
    <aside class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-7 shadow-sm">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-4"><i class="fas fa-book-open text-accent"></i> كيف تعمل الأداة؟</h2>
        <div class="article-body text-[15px]">
          <p>اشرح الصيغة ومصدرها الرسمي.</p>
          <div class="note">
            <b>تنبيه:</b> الأداة مساعدة تقريبية — ارجع إلى الوثيقة الرسمية قبل أي قرار.
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php require __DIR__ . '/partials/app-cta.php'; ?>

<script>
/* منطق الأداة — كل الحساب داخل المتصفح، لا تُرسَل أي معطيات إلى الخادم. */
(function () {
  'use strict';

  /* ── عدّل هذه البيانات حسب أداتك ── */
  var OPTIONS = [
    { name: 'الخيار الأول',  factor: 1.5 },
    { name: 'الخيار الثاني', factor: 1.2 }
  ];

  var $ = function (id) { return document.getElementById(id); };
  var opt = $('opt'), v1 = $('val1'), v2 = $('val2');
  var calcBtn = $('calc'), resetBtn = $('reset'), result = $('result'), errBox = $('calc-error');

  OPTIONS.forEach(function (o, i) {
    var el = document.createElement('option');
    el.value = String(i);
    el.textContent = o.name + ' — معامل ' + o.factor;
    opt.appendChild(el);
  });

  /** رسالة خطأ إن كانت القيم خارج المجال، أو '' إن كان كل شيء سليمًا */
  function validationError() {
    var fields = [{ el: v1, label: 'القيمة الأولى' }, { el: v2, label: 'القيمة الثانية' }];
    for (var i = 0; i < fields.length; i++) {
      var raw = fields[i].el.value;
      if (raw === '') continue;
      var n = Number(raw);
      if (isNaN(n) || n < 0 || n > 20) return fields[i].label + ' يجب أن تكون بين 0 و 20.';
    }
    return '';
  }

  function canCompute() {
    return opt.value !== '' && v1.value !== '' && v2.value !== '' && validationError() === '';
  }

  function update() {
    var err = validationError();
    errBox.textContent = err;
    errBox.hidden = err === '';
    calcBtn.disabled = !canCompute();
    result.hidden = true;
  }

  [opt, v1, v2].forEach(function (el) {
    el.addEventListener('change', update);
    el.addEventListener('input', update);
  });

  calcBtn.addEventListener('click', function () {
    if (!canCompute()) return;

    var a = Number(v1.value), b = Number(v2.value);
    var chosen = OPTIONS[Number(opt.value)];

    /* ── عدّل معادلتك هنا ── */
    var base = a * 0.75 + b * 0.25;
    var total = base * chosen.factor;

    $('score').textContent = total.toFixed(2);
    $('msg').textContent = 'بالتوفيق 💪';
    $('steps').innerHTML =
      '<li>' + a + ' × 0.75 = <b>' + (a * 0.75).toFixed(2) + '</b></li>' +
      '<li>' + b + ' × 0.25 = <b>' + (b * 0.25).toFixed(2) + '</b></li>' +
      '<li>المجموع = <b>' + base.toFixed(2) + '</b></li>' +
      '<li>× المعامل (' + chosen.factor + ') = <b class="text-primary">' + total.toFixed(2) + '</b></li>';

    result.hidden = false;
    result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });

  resetBtn.addEventListener('click', function () {
    opt.value = ''; v1.value = ''; v2.value = '';
    update();
  });
})();
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
