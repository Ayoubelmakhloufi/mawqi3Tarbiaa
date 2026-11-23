<?php
/**
 * مخطّط المراجعة — من اليوم إلى يوم الامتحان.
 *
 * يُدخل المترشح تاريخ امتحانه وساعاته المتاحة، فيحصل على جدول أسبوعي
 * يوزّع محاور الموقع ويُدرج مراجعة متباعدة ومحاكاة في الأسابيع الأخيرة.
 *
 * ⚠️ المحاور تُقرأ من data/ikhtibarat.json لا من قائمة مكتوبة هنا:
 * أي بنك أسئلة جديد يدخل الخطة وحده بلا تعديل هذا الملف.
 *
 * كل الحساب داخل المتصفح؛ لا تُرسَل بيانات ولا تُخزَّن.
 */

require __DIR__ . '/partials/lib.php';

$quizzes = tb_data('ikhtibarat')['quizzes'] ?? [];

/* «الشامل» ليس محورًا يُدرَس بل محاكاة تُستعمل في الأسابيع الأخيرة */
$axes = [];
foreach ($quizzes as $z) {
    if ($z['slug'] === 'chamil') {
        continue;
    }
    $axes[] = [
        's' => $z['slug'],
        't' => $z['title'],
        'n' => count($z['qs']),
    ];
}

$nQ = array_sum(array_map(static fn (array $z): int => count($z['qs']), $quizzes));

$meta = [
    'title'       => 'مخطّط المراجعة — جدول أسبوعي حتى يوم المباراة',
    'description' => 'أدخل تاريخ امتحانك وساعاتك المتاحة، واحصل على جدول مراجعة أسبوعي '
                   . 'يوزّع المحاور ويُدرج المراجعة المتباعدة والمحاكاة. مجانًا وبلا تسجيل.',
    'canonical'   => '/mokhattat-almourajaa',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'مخطّط المراجعة', 'url' => '/mokhattat-almourajaa'],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'WebApplication',
        'name'        => 'مخطّط المراجعة',
        'url'         => tb_abs('/mokhattat-almourajaa'),
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem'     => 'أي متصفح',
        'inLanguage'  => 'ar',
        'offers'      => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'MAD'],
        'publisher'   => ['@id' => SITE_URL . '/#organization'],
    ]],
];

