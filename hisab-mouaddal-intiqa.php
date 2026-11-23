<?php
require __DIR__ . '/partials/lib.php';

/** سنة المرجع الرسمي المعتمد في المعاملات — تُحدَّث عند صدور ملحق جديد. */
const REF_YEAR = '2026-2027';

$meta = [
    'title'       => 'حاسبة معدل الانتقاء للمدارس العليا للأساتذة (ENS · ESEF · FSE)',
    'description' => 'احسب معدل انتقائك التمهيدي للإجازة في التربية: (الوطني × 0.75 + الجهوي × 0.25) × معامل الترجيح، بمعاملات الملحق الرسمي رقم 2 لسنة ' . REF_YEAR . '.',
    /* الرابط الأصلي مُحافَظ عليه عمدًا: سبق أن شورك مع المستخدمين، فلا يجوز تغييره. */
    'canonical'   => CALC_URL,
    'image'       => '/assets/img/og-adawat.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'حاسبة معدل الانتقاء', 'url' => CALC_URL],
    ],
    'jsonld'      => [[
        '@context'            => 'https://schema.org',
        '@type'               => 'WebApplication',
        'name'                => 'حاسبة معدل الانتقاء للمدارس العليا للأساتذة',
        'url'                 => tb_abs(CALC_URL),
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem'     => 'All',
        'browserRequirements' => 'يعمل مباشرة في المتصفح دون تثبيت',
        'inLanguage'          => 'ar',
        'description'         => 'أداة مجانية لحساب معدل الانتقاء التمهيدي لولوج الإجازة في التربية بالمدارس العليا للأساتذة بالمغرب، وفق الملحق الرسمي رقم 2.',
        'offers'              => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'MAD'],
        'publisher'           => ['@id' => SITE_URL . '/#organization'],
    ]],
];

require __DIR__ . '/partials/header.php';
?>

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
        <li class="text-white/80" aria-current="page">حاسبة معدل الانتقاء</li>
      </ol>
    </nav>

    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">حاسبة معدل الانتقاء</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-6">
        احسب معدل انتقائك التمهيدي لولوج الإجازة في التربية بالمدارس العليا للأساتذة
        (ENS · ESEF · FSE) في ثوانٍ، مع تفصيل كامل لخطوات الحساب.
      </p>
      <div class="flex flex-wrap gap-3">
        <p class="inline-flex flex-wrap items-center gap-2 bg-white/10 text-accent rounded-2xl px-5 py-3 font-bold text-sm md:text-base">
          <i class="fas fa-wand-magic-sparkles"></i>
          معدل الانتقاء = (الوطني × 0.75 + الجهوي × 0.25) × معامل الترجيح
        </p>
        <p class="inline-flex items-center gap-2 bg-accent/20 text-accent rounded-2xl px-5 py-3 font-bold text-sm">
          <i class="fas fa-file-circle-check"></i>
          معاملات الملحق رقم 2 — <?= e(REF_YEAR) ?>
        </p>
      </div>
    </div>
  </div>
</section>

