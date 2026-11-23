<?php
/**
 * مولّد شبكة التقويم بالمعايير — أداة داخل المتصفح.
 *
 * تُنتج شبكة جاهزة للطباعة: معايير × مستويات تحكّم، يملؤها الأستاذ بخط اليد
 * أثناء التصحيح. والمعايير الافتراضية هي معايير روجيرس المعتمدة في المقاربة
 * بالكفايات: ثلاثة معايير حدّ أدنى ومعيار إتقان واحد.
 *
 * لا تُرسَل أي بيانات ولا تُخزَّن.
 */

require __DIR__ . '/partials/lib.php';

$meta = [
    'title'       => 'مولّد شبكة التقويم بالمعايير — جاهزة للطباعة',
    'description' => 'أداة مجانية لبناء شبكة تصحيح بالمعايير والمؤشرات ومستويات التحكّم، '
                   . 'جاهزة للطباعة. تعتمد معايير الحد الأدنى ومعيار الإتقان. داخل متصفحك بلا تسجيل.',
    'canonical'   => '/chabakat-taqwim',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'مولّد شبكة التقويم', 'url' => '/chabakat-taqwim'],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'WebApplication',
        'name'        => 'مولّد شبكة التقويم بالمعايير',
        'url'         => tb_abs('/chabakat-taqwim'),
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
        <li class="text-white/80" aria-current="page">شبكة التقويم</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-4xl font-black leading-tight mb-3">مولّد شبكة التقويم بالمعايير</h1>
      <p class="text-lg text-slate-300 leading-relaxed mb-5">
        شبكة تصحيح جاهزة للطباعة: <b>معايير × مستويات تحكّم</b>. تجعل تصحيحك
        <b>ثابتًا لا مزاجيًا</b>، وتجعل النقطة مفهومة للمتعلم وأسرته.
      </p>
      <div class="flex flex-wrap gap-3 text-sm font-bold">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-table-list"></i> معايير روجيرس افتراضيًا</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-print"></i> طباعة مباشرة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-lock"></i> بلا تسجيل</span>
      </div>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14" x-data="chabaka()">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto">

      <div class="no-print">
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
            <i class="fas fa-circle-info text-accent"></i> معطيات الشبكة
          </h2>
          <div class="grid gap-4 sm:grid-cols-2">
            <label class="block">
              <span class="block text-sm font-bold text-slate-600 mb-2">المادة والمستوى</span>
              <input type="text" x-model="f.head" placeholder="اللغة العربية — الثانية إعدادي"
                     class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
            </label>
            <label class="block">
              <span class="block text-sm font-bold text-slate-600 mb-2">المهمة أو الوضعية المقوَّمة</span>
              <input type="text" x-model="f.task" placeholder="تحرير نص حجاجي في وضعية تواصلية"
                     class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
            </label>
          </div>
        </div>

        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-3">
            <i class="fas fa-list-check text-accent"></i> المعايير والمؤشرات
          </h2>
          <p class="text-sm text-slate-500 leading-relaxed mb-6">
            المعيار <b>صفة عامة</b> للجواب، والمؤشر <b>علامة ملموسة</b> تدل على تحققه.
            دون مؤشرات لا يتفق مصححان على النقطة نفسها.
            <b>افصل معيار الإتقان</b> عن معايير الحد الأدنى حتى لا يُحرم متعلم أتقن الأساسي بسبب الشكل.
          </p>

          <template x-for="(c, i) in f.criteria" :key="i">
            <div class="bg-slate-50 rounded-2xl p-5 mb-3">
              <div class="flex items-center gap-2 mb-3">
                <input type="text" :value="c.name" @input="f.criteria[i].name = $event.target.value"
                       placeholder="اسم المعيار"
                       class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 font-bold text-slate-800 focus:border-accent outline-none transition">
                <input type="number" :value="c.pts" @input="f.criteria[i].pts = $event.target.value" min="0" step="0.25"
                       placeholder="نقطة"
                       class="w-20 shrink-0 rounded-xl border-2 border-slate-200 bg-white px-3 py-2.5 font-medium text-slate-800 focus:border-accent outline-none transition">
                <button type="button" @click="f.criteria.splice(i, 1)" x-show="f.criteria.length > 1" aria-label="حذف المعيار"
                        class="w-12 h-12 shrink-0 rounded-xl bg-white text-red-600 hover:bg-slate-100 transition-colors">
                  <i class="fas fa-xmark"></i>
                </button>
              </div>
              <textarea :value="c.ind" @input="f.criteria[i].ind = $event.target.value" rows="2"
                        placeholder="المؤشرات: ما الذي يُلاحَظ فعلًا؟"
                        class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
              <label class="flex items-center gap-2 mt-3 text-sm font-bold text-slate-600">
                <input type="checkbox" :checked="c.perf" @change="f.criteria[i].perf = $event.target.checked"
                       class="w-7 h-7 rounded-lg">
                معيار إتقان (لا يُقصي عند الإخفاق)
              </label>
            </div>
          </template>

          <div class="flex flex-wrap gap-3 mt-4">
            <button type="button" @click="f.criteria.push({ name: '', ind: '', pts: '', perf: false })"
                    class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
              <i class="fas fa-plus text-xs"></i> أضف معيارًا
            </button>
            <button type="button" @click="resetCriteria()"
                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition-colors">
              <i class="fas fa-rotate-right text-xs"></i> استرجع معايير روجيرس
            </button>
          </div>
        </div>

        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-3">
            <i class="fas fa-layer-group text-accent"></i> مستويات التحكّم
          </h2>
          <p class="text-sm text-slate-500 leading-relaxed mb-6">
            صف كل مستوى <b>بعبارة ملموسة</b> لا بنقطة وحدها — هذا ما يجعل الشبكة قابلة للاستعمال
            في التقويم الذاتي والتقويم بالأقران أيضًا.
          </p>
          <template x-for="(l, i) in f.levels" :key="i">
            <div class="flex items-start gap-2 mb-2">
              <input type="text" :value="l.name" @input="f.levels[i].name = $event.target.value"
                     placeholder="المستوى"
                     class="w-20 shrink-0 rounded-xl border-2 border-slate-200 bg-white px-3 py-3 font-bold text-slate-800 focus:border-accent outline-none transition">
              <input type="text" :value="l.desc" @input="f.levels[i].desc = $event.target.value"
                     placeholder="وصف ملموس لهذا المستوى"
                     class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition">
              <button type="button" @click="f.levels.splice(i, 1)" x-show="f.levels.length > 2" aria-label="حذف المستوى"
                      class="w-12 h-12 shrink-0 rounded-xl bg-slate-100 text-red-600 hover:bg-slate-200 transition-colors">
                <i class="fas fa-xmark"></i>
              </button>
            </div>
          </template>
          <button type="button" @click="f.levels.push({ name: '', desc: '' })" x-show="f.levels.length < 5"
                  class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
            <i class="fas fa-plus text-xs"></i> أضف مستوى
          </button>
        </div>

        <div class="flex flex-wrap gap-3 mb-10">
          <button type="button" @click="print()"
                  class="flex-1 bg-primary text-white py-4 rounded-xl font-black text-lg hover:bg-secondary transition-all shadow-lg shadow-primary/20">
            <i class="fas fa-print ml-2"></i> اطبع الشبكة
          </button>
          <button type="button" @click="clear()"
                  class="bg-slate-100 text-slate-700 px-6 py-4 rounded-xl font-black hover:bg-slate-200 transition-colors">
            <i class="fas fa-eraser ml-2"></i> امسح الكل
          </button>
        </div>

        <h2 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-2.5">
          <i class="fas fa-eye text-accent"></i> المعاينة
        </h2>
      </div>

      <!-- الشبكة (هي ما يُطبع) -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-5 md:p-8 article-body">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
          <p class="font-bold text-slate-700" x-text="f.head"></p>
          <p class="text-sm text-slate-500">الاسم: ..................... القسم: ..........</p>
        </div>

        <h2 class="text-center">شبكة التقويم بالمعايير</h2>
        <p class="text-center" x-show="f.task"><b x-text="f.task"></b></p>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>المعيار</th>
                <th>المؤشرات</th>
                <template x-for="(l, i) in f.levels" :key="i"><th x-text="l.name"></th></template>
                <th>النقطة</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(c, i) in f.criteria" :key="i">
                <tr>
                  <td>
                    <b x-text="c.name"></b>
                    <span x-show="c.perf" class="text-slate-500"> (إتقان)</span>
                  </td>
                  <td x-text="c.ind"></td>
                  <template x-for="(l, j) in f.levels" :key="j"><td></td></template>
                  <td x-text="c.pts ? '/ ' + c.pts : ''"></td>
                </tr>
              </template>
              <tr>
                <td colspan="2"><b>المجموع</b></td>
                <template x-for="(l, j) in f.levels" :key="j"><td></td></template>
                <td x-text="total ? '/ ' + total : ''"></td>
              </tr>
            </tbody>
          </table>
        </div>

        <h3>دلالة المستويات</h3>
        <ul>
          <template x-for="(l, i) in f.levels" :key="i">
            <li x-show="l.name || l.desc"><b x-text="l.name ? l.name + ': ' : ''"></b><span x-text="l.desc"></span></li>
          </template>
        </ul>

        <h3>ملاحظات المصحّح</h3>
        <p>.......................................................................................</p>
        <p>.......................................................................................</p>
      </div>

      <p class="mt-6 text-center text-sm text-slate-400 font-bold no-print">
        <i class="fas fa-lock text-xs"></i>
        كل ما تكتبه يبقى في متصفحك — لا يُرسَل إلى أي خادم ولا يُخزَّن.
      </p>

      <div class="mt-10 bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body no-print">
        <h2>لماذا الشبكة أعدل من التقدير الحدسي؟</h2>
        <p>
          التصحيح بلا شبكة يتأثر بما لا علاقة له بالتعلم: <b>أثر الهالة</b> (انطباعك السابق عن المتعلم)،
          و<b>أثر الترتيب</b> (الورقة الضعيفة بعد ممتازة تبدو أضعف مما هي)، والتعب في آخر الرزمة.
          الشبكة تُثبّت المرجع فتقلّص هذه الآثار وترفع <b>ثبات</b> التقويم.
        </p>
        <div class="note-ok">
          <b>سلّمها للمتعلمين قبل الإنجاز.</b> هذا ليس كشفًا للأسئلة، بل تعليم لما هو «العمل الجيد» —
          ومن أقوى ما يرفع جودة الإنتاج. ولا يمكن أن يقوّم المتعلم نفسه بمعيار يجهله.
        </div>
        <div class="note-info">
          راجع النظري في <a href="/moujam/majal/taqwim">مصطلحات التقويم</a>
          و<a href="/maqalat/anwa3-attaqwim">أنواع التقويم الثلاثة</a>،
          واختبر فهمك في <a href="/ikhtibarat/taqwim/">اختبار التقويم التربوي</a>.
        </div>
      </div>

    </div>
  </div>