require __DIR__ . '/partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden no-print">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-5">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-accent transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/adawat/" class="hover:text-accent transition-colors">الأدوات</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">مخطّط المراجعة</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-4xl font-black leading-tight mb-3">مخطّط المراجعة</h1>
      <p class="text-lg text-slate-300 leading-relaxed mb-5">
        أدخل <b>تاريخ امتحانك</b> و<b>ساعاتك المتاحة</b>، فتحصل على جدول أسبوعي يوزّع
        <b><?= count($axes) ?> محاور</b> على المدة المتبقية، ويُدرج <b>المراجعة المتباعدة</b>
        والمحاكاة في الأسابيع الأخيرة.
      </p>
      <div class="flex flex-wrap gap-3 text-sm font-bold">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-repeat"></i> مراجعة متباعدة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-print"></i> قابل للطباعة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-lock"></i> بلا تسجيل</span>
      </div>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14"
     x-data="planner(<?= e(json_encode($axes, JSON_UNESCAPED_UNICODE)) ?>)">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto">

      <!-- المدخلات -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6 no-print">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
          <i class="fas fa-sliders text-accent"></i> معطياتك
        </h2>
        <div class="grid gap-4 md:grid-cols-3">
          <label class="block">
            <span class="block text-sm font-bold text-slate-600 mb-2">تاريخ الامتحان</span>
            <input type="date" x-model="examDate"
                   class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
          </label>
          <label class="block">
            <span class="block text-sm font-bold text-slate-600 mb-2">ساعات في اليوم</span>
            <input type="number" x-model.number="hours" min="1" max="12" step="0.5"
                   class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
          </label>
          <label class="block">
            <span class="block text-sm font-bold text-slate-600 mb-2">أيام في الأسبوع</span>
            <input type="number" x-model.number="days" min="1" max="7"
                   class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
          </label>
        </div>

        <p x-show="error" x-cloak class="mt-5 rounded-xl bg-amber-50 ring-1 ring-amber-200 text-amber-800 px-4 py-3 font-bold text-sm" x-text="error"></p>

        <button type="button" @click="print()" x-show="!error && weeks > 0" x-cloak
                class="w-full mt-6 bg-primary text-white py-4 rounded-xl font-black text-lg hover:bg-secondary transition-all shadow-lg shadow-primary/20">
          <i class="fas fa-print ml-2"></i> اطبع الجدول
        </button>
      </div>

      <template x-if="!error && weeks > 0">
        <div>
          <!-- الخلاصة -->
          <div class="rounded-3xl overflow-hidden ring-1 ring-slate-200 shadow-sm mb-6">
            <div class="bg-primary text-center px-6 py-8">
              <p class="text-white/70 font-medium mb-1">أمامك</p>
              <p class="my-2">
                <span class="text-5xl md:text-6xl font-black text-gold" x-text="weeks"></span>
                <span class="text-xl font-bold text-white/60"> أسبوعًا</span>
              </p>
              <p class="text-white/80 font-bold">
                <span x-text="totalHours"></span> ساعة إجمالًا ·
                <span x-text="hours * days"></span> ساعة أسبوعيًا
              </p>
            </div>
            <div class="bg-white p-6 article-body">
              <p x-text="verdict"></p>
            </div>
          </div>

          <!-- الجدول -->
          <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body">
            <h2>جدول المراجعة</h2>
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>الأسبوع</th>
                    <th>المرحلة</th>
                    <th>المحور الجديد</th>
                    <th>مراجعة متباعدة</th>
                    <th>التدريب</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="w in plan" :key="w.i">
                    <tr>
                      <td>
                        <b x-text="'الأسبوع ' + w.i"></b><br>
                        <span class="text-xs text-slate-500" x-text="w.from + ' — ' + w.to"></span>
                      </td>
                      <td x-text="w.phase"></td>
                      <td x-html="w.newHtml"></td>
                      <td x-html="w.reviewHtml"></td>
                      <td x-html="w.drillHtml"></td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>

            <h3>كيف تقضي ساعات الأسبوع؟</h3>
            <div class="table-wrap">
              <table>
                <thead><tr><th>النشاط</th><th>الحصة</th><th>لماذا</th></tr></thead>
                <tbody>
                  <tr><td>قراءة المحور الجديد</td><td x-text="split.read + ' ساعة'"></td><td>الفهم أولًا، والقراءة السريعة لا تكفي</td></tr>
                  <tr><td>بطاقات المراجعة</td><td x-text="split.cards + ' ساعة'"></td><td>الاسترجاع من الذاكرة يثبّت أقوى من إعادة القراءة</td></tr>
                  <tr><td>الاختبارات وتحليل الأخطاء</td><td x-text="split.quiz + ' ساعة'"></td><td>يكشف ما تظنّ أنك تعرفه ولا تعرفه فعلًا</td></tr>
                </tbody>
              </table>
            </div>

            <div class="note-ok">
              <b>القاعدة التي تصنع الفرق:</b> لا تُعِد قراءة الملخص. بعد كل محور،
              أغلق النص و<b>استرجع من ذاكرتك</b> ثم تحقّق. والمراجعة بعد <b>يومين</b>
              لا بعد ساعة — التباعد هو ما يثبّت.
            </div>
          </div>
        </div>
      </template>

      <div class="mt-10 bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body no-print">
        <h2>لماذا هذا الترتيب بالذات؟</h2>
        <ol>
          <li><b>نظريات التعلم أولًا</b> حيثما أمكن — لأنها تفسّر البيداغوجيات بعدها،
              ولا تُفهم البيداغوجيا معزولة عن أساسها النظري.</li>
          <li><b>محور جديد + مراجعة قديم في الأسبوع نفسه.</b> هذا هو التباعد عمليًا:
              تعود إلى ما درستَه قبل أسبوعين حين يكون على وشك التلاشي، فيقوى أثره.</li>
          <li><b>المحاكاة في النهاية لا في البداية.</b> الاختبار الشامل قبل بناء الأساس
              يعطي نتيجة محبِطة بلا فائدة تشخيصية.</li>
          <li><b>المؤقّت في الأسابيع الأخيرة فقط</b> — تتعوّد ضغط الزمن بعد أن تتقن المضمون.</li>
        </ol>
        <div class="note-info">
          الجدول <b>اقتراح لا وصفة</b>. إن تعثّرت في محور فامنحه أسبوعًا إضافيًا،
          وأعِد توليد الجدول بتاريخ جديد. والالتزام بجدول واقعي أنفع من جدول مثالي يُهجر في أسبوعه الثاني.
        </div>
      </div>

    </div>
  </div>
</div>

<script>
/**
 * مخطّط المراجعة — كل الحساب داخل المتصفح.
 * @param {Array} axes محاور الموقع، مقروءة من بنوك الأسئلة.
 */