<div class="container mx-auto px-4 md:px-6 py-12 md:py-16">
  <div class="grid gap-8 lg:grid-cols-5">

    <!-- نموذج الحساب -->
    <div class="lg:col-span-3 space-y-6">
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 shadow-sm">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
          <i class="fas fa-calculator text-accent"></i> أدخل معطياتك
        </h2>

        <div class="space-y-5">
          <div>
            <label for="track" class="block font-bold text-slate-700 mb-2">١. اختر مسلك الإجازة في التربية</label>
            <select id="track" class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
              <option value="">— اختر المسلك الذي تترشح له —</option>
            </select>
            <p id="track-hint" hidden class="mt-2 text-sm font-bold text-secondary bg-emerald-50 rounded-xl px-4 py-2.5"></p>
          </div>

          <div>
            <label for="branch" class="block font-bold text-slate-700 mb-2">٢. اختر شعبتك في الباكالوريا</label>
            <select id="branch" disabled class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 font-medium text-slate-800 disabled:opacity-60 disabled:cursor-not-allowed focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
              <option value="">اختر المسلك أولًا</option>
            </select>
            <p id="branch-note" hidden class="mt-2 text-sm text-slate-500 bg-slate-50 rounded-xl px-4 py-2.5 leading-relaxed"></p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="national" class="block font-bold text-slate-700 mb-2">٣. نقطة الامتحان الوطني</label>
              <input id="national" type="number" inputmode="decimal" min="0" max="20" step="0.01" placeholder="مثال: 14"
                     class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
            </div>
            <div>
              <label for="regional" class="block font-bold text-slate-700 mb-2">٤. نقطة الامتحان الجهوي</label>
              <input id="regional" type="number" inputmode="decimal" min="0" max="20" step="0.01" placeholder="مثال: 14"
                     class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
            </div>
          </div>

          <p id="calc-error" hidden class="text-sm font-bold text-red-600 bg-red-50 rounded-xl px-4 py-3"></p>

          <div class="flex gap-3 pt-1">
            <button type="button" id="calc" disabled
                    class="flex-1 bg-primary text-white py-4 rounded-xl font-black hover:bg-secondary disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-lg shadow-primary/20">
              احسب معدل الانتقاء
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
          <p class="text-white/70 font-medium">معدل الانتقاء التمهيدي الخاص بك</p>
          <p class="text-5xl md:text-6xl font-black text-gold my-2" id="score">0.00</p>
          <p id="msg" class="font-bold"></p>
        </div>
        <div class="bg-white p-6 md:p-8">
          <h3 class="font-black text-primary flex items-center gap-2 mb-4"><i class="fas fa-circle-info text-accent"></i> تفصيل الحساب</h3>
          <ol id="steps" class="space-y-2.5 pr-5 list-decimal marker:text-accent marker:font-black text-slate-700"></ol>

          <p id="result-ref" class="mt-5 text-sm text-slate-500 bg-slate-50 rounded-xl px-4 py-3 leading-relaxed"></p>

          <div class="mt-6 bg-slate-50 ring-1 ring-slate-200 rounded-2xl p-5">
            <p class="text-slate-700 leading-relaxed mb-4">
              إذا كنت مهتمًا بعلوم التربية، فتطبيق <b>«تربية»</b> يرافقك بشجرة مهارات متدرجة
              واختبارات تفاعلية ومكتبة رقمية — مجانًا.
            </p>
            <div class="flex flex-wrap gap-3">
              <a href="<?= e(PLAY_URL) ?>" target="_blank" rel="noopener"
                 class="inline-flex items-center gap-2 bg-primary text-white px-5 py-3 rounded-xl font-bold hover:bg-secondary transition-colors">
                <i class="fab fa-google-play"></i> تحميل التطبيق
              </a>
              <a href="<?= e(WA_URL) ?>" target="_blank" rel="noopener"
                 class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-xl font-bold hover:bg-green-700 transition-colors">
                <i class="fab fa-whatsapp"></i> قناة الواتساب
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- الشرح والمراجع -->
    <aside class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-7 shadow-sm">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-4"><i class="fas fa-book-open text-accent"></i> طريقة الانتقاء</h2>
        <div class="article-body text-[15px]">
          <p><b>الانتقاء الأولي:</b> (75% الوطني + 25% الجهوي) × معامل الترجيح حسب التقاطع بين المسلك وشعبة الباكالوريا.</p>
          <p><b>القبول النهائي:</b> 75% نقطة الانتقاء + 25% نقطة المباراة الشفوية.</p>
        </div>
        <div class="mt-5 bg-slate-50 ring-1 ring-slate-200 rounded-2xl p-5">
          <p class="font-black text-slate-800 mb-2">🎓 مثال</p>
          <p class="text-sm text-slate-600 leading-relaxed">
            وطني 14 وجهوي 14 ← (10.50 + 3.50) = 14 ← 14 × 1.5 =
            <b class="text-primary text-base">21.00</b>
          </p>
        </div>
      </div>

      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-7 shadow-sm">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-2"><i class="fas fa-table-list text-accent"></i> جدول معاملات الترجيح</h2>
        <p class="text-sm font-bold text-slate-400 mb-4">الملحق رقم 2 — برسم الموسم الجامعي <?= e(REF_YEAR) ?></p>
        <div class="space-y-4">
          <a href="/assets/img/jadwal-mou3amilat-1.webp" target="_blank" rel="noopener" class="block group">
            <img src="/assets/img/jadwal-mou3amilat-1.webp" alt="جدول معاملات الترجيح الرسمي — الفئات من 1 إلى 6" loading="lazy"
                 width="766" height="1024" class="w-full rounded-2xl ring-1 ring-slate-200 group-hover:ring-accent transition">
          </a>
          <a href="/assets/img/jadwal-mou3amilat-2.webp" target="_blank" rel="noopener" class="block group">
            <img src="/assets/img/jadwal-mou3amilat-2.webp" alt="جدول معاملات الترجيح الرسمي — الفئتان 7 و8 والملاحظات" loading="lazy"
                 width="770" height="1024" class="w-full rounded-2xl ring-1 ring-slate-200 group-hover:ring-accent transition">
          </a>
        </div>
        <div class="article-body mt-5">
          <div class="note-ok">
            <b>المعاملات في هذه الحاسبة مطابقة للجدول أعلاه</b> حرفًا بحرف. ومع ذلك، تبقى الحاسبة أداة
            مساعدة: تحقّق دائمًا من بلاغ المؤسسة للسنة المعنية، فالمعاملات قد تُحدَّث.
          </div>
        </div>
      </div>

      <!-- حواشي مسالك البكالوريا المهنية -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-7 shadow-sm">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-4"><i class="fas fa-list-check text-accent"></i> مسالك البكالوريا المهنية المعنية</h2>
        <div class="space-y-4 text-sm leading-relaxed">
          <div>
            <p class="font-black text-slate-800 mb-1">* مسالك التجارة والتدبير <span class="font-medium text-slate-400">(الفئة 5)</span></p>
            <p class="text-slate-600">التجارة، المحاسبة، اللوجستيك، التسيير والإدارة.</p>
          </div>
          <div>
            <p class="font-black text-slate-800 mb-1">** المسالك التقنية <span class="font-medium text-slate-400">(الفئة 6 — معامل 1.4)</span></p>
            <p class="text-slate-600">التصنيع الميكانيكي، الصيانة الصناعية، صناعة البنيات المعدنية، الإلكتروتكنيك وأجهزة التواصل، الصيانة المعلوماتية والشبكات، النظم الإلكترونية والرقمية، الطاقات المتجددة (خيار: نظم الطاقة الشمسية).</p>
          </div>
          <div>
            <p class="font-black text-slate-800 mb-1">*** المسالك الأخرى <span class="font-medium text-slate-400">(الفئة 6 — معامل 1.2)</span></p>
            <p class="text-slate-600">صناعة الطائرات، صيانة المركبات المتحركة (خيار: السيارات)، التبريد وتكييف الهواء، رسم البناء، أوراش البناء، اللوجستيك.</p>
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php require __DIR__ . '/partials/app-cta.php'; ?>