</div>

<script>
/** مولّد شبكة التقويم — كل شيء داخل المتصفح. */
function chabaka() {
  /** معايير روجيرس: ثلاثة حدّ أدنى + معيار إتقان */
  function defaultCriteria() {
    return [
      { name: 'الملاءمة', ind: 'فهم التعليمة · الإنتاج يستجيب للمطلوب · احترام نوع النص', pts: '4', perf: false },
      { name: 'الاستعمال السليم لأدوات المادة', ind: 'سلامة القواعد والمفاهيم · توظيف المكتسبات في موضعها', pts: '4', perf: false },
      { name: 'الانسجام', ind: 'الأفكار متسلسلة · الروابط صريحة · لا تناقض بين الأجزاء', pts: '4', perf: false },
      { name: 'الإتقان', ind: 'جودة العرض · الخط والنظافة · الأصالة في الطرح', pts: '2', perf: true }
    ];
  }

  function defaultLevels() {
    return [
      { name: 'متحكّم', desc: 'المعيار متحقق في أغلب المؤشرات دون تعثر يُذكر.' },
      { name: 'في طور التحكّم', desc: 'المعيار متحقق جزئيًا: بعض المؤشرات حاضرة وأخرى ناقصة.' },
      { name: 'غير متحكّم', desc: 'المعيار غير متحقق: أغلب المؤشرات غائبة.' }
    ];
  }

  function blank() {
    return { head: '', task: '', criteria: defaultCriteria(), levels: defaultLevels() };
  }

  return {
    f: blank(),

    /** مجموع النقط المعلنة على المعايير */
    get total() {
      var s = this.f.criteria.reduce(function (a, c) {
        var v = parseFloat(c.pts);
        return a + (isNaN(v) ? 0 : v);
      }, 0);

      return s ? Math.round(s * 100) / 100 : 0;
    },

    resetCriteria() { this.f.criteria = defaultCriteria(); },

    clear() {
      if (window.confirm('سيُمسح كل ما كتبتَه. متأكد؟')) { this.f = blank(); }
    },

    print() { window.print(); }
  };
}
</script>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
