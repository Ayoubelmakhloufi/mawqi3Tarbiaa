<?php
require __DIR__ . '/partials/lib.php';

/** دورة العتبات المرجعية المعروضة في الحاسبة. */
const CRMEF_REF_SESSION = 'نونبر 2025';

$meta = [
    'title'       => 'حاسبة نقطة الانتقاء الأولي لمباراة المراكز الجهوية CRMEF',
    'description' => 'احسب نقطة انتقائك الأولي لمباراة الولوج إلى المراكز الجهوية لمهن التربية والتكوين على 80، وقارنها بعتبات دورة نونبر 2025 حسب المسلك والتخصص.',
    'canonical'   => '/hisab-intiqa-crmef',
    'image'       => '/assets/img/og-adawat.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'حاسبة الانتقاء الأولي للمراكز الجهوية', 'url' => '/hisab-intiqa-crmef'],
    ],
    'jsonld'      => [[
        '@context'            => 'https://schema.org',
        '@type'               => 'WebApplication',
        'name'                => 'حاسبة نقطة الانتقاء الأولي لمباراة المراكز الجهوية',
        'url'                 => tb_abs('/hisab-intiqa-crmef'),
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem'     => 'All',
        'inLanguage'          => 'ar',
        'description'         => 'أداة مجانية لحساب نقطة الانتقاء الأولي لمباراة الولوج إلى المراكز الجهوية لمهن التربية والتكوين بالمغرب.',
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
        <li class="text-white/80" aria-current="page">الانتقاء الأولي CRMEF</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">حاسبة نقطة الانتقاء الأولي — المراكز الجهوية</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-6">
        احسب نقطتك على <b>80</b> حسب المعايير الرسمية الثلاثة، وقارنها فورًا بعتبات
        <b>دورة <?= e(CRMEF_REF_SESSION) ?></b> في مسلكك وتخصصك.
      </p>
      <p class="inline-flex flex-wrap items-center gap-2 bg-white/10 text-accent rounded-2xl px-5 py-3 font-bold text-sm md:text-base">
        <i class="fas fa-wand-magic-sparkles"></i>
        ميزة الباكالوريا (25) + ميزة الإجازة (30) + السن عند الإجازة (25)
      </p>
    </div>
  </div>
</section>

<div class="container mx-auto px-4 md:px-6 py-12 md:py-16">
  <div class="grid gap-8 lg:grid-cols-5">

    <div class="lg:col-span-3 space-y-6">
      <!-- تنبيه أساسي قبل النموذج -->
      <div class="article-body">
        <div class="note">
          <b>اقرأ هذا قبل أن تحسب.</b> نقطة الانتقاء تُحتسب بمعادلة رسمية ثابتة،
          لكن <b>النجاح في الانتقاء ليس رقمًا ثابتًا</b>: العتبة تتحدد <b>تنافسيًا</b> بعد ترتيب كل
          المترشحين، وتتوقف على <b>عدد المترشحين ونقطهم وعدد المناصب</b> في تخصصك تلك السنة.
          العتبات المعروضة هنا <b>مرجع تقريبي من دورة سابقة</b> ولا تُلزم أي دورة قادمة.
        </div>
      </div>

      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 shadow-sm">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
          <i class="fas fa-calculator text-accent"></i> أدخل معطياتك
        </h2>

        <div class="space-y-5">
          <div>
            <label for="bac" class="block font-bold text-slate-700 mb-2">١. الميزة المحصّل عليها في شهادة الباكالوريا</label>
            <select id="bac" class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
              <option value="">— اختر الميزة —</option>
            </select>
          </div>

          <div>
            <label for="lic" class="block font-bold text-slate-700 mb-2">٢. الميزة المحصّل عليها في شهادة الإجازة</label>
            <select id="lic" class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
              <option value="">— اختر الميزة —</option>
            </select>
          </div>

          <div>
            <label for="age" class="block font-bold text-slate-700 mb-2">٣. سنّك عند حصولك على شهادة الإجازة</label>
            <select id="age" class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
              <option value="">— اختر السن —</option>
            </select>
          </div>

          <hr class="border-slate-100">

          <div>
            <label for="track" class="block font-bold text-slate-700 mb-2">٤. المسلك والتخصص <span class="font-medium text-slate-400">(اختياري — للمقارنة بعتبة <?= e(CRMEF_REF_SESSION) ?>)</span></label>
            <select id="track" class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
              <option value="">— بدون مقارنة —</option>
            </select>
          </div>

          <div class="flex gap-3 pt-1">
            <button type="button" id="calc" disabled
                    class="flex-1 bg-primary text-white py-4 rounded-xl font-black hover:bg-secondary disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-lg shadow-primary/20">
              احسب نقطة الانتقاء
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
          <p class="text-white/70 font-medium">نقطة الانتقاء الأولي</p>
          <p class="my-2"><span class="text-6xl md:text-7xl font-black text-gold" id="score">0</span><span class="text-2xl font-bold text-white/50"> / 80</span></p>
          <p id="msg" class="font-bold"></p>
        </div>
        <div class="bg-white p-6 md:p-8">
          <h3 class="font-black text-primary flex items-center gap-2 mb-4"><i class="fas fa-circle-info text-accent"></i> تفصيل النقط</h3>
          <ol id="steps" class="space-y-2.5 pr-5 list-decimal marker:text-accent marker:font-black text-slate-700"></ol>

          <div id="cmp" hidden class="mt-6 rounded-2xl p-5 ring-1"></div>

          <div class="mt-6 bg-slate-50 ring-1 ring-slate-200 rounded-2xl p-5 article-body">
            <div class="note">
              <b>النتيجة تقديرية.</b> نقطتك على 80 تُحتسب بالمعادلة الرسمية، لكن قبولك يتوقف على
              <b>ترتيبك بين كل المترشحين</b> في تخصصك، وعلى <b>عدد المناصب المفتوحة</b> في الدورة المعنية.
              عتبة سنة ماضية قد ترتفع أو تنخفض بشكل كبير في السنة الموالية.
            </div>
          </div>

          <div class="mt-4 flex flex-wrap gap-3">
            <a href="<?= e(PLAY_URL) ?>" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 bg-primary text-white px-5 py-3 rounded-xl font-bold hover:bg-secondary transition-colors">
              <i class="fab fa-google-play"></i> تدرّب على تطبيق تربية
            </a>
            <a href="/maqalat/kayfa-tastaid-mobarat-attaalim"
               class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 px-5 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors">
              خطة الاستعداد للمباراة
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- الشرح -->
    <aside class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-7 shadow-sm">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-4"><i class="fas fa-list-check text-accent"></i> سُلّم النقط الرسمي</h2>
        <div class="article-body text-[15px]">
          <p><b>1. ميزة الباكالوريا</b></p>
          <div class="table-wrap">
            <table><tbody>
              <tr><td>حسن جدًا</td><td class="font-black text-primary">25</td></tr>
              <tr><td>حسن</td><td class="font-black text-primary">20</td></tr>
              <tr><td>لا بأس به</td><td class="font-black text-primary">10</td></tr>
              <tr><td>مقبول أو بدون ميزة</td><td class="font-black text-slate-400">0</td></tr>
            </tbody></table>
          </div>

          <p><b>2. ميزة الإجازة</b></p>
          <div class="table-wrap">
            <table><tbody>
              <tr><td>حسن جدًا</td><td class="font-black text-primary">30</td></tr>
              <tr><td>حسن</td><td class="font-black text-primary">25</td></tr>
              <tr><td>لا بأس به</td><td class="font-black text-primary">15</td></tr>
              <tr><td>مقبول أو بدون ميزة</td><td class="font-black text-slate-400">0</td></tr>
            </tbody></table>
          </div>

          <p><b>3. السن عند الحصول على الإجازة</b></p>
          <div class="table-wrap">
            <table><tbody>
              <tr><td>22 سنة أو أقل</td><td class="font-black text-primary">25</td></tr>
              <tr><td>23 سنة</td><td class="font-black text-primary">15</td></tr>
              <tr><td>24 سنة</td><td class="font-black text-primary">5</td></tr>
              <tr><td>أكثر من 24 سنة</td><td class="font-black text-slate-400">0</td></tr>
            </tbody></table>
          </div>

          <div class="note-ok">
            <b>المجموع الأقصى = 80 نقطة.</b> الهدف المعلن من هذه المعايير هو انتقاء المترشحين
            الذين قطعوا <b>مسارًا تعليميًا ناجحًا ومنتظمًا</b>.
          </div>
        </div>
      </div>

      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-7 shadow-sm">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-2"><i class="fas fa-chart-simple text-accent"></i> عتبات دورة <?= e(CRMEF_REF_SESSION) ?></h2>
        <p class="text-sm text-slate-500 mb-4">للاستئناس فقط — لا تُلزم أي دورة قادمة.</p>
        <div class="article-body text-[15px]">
          <div class="table-wrap">
            <table id="thresholds">
              <thead><tr><th>التخصص</th><th>العتبة</th></tr></thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="note-info">
            <b>لماذا بعض العتبات = 0؟</b> لأن عدد المترشحين في ذلك التخصص كان أقل من أو يساوي
            عدد المناصب، فقُبل الجميع. وهذا يتغيّر جذريًا من سنة لأخرى.
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php require __DIR__ . '/partials/app-cta.php'; ?>

<script>
/**
 * حاسبة نقطة الانتقاء الأولي — مباراة الولوج إلى المراكز الجهوية لمهن التربية والتكوين.
 * سُلّم النقط منقول من الوثيقة الرسمية لوزارة التربية الوطنية.
 * العتبات من محضر التحقق والمصادقة على نتائج الانتقاء الأولي — دورة نونبر 2025.
 */
(function () {
  'use strict';

  var BAC = [
    { n: 'حسن جدًا', p: 25 }, { n: 'حسن', p: 20 },
    { n: 'لا بأس به', p: 10 }, { n: 'مقبول أو بدون ميزة', p: 0 }
  ];
  var LIC = [
    { n: 'حسن جدًا', p: 30 }, { n: 'حسن', p: 25 },
    { n: 'لا بأس به', p: 15 }, { n: 'مقبول أو بدون ميزة', p: 0 }
  ];
  var AGE = [
    { n: '22 سنة أو أقل', p: 25 }, { n: '23 سنة', p: 15 },
    { n: '24 سنة', p: 5 }, { n: 'أكثر من 24 سنة', p: 0 }
  ];

  /* عتبات دورة نونبر 2025 حسب المسلك والتخصص */
  var TRACKS = [
    { g: 'التعليم الابتدائي', s: 'المزدوج', t: 60 },
    { g: 'التعليم الابتدائي', s: 'اللغة الأمازيغية', t: 5 },

    { g: 'الثانوي الإعدادي', s: 'اللغة العربية', t: 5 },
    { g: 'الثانوي الإعدادي', s: 'اللغة الإنجليزية', t: 15 },
    { g: 'الثانوي الإعدادي', s: 'التربية الإسلامية', t: 35 },
    { g: 'الثانوي الإعدادي', s: 'التربية البدنية والرياضية', t: 35 },
    { g: 'الثانوي الإعدادي', s: 'الاجتماعيات', t: 15 },
    { g: 'الثانوي الإعدادي', s: 'المعلوميات', t: 0 },
    { g: 'الثانوي الإعدادي', s: 'الفيزياء والكيمياء', t: 0 },
    { g: 'الثانوي الإعدادي', s: 'علوم الحياة والأرض', t: 0 },
    { g: 'الثانوي الإعدادي', s: 'التكنولوجيا', t: 65 },

    { g: 'الثانوي التأهيلي', s: 'اللغة العربية', t: 0 },
    { g: 'الثانوي التأهيلي', s: 'اللغة الإنجليزية', t: 40 },
    { g: 'الثانوي التأهيلي', s: 'التربية الإسلامية', t: 40 },
    { g: 'الثانوي التأهيلي', s: 'التربية البدنية والرياضية', t: 50 },
    { g: 'الثانوي التأهيلي', s: 'التاريخ والجغرافيا', t: 15 },
    { g: 'الثانوي التأهيلي', s: 'المعلوميات', t: 55 },
    { g: 'الثانوي التأهيلي', s: 'الفيزياء والكيمياء', t: 0 },
    { g: 'الثانوي التأهيلي', s: 'علوم الحياة والأرض', t: 0 },
    { g: 'الثانوي التأهيلي', s: 'علوم الاقتصاد والتدبير', t: 85 },
    { g: 'الثانوي التأهيلي', s: 'الفلسفة', t: 40 },

    { g: 'الإعدادي والتأهيلي معًا', s: 'الرياضيات', t: 0 },
    { g: 'الإعدادي والتأهيلي معًا', s: 'الفرنسية', t: 25 }
  ];

  var $ = function (id) { return document.getElementById(id); };
  var bac = $('bac'), lic = $('lic'), age = $('age'), track = $('track');
  var calcBtn = $('calc'), resetBtn = $('reset'), result = $('result');

  function fill(sel, arr) {
    arr.forEach(function (o, i) {
      var el = document.createElement('option');
      el.value = String(i);
      el.textContent = o.n + ' — ' + o.p + ' نقطة';
      sel.appendChild(el);
    });
  }
  fill(bac, BAC); fill(lic, LIC); fill(age, AGE);

  /* قائمة المسالك مجمّعة */
  var groups = {};
  TRACKS.forEach(function (t, i) {
    (groups[t.g] = groups[t.g] || []).push({ i: i, t: t });
  });
  Object.keys(groups).forEach(function (g) {
    var og = document.createElement('optgroup');
    og.label = g;
    groups[g].forEach(function (x) {
      var el = document.createElement('option');
      el.value = String(x.i);
      el.textContent = x.t.s;
      og.appendChild(el);
    });
    track.appendChild(og);
  });

  /* جدول العتبات في الجانب */
  var tb = document.querySelector('#thresholds tbody');
  Object.keys(groups).forEach(function (g) {
    var hr = document.createElement('tr');
    hr.innerHTML = '<td colspan="2" class="bg-slate-100 font-black">' + g + '</td>';
    tb.appendChild(hr);
    groups[g].forEach(function (x) {
      var r = document.createElement('tr');
      r.innerHTML = '<td>' + x.t.s + '</td><td class="font-black ' +
        (x.t.t === 0 ? 'text-slate-400' : 'text-primary') + '">' + x.t.t + '</td>';
      tb.appendChild(r);
    });
  });

  function ready() {
    return bac.value !== '' && lic.value !== '' && age.value !== '';
  }
  function update() {
    calcBtn.disabled = !ready();
    result.hidden = true;
  }
  [bac, lic, age, track].forEach(function (el) { el.addEventListener('change', update); });

  calcBtn.addEventListener('click', function () {
    if (!ready()) return;

    var b = BAC[Number(bac.value)], l = LIC[Number(lic.value)], a = AGE[Number(age.value)];
    var total = b.p + l.p + a.p;

    $('score').textContent = String(total);
    $('msg').textContent = total >= 60 ? 'نقطة قوية 💪' : (total >= 35 ? 'نقطة متوسطة' : 'نقطة منخفضة');
    $('steps').innerHTML =
      '<li>ميزة الباكالوريا (' + b.n + ') = <b>' + b.p + '</b></li>' +
      '<li>ميزة الإجازة (' + l.n + ') = <b>' + l.p + '</b></li>' +
      '<li>السن عند الإجازة (' + a.n + ') = <b>' + a.p + '</b></li>' +
      '<li>المجموع = <b class="text-primary">' + total + ' / 80</b></li>';

    var cmp = $('cmp');
    if (track.value === '') {
      cmp.hidden = true;
    } else {
      var t = TRACKS[Number(track.value)];
      var pass = total >= t.t;
      cmp.hidden = false;
      cmp.className = 'mt-6 rounded-2xl p-5 ring-1 ' +
        (pass ? 'bg-emerald-50 ring-emerald-200' : 'bg-amber-50 ring-amber-200');
      cmp.innerHTML =
        '<p class="font-black mb-2 ' + (pass ? 'text-emerald-800' : 'text-amber-800') + '">' +
        (pass ? '✅ نقطتك تفوق عتبة الدورة المرجعية' : '⚠️ نقطتك دون عتبة الدورة المرجعية') + '</p>' +
        '<p class="text-sm leading-relaxed ' + (pass ? 'text-emerald-900' : 'text-amber-900') + '">' +
        '<b>' + t.g + ' — ' + t.s + '</b><br>' +
        'عتبة دورة نونبر 2025: <b>' + t.t + '</b> · نقطتك: <b>' + total + '</b><br><br>' +
        'هذه <b>مقارنة استئناسية فقط</b>. عتبة الدورة القادمة تتحدد بعدد المترشحين ونقطهم ' +
        'وعدد المناصب المفتوحة، وقد ترتفع أو تنخفض بفارق كبير.</p>';
    }

    result.hidden = false;
    result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });

  resetBtn.addEventListener('click', function () {
    bac.value = ''; lic.value = ''; age.value = ''; track.value = '';
    update();
  });
})();
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