<script>
/**
 * حاسبة معدل الانتقاء التمهيدي.
 * المعاملات منقولة حرفيًا من «الملحق رقم 2: طريقة احتساب معدل الانتقاء التمهيدي للمترشحين»
 * برسم الموسم الجامعي 2026-2027. أي تعديل هنا يجب أن يستند إلى ملحق رسمي جديد.
 */
(function () {
  'use strict';

  /* ــــ شعب الباكالوريا كما وردت في الملحق ــــ */
  var B = {
    math:     'شعبة العلوم الرياضية',
    exp:      'شعبة العلوم التجريبية',
    expPC:    'شعبة العلوم التجريبية (علوم فيزيائية)',
    expSVT:   'شعبة العلوم التجريبية (علوم الحياة والأرض)',
    expSVTA:  'شعبة العلوم التجريبية (علوم الحياة والأرض وعلوم زراعية)',
    techno:   'شعبة العلوم والتكنولوجيات',
    eco:      'شعبة العلوم الاقتصادية والتدبير',
    lettres:  'شعبة الآداب',
    humain:   'شعبة العلوم الإنسانية',
    asil:     'شعبة التعليم الأصيل',
    pro:      'شعب البكالوريا المهنية',
    proGest:  'شعب البكالوريا المهنية (التجارة والتدبير)',
    proTech:  'شعب البكالوريا المهنية (المسالك التقنية)',
    proOther: 'شعب البكالوريا المهنية (المسالك الأخرى)'
  };

  /* ــــ الفئات الثماني ومعاملاتها (الملحق رقم 2) ــــ */
  var CATEGORIES = {
    1: {
      title: 'الإجازة في التربية — تخصص التعليم الابتدائي: ثنائي اللغة',
      branches: [
        { n: B.math, c: 1.5 }, { n: B.exp, c: 1.5 }, { n: B.techno, c: 1.3 },
        { n: B.eco, c: 1.2 }, { n: B.lettres, c: 1.2 }, { n: B.humain, c: 1.2 },
        { n: B.asil, c: 1.2 }, { n: B.pro, c: 1 }
      ]
    },
    2: {
      title: 'الإجازة في التربية — تخصص التعليم الابتدائي: اللغة الأمازيغية',
      branches: [
        { n: B.math, c: 1.5 }, { n: B.exp, c: 1.5 }, { n: B.techno, c: 1.3 },
        { n: B.eco, c: 1.2 }, { n: B.lettres, c: 1.2 }, { n: B.humain, c: 1.2 },
        { n: B.asil, c: 1.2 }, { n: B.pro, c: 1 }
      ]
    },
    3: {
      title: 'الإجازة في التربية — التعليم الثانوي: اللغة العربية والفلسفة والدراسات الإسلامية والتاريخ والجغرافيا',
      branches: [
        { n: B.lettres, c: 1.5 }, { n: B.humain, c: 1.5 }, { n: B.asil, c: 1.3 },
        { n: B.math, c: 1.2 }, { n: B.exp, c: 1.2 }, { n: B.techno, c: 1.2 },
        { n: B.eco, c: 1.2 }, { n: B.pro, c: 1 }
      ]
    },
    4: {
      title: 'الإجازة في التربية — التعليم الثانوي: اللغة الفرنسية واللغة الإنجليزية',
      branches: [
        { n: B.lettres, c: 1.5 }, { n: B.humain, c: 1.5 }, { n: B.asil, c: 1.3 },
        { n: B.math, c: 1.2 }, { n: B.exp, c: 1.2 }, { n: B.techno, c: 1.2 },
        { n: B.eco, c: 1.2 }, { n: B.pro, c: 1 }
      ]
    },
    5: {
      title: 'الإجازة في التربية — التعليم الثانوي: العلوم الاقتصادية والتدبير',
      note: 'الشعب المؤهلة لهذا المسلك محدودة حسب الملحق. مسالك البكالوريا المهنية المعنية: التجارة، المحاسبة، اللوجستيك، التسيير والإدارة.',
      branches: [
        { n: B.eco, c: 1.5 }, { n: B.math, c: 1.4 }, { n: B.expPC, c: 1.3 },
        { n: B.proGest, c: 1.2 }, { n: B.expSVT, c: 1 }
      ]
    },
    6: {
      title: 'الإجازة في التربية — التعليم الثانوي: العلوم الصناعية',
      note: 'انتبه إلى التمييز بين المسالك التقنية (معامل 1.4) والمسالك الأخرى (معامل 1.2) في البكالوريا المهنية — راجع القائمة في الجانب.',
      branches: [
        { n: B.techno, c: 1.5 }, { n: B.math, c: 1.4 }, { n: B.proTech, c: 1.4 },
        { n: B.expPC, c: 1.3 }, { n: B.proOther, c: 1.2 }, { n: B.expSVTA, c: 1 }
      ]
    },
    7: {
      title: 'الإجازة في التربية — التعليم الثانوي: الرياضيات والفيزياء والكيمياء وعلوم الحياة والأرض والمعلوميات',
      note: 'حسب الملحق، هذا المسلك مفتوح أمام شعبتَي العلوم الرياضية والعلوم التجريبية فقط، وبمعامل 1.5 لكلتيهما.',
      branches: [
        { n: B.math, c: 1.5 }, { n: B.exp, c: 1.5 }
      ]
    },
    8: {
      title: 'الإجازة في التربية — التعليم الثانوي: التربية البدنية والرياضة',
      branches: [
        { n: B.exp, c: 1.5 }, { n: B.math, c: 1.4 }, { n: B.lettres, c: 1 },
        { n: B.humain, c: 1 }, { n: B.eco, c: 1 }, { n: B.asil, c: 1 },
        { n: B.techno, c: 1 }, { n: B.pro, c: 1 }
      ]
    }
  };

  /* ــــ المسالك المفتوحة (الملحق رقم 1) وارتباط كل مسلك بفئته ــــ */
  var TRACKS = [
    { name: 'التعليم الابتدائي — ثنائي اللغة',              cat: 1 },
    { name: 'التعليم الابتدائي — اللغة الأمازيغية',          cat: 2 },
    { name: 'التعليم الثانوي — اللغة العربية',               cat: 3 },
    { name: 'التعليم الثانوي — الفلسفة',                     cat: 3 },
    { name: 'التعليم الثانوي — الدراسات الإسلامية',          cat: 3 },
    { name: 'التعليم الثانوي — التاريخ والجغرافيا',          cat: 3 },
    { name: 'التعليم الثانوي — اللغة الفرنسية',              cat: 4 },
    { name: 'التعليم الثانوي — اللغة الإنجليزية',            cat: 4 },
    { name: 'التعليم الثانوي — العلوم الاقتصادية والتدبير',  cat: 5 },
    { name: 'التعليم الثانوي — العلوم الصناعية للمهندس',     cat: 6 },
    { name: 'التعليم الثانوي — الرياضيات',                   cat: 7 },
    { name: 'التعليم الثانوي — الفيزياء والكيمياء',          cat: 7 },
    { name: 'التعليم الثانوي — علوم الحياة والأرض',          cat: 7 },
    { name: 'التعليم الثانوي — المعلوميات',                  cat: 7 },
    { name: 'التعليم الثانوي — التربية البدنية والرياضة',    cat: 8 }
  ];

  var $ = function (id) { return document.getElementById(id); };
  var trackSel = $('track'), branchSel = $('branch'), nat = $('national'), reg = $('regional');
  var calcBtn = $('calc'), resetBtn = $('reset'), result = $('result');
  var errBox = $('calc-error'), trackHint = $('track-hint'), branchNote = $('branch-note');

  TRACKS.forEach(function (t, i) {
    var o = document.createElement('option');
    o.value = String(i);
    o.textContent = t.name;
    trackSel.appendChild(o);
  });

  function currentTrack() {
    return trackSel.value === '' ? null : TRACKS[Number(trackSel.value)];
  }
  function currentCategory() {
    var t = currentTrack();
    return t ? CATEGORIES[t.cat] : null;
  }
  function currentBranch() {
    var cat = currentCategory();
    if (!cat) return undefined;
    return cat.branches.filter(function (b) { return b.n === branchSel.value; })[0];
  }

  function refreshBranches() {
    var cat = currentCategory();
    var track = currentTrack();

    branchSel.innerHTML = '';
    branchNote.hidden = true;

    if (!cat) {
      branchSel.disabled = true;
      branchSel.innerHTML = '<option value="">اختر المسلك أولًا</option>';
      trackHint.hidden = true;
      return;
    }

    trackHint.textContent = 'الفئة ' + track.cat + ' في الملحق: ' + cat.title;
    trackHint.hidden = false;

    branchSel.disabled = false;
    branchSel.innerHTML = '<option value="">— اختر الشعبة —</option>';
    cat.branches.forEach(function (b) {
      var o = document.createElement('option');
      o.value = b.n;
      o.textContent = b.n + ' — معامل ' + b.c;
      branchSel.appendChild(o);
    });

    if (cat.note) {
      branchNote.textContent = cat.note;
      branchNote.hidden = false;
    }
  }

  /** رسالة خطأ إن كانت النقط خارج المجال المسموح، أو '' إن كان كل شيء سليمًا */
  function validationError() {
    var fields = [
      { el: nat, label: 'نقطة الامتحان الوطني' },
      { el: reg, label: 'نقطة الامتحان الجهوي' }
    ];
    for (var i = 0; i < fields.length; i++) {
      var raw = fields[i].el.value;
      if (raw === '') continue;
      var v = Number(raw);
      if (isNaN(v) || v < 0 || v > 20) {
        return fields[i].label + ' يجب أن تكون عددًا بين 0 و 20.';
      }
    }
    return '';
  }

  function canCompute() {
    return !!currentTrack() && !!currentBranch() && nat.value !== '' && reg.value !== '' && validationError() === '';
  }

  function update() {
    var err = validationError();
    errBox.textContent = err;
    errBox.hidden = err === '';
    calcBtn.disabled = !canCompute();
    result.hidden = true;
  }

  trackSel.addEventListener('change', function () { refreshBranches(); update(); });
  branchSel.addEventListener('change', update);
  nat.addEventListener('input', update);
  reg.addEventListener('input', update);

  calcBtn.addEventListener('click', function () {
    if (!canCompute()) return;

    var n = Number(nat.value), r = Number(reg.value);
    var branch = currentBranch(), track = currentTrack();
    var base = n * 0.75 + r * 0.25;
    var sel = base * branch.c;

    $('score').textContent = sel.toFixed(2);
    $('msg').textContent = sel >= 20 ? 'نتيجة ممتازة! 🎉' : 'بالتوفيق في مباراتك 💪';
    $('steps').innerHTML =
      '<li>الوطني × 0.75 = ' + n + ' × 0.75 = <b>' + (n * 0.75).toFixed(2) + '</b></li>' +
      '<li>الجهوي × 0.25 = ' + r + ' × 0.25 = <b>' + (r * 0.25).toFixed(2) + '</b></li>' +
      '<li>المجموع = <b>' + base.toFixed(2) + '</b></li>' +
      '<li>× معامل الترجيح (' + branch.c + ') = <b class="text-primary">' + sel.toFixed(2) + '</b></li>';

    $('result-ref').innerHTML =
      'المعامل <b>' + branch.c + '</b> مأخوذ من <b>الفئة ' + track.cat + '</b> في الملحق رقم 2 ' +
      '(<?= e(REF_YEAR) ?>)، عند تقاطع مسلك «' + track.name + '» مع «' + branch.n + '».';

    result.hidden = false;
    result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });

  resetBtn.addEventListener('click', function () {
    trackSel.value = '';
    refreshBranches();
    nat.value = '';
    reg.value = '';
    update();
  });
})();
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