function planner(axes) {
  var MONTHS = ['يناير', 'فبراير', 'مارس', 'أبريل', 'ماي', 'يونيو',
                'يوليوز', 'غشت', 'شتنبر', 'أكتوبر', 'نونبر', 'دجنبر'];

  /** تاريخ عربي مختصر: 12 غشت */
  function fmt(d) { return d.getDate() + ' ' + MONTHS[d.getMonth()]; }

  function quizLink(a) {
    return '<a href="/ikhtibarat/' + a.s + '/">' + a.t + '</a>';
  }

  return {
    axes: axes,
    examDate: '',
    hours: 2,
    days: 5,

    /** الأيام المتبقية (قد تكون سالبة لتاريخ ماضٍ)، أو null إن كان التاريخ غير صالح */
    get daysLeft() {
      if (!this.examDate) { return null; }
      var exam = new Date(this.examDate + 'T00:00:00');
      if (isNaN(exam.getTime())) { return null; }
      var today = new Date();
      today.setHours(0, 0, 0, 0);

      return Math.round((exam - today) / 86400000);
    },

    get weeks() {
      var d = this.daysLeft;

      return d !== null && d > 0 ? Math.max(1, Math.ceil(d / 7)) : 0;
    },

    get totalHours() {
      return Math.round(this.weeks * this.hours * this.days);
    },

    get error() {
      if (!this.examDate) { return 'أدخل تاريخ الامتحان لتظهر الخطة.'; }
      if (this.daysLeft === null) { return 'التاريخ غير صالح.'; }
      if (this.daysLeft < 0) { return 'هذا التاريخ في الماضي — أدخل تاريخًا قادمًا.'; }
      if (this.daysLeft === 0) { return 'امتحانك اليوم — لا مجال لخطة. راجع أخطاءك السابقة فقط ونم مبكرًا.'; }
      if (!this.hours || this.hours <= 0) { return 'أدخل عدد ساعات أكبر من صفر.'; }
      if (!this.days || this.days <= 0 || this.days > 7) { return 'أيام الأسبوع بين 1 و7.'; }

      return '';
    },

    get verdict() {
      var w = this.weeks;
      var perWeek = this.hours * this.days;
      if (w >= 12) { return 'مدة مريحة. لا تستعجل: افهم كل محور جيدًا قبل الانتقال، فالأساس المتين يوفّر عليك إعادة كل شيء لاحقًا.'; }
      if (w >= 6) { return 'مدة معقولة إن التزمت. الأولوية للمحاور الأساسية وللاختبارات — لا لجمع مزيد من الملخصات.'; }
      if (w >= 3) { return 'المدة ضيقة. ركّز على الاسترجاع والاختبارات أكثر من القراءة، وتجاوز التفاصيل الثانوية.';
      }
      if (perWeek < 6) { return 'المدة قصيرة والساعات قليلة. اقتصر على الاختبارات وبطاقات المراجعة — هما أعلى مردود لكل ساعة.'; }

      return 'المدة قصيرة جدًا. لا تحاول تغطية كل شيء: اختبر نفسك أولًا لتكشف ثغراتك، ثم عالج الأضعف فقط.';
    },

    /** توزيع ساعات الأسبوع بين القراءة والبطاقات والاختبارات */
    get split() {
      var per = this.hours * this.days;
      var tight = this.weeks < 4;
      var read = tight ? 0.3 : 0.5;
      var cards = tight ? 0.3 : 0.25;

      return {
        read:  Math.max(0.5, Math.round(per * read * 10) / 10),
        cards: Math.max(0.5, Math.round(per * cards * 10) / 10),
        quiz:  Math.max(0.5, Math.round(per * (1 - read - cards) * 10) / 10)
      };
    },

    /** الخطة الأسبوعية: بناء ← ترسيخ ← محاكاة */
    get plan() {
      var w = this.weeks;
      if (w <= 0) { return []; }

      var n = this.axes.length;
      /* المحاكاة تأخذ آخر أسبوع أو أسبوعين، والباقي للبناء والترسيخ */
      var sim = w >= 6 ? 2 : (w >= 3 ? 1 : 0);
      var build = Math.max(1, w - sim);
      var perWeek = Math.ceil(n / build);

      var start = new Date();
      start.setHours(0, 0, 0, 0);

      var rows = [];
      var done = [];   /* المحاور المدروسة، للمراجعة المتباعدة */
      var k = 0;

      for (var i = 1; i <= w; i++) {
        var from = new Date(start.getTime() + (i - 1) * 7 * 86400000);
        var to = new Date(Math.min(
          start.getTime() + (i * 7 - 1) * 86400000,
          start.getTime() + this.daysLeft * 86400000
        ));

        var isSim = i > build;
        var fresh = [];

        if (!isSim) {
          for (var j = 0; j < perWeek && k < n; j++, k++) { fresh.push(this.axes[k]); }
        }

        /* المراجعة المتباعدة: ما دُرِس قبل أسبوعين تقريبًا */
        var back = done.slice(Math.max(0, done.length - perWeek * 2), Math.max(0, done.length - perWeek));
        if (isSim) { back = done.slice(-perWeek * 2); }

        rows.push({
          i: i,
          from: fmt(from),
          to: fmt(to),
          phase: isSim ? 'محاكاة' : (i <= Math.ceil(build * 0.7) ? 'بناء' : 'ترسيخ'),
          newHtml: fresh.length
            ? fresh.map(quizLink).join('<br>')
            : '<span class="text-slate-400">— مراجعة فقط</span>',
          reviewHtml: back.length
            ? back.map(function (a) { return a.t; }).join(' · ')
            : '<a href="/bitaqat/">بطاقات المراجعة</a>',
          drillHtml: isSim
            ? '<a href="/ikhtibarat/chamil/">الاختبار الشامل</a> و<a href="/ikhtibarat/achwai/">العشوائي</a><br><b>مع المؤقّت</b>'
            : (fresh.length
              ? 'اختبار كل محور + <a href="/bitaqat/">بطاقات</a>'
              : '<a href="/ikhtibarat/achwai/">اختبار عشوائي</a>')
        });

        fresh.forEach(function (a) { done.push(a); });
      }

      return rows;
    },

    print() { window.print(); }
  };
}
</script>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
