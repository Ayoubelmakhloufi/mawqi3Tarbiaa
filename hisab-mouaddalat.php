<?php
/**
 * حاسبة معدلات المتعلمين — أداة داخل المتصفح.
 *
 * يُدخل الأستاذ فروضه ومعاملاتها ونقط متعلميه، فتُحسب المعدلات المرجّحة
 * وإحصائيات القسم، مع جدول جاهز للطباعة.
 *
 * ⚠️ المعاملات يُدخلها الأستاذ بنفسه: لا تفترض الأداة أي معامل رسمي
 * لأن المعاملات تختلف باختلاف السلك والمادة والمذكرة المعمول بها.
 *
 * لا تُرسَل أي بيانات ولا تُخزَّن.
 */

require __DIR__ . '/partials/lib.php';

$meta = [
    'title'       => 'حاسبة معدلات المتعلمين — معدل مرجّح وإحصائيات القسم',
    'description' => 'أدخل فروضك ومعاملاتها ونقط متعلميك، واحصل على المعدل المرجّح لكل متعلم '
                   . 'ومعدل القسم ونسبة النجاح، في جدول جاهز للطباعة. مجانًا وبلا تسجيل.',
    'canonical'   => '/hisab-mouaddalat',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'حاسبة معدلات المتعلمين', 'url' => '/hisab-mouaddalat'],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'WebApplication',
        'name'        => 'حاسبة معدلات المتعلمين',
        'url'         => tb_abs('/hisab-mouaddalat'),
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
        <li class="text-white/80" aria-current="page">حاسبة المعدلات</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-4xl font-black leading-tight mb-3">حاسبة معدلات المتعلمين</h1>
      <p class="text-lg text-slate-300 leading-relaxed mb-5">
        أدخل فروضك ومعاملاتها ونقط متعلميك، فتحصل على <b>المعدل المرجّح</b> لكل واحد
        و<b>إحصائيات القسم</b> في جدول جاهز للطباعة.
      </p>
      <div class="flex flex-wrap gap-3 text-sm font-bold">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-calculator"></i> معدل مرجّح</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-chart-simple"></i> إحصائيات القسم</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-lock"></i> نقطك لا تغادر جهازك</span>
      </div>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14" x-data="mouaddalat()">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto">

      <div class="no-print">
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
            <i class="fas fa-circle-info text-accent"></i> معطيات القسم
          </h2>
          <div class="grid gap-4 md:grid-cols-3">
            <label class="block">
              <span class="block text-sm font-bold text-slate-600 mb-2">المادة والقسم</span>
              <input type="text" x-model="head" placeholder="الرياضيات — 2/3"
                     class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
            </label>
            <label class="block">
              <span class="block text-sm font-bold text-slate-600 mb-2">السلّم (النقطة على)</span>
              <input type="number" x-model.number="scale" min="1" max="100"
                     class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
            </label>
            <label class="block">
              <span class="block text-sm font-bold text-slate-600 mb-2">عتبة النجاح</span>
              <input type="number" x-model.number="pass" min="0" step="0.5"
                     class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
            </label>
          </div>
        </div>

        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-3">
            <i class="fas fa-list-ol text-accent"></i> الفروض ومعاملاتها
          </h2>
          <p class="text-sm text-slate-500 leading-relaxed mb-6">
            <b>المعاملات تُدخلها أنت</b> — لا تفترض الأداة أي معامل رسمي لأنه يختلف
            باختلاف السلك والمادة والمذكرة المعمول بها. طابقها مع مرجعك الرسمي.
          </p>
          <template x-for="(t, i) in tests" :key="i">
            <div class="flex items-center gap-2 mb-2">
              <input type="text" :value="t.name" @input="tests[i].name = $event.target.value"
                     placeholder="اسم الفرض"
                     class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 font-medium text-slate-800 focus:border-accent outline-none transition">
              <input type="number" :value="t.w" @input="tests[i].w = $event.target.value" min="0" step="0.5"
                     placeholder="معامل"
                     class="w-20 shrink-0 rounded-xl border-2 border-slate-200 bg-white px-3 py-2.5 font-medium text-slate-800 focus:border-accent outline-none transition">
              <button type="button" @click="removeTest(i)" x-show="tests.length > 1" aria-label="حذف الفرض"
                      class="w-12 h-12 shrink-0 rounded-xl bg-slate-100 text-red-600 hover:bg-slate-200 transition-colors">
                <i class="fas fa-xmark"></i>
              </button>
            </div>
          </template>
          <button type="button" @click="addTest()"
                  class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
            <i class="fas fa-plus text-xs"></i> أضف فرضًا
          </button>
        </div>

        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
            <i class="fas fa-users text-accent"></i> المتعلمون ونقطهم
          </h2>

          <div class="table-wrap mb-4">
            <table>
              <thead>
                <tr>
                  <th>المتعلم(ة)</th>
                  <template x-for="(t, j) in tests" :key="j"><th x-text="t.name || ('فرض ' + (j + 1))"></th></template>
                  <th>المعدل</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <template x-for="(s, i) in students" :key="i">
                  <tr>
                    <td>
                      <input type="text" :value="s.name" @input="students[i].name = $event.target.value"
                             placeholder="الاسم"
                             class="w-full rounded-lg border-2 border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-800 focus:border-accent outline-none transition">
                    </td>
                    <template x-for="(t, j) in tests" :key="j">
                      <td>
                        <input type="number" :value="s.marks[j]" @input="setMark(i, j, $event.target.value)"
                               min="0" :max="scale" step="0.25"
                               class="w-20 rounded-lg border-2 border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-800 focus:border-accent outline-none transition">
                      </td>
                    </template>
                    <td>
                      <b :class="avg(i) === null ? 'text-slate-400' : (avg(i) >= pass ? 'text-emerald-700' : 'text-red-600')"
                         x-text="avg(i) === null ? '—' : avg(i).toFixed(2)"></b>
                    </td>
                    <td>
                      <button type="button" @click="students.splice(i, 1)" x-show="students.length > 1" aria-label="حذف المتعلم"
                              class="text-red-600 hover:text-primary transition-colors">
                        <i class="fas fa-xmark"></i>
                      </button>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <div class="flex flex-wrap gap-3">
            <button type="button" @click="addStudent()"
                    class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
              <i class="fas fa-plus text-xs"></i> أضف متعلمًا
            </button>
            <button type="button" @click="addMany(10)"
                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition-colors">
              <i class="fas fa-user-plus text-xs"></i> أضف 10 صفوف
            </button>
          </div>
        </div>

        <p x-show="warning" x-cloak class="mb-6 rounded-xl bg-amber-50 ring-1 ring-amber-200 text-amber-800 px-4 py-3 font-bold text-sm" x-text="warning"></p>

        <div class="flex flex-wrap gap-3 mb-10">
          <button type="button" @click="print()"
                  class="flex-1 bg-primary text-white py-4 rounded-xl font-black text-lg hover:bg-secondary transition-all shadow-lg shadow-primary/20">
            <i class="fas fa-print ml-2"></i> اطبع الجدول
          </button>
          <button type="button" @click="clear()"
                  class="bg-slate-100 text-slate-700 px-6 py-4 rounded-xl font-black hover:bg-slate-200 transition-colors">
            <i class="fas fa-eraser ml-2"></i> امسح الكل
          </button>
        </div>
      </div>

      <!-- الحصيلة (تُطبع) -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-5 md:p-8 article-body">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
          <p class="font-bold text-slate-700" x-text="head"></p>
          <p class="text-sm text-slate-500" x-text="'النقطة على ' + scale + ' · عتبة النجاح ' + pass"></p>
        </div>

        <h2 class="text-center">نتائج القسم</h2>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th><th>المتعلم(ة)</th>
                <template x-for="(t, j) in tests" :key="j"><th x-text="t.name || ('فرض ' + (j + 1))"></th></template>
                <th>المعدل</th><th>النتيجة</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(s, i) in students" :key="i">
                <tr x-show="s.name || avg(i) !== null">
                  <td x-text="i + 1"></td>
                  <td x-text="s.name"></td>
                  <template x-for="(t, j) in tests" :key="j"><td x-text="s.marks[j] === '' || s.marks[j] === undefined ? '—' : s.marks[j]"></td></template>
                  <td><b x-text="avg(i) === null ? '—' : avg(i).toFixed(2)"></b></td>
                  <td x-text="avg(i) === null ? '—' : (avg(i) >= pass ? 'ناجح' : 'يحتاج دعمًا')"></td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

        <h3>إحصائيات القسم</h3>
        <div class="table-wrap">
          <table>
            <tbody>
              <tr><th>عدد المتعلمين المحسوبين</th><td x-text="stats.n"></td></tr>
              <tr><th>معدل القسم</th><td x-text="stats.n ? stats.mean.toFixed(2) : '—'"></td></tr>
              <tr><th>أعلى معدل</th><td x-text="stats.n ? stats.max.toFixed(2) : '—'"></td></tr>
              <tr><th>أدنى معدل</th><td x-text="stats.n ? stats.min.toFixed(2) : '—'"></td></tr>
              <tr><th>عدد الناجحين</th><td x-text="stats.n ? (stats.pass + ' من ' + stats.n + ' (' + stats.rate + '٪)') : '—'"></td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <p class="mt-6 text-center text-sm text-slate-400 font-bold no-print">
        <i class="fas fa-lock text-xs"></i>
        نقط متعلميك تبقى في متصفحك — لا تُرسَل إلى أي خادم ولا تُخزَّن.
      </p>

      <div class="mt-10 bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body no-print">
        <h2>كيف يُحسب المعدل المرجّح؟</h2>
        <p>
          مجموع (النقطة × معاملها) مقسومًا على مجموع المعاملات <b>المحسوبة فعلًا</b>.
          فإن غاب متعلم عن فرض وتركتَ خانته فارغة، لا يُحتسب معامل ذلك الفرض له —
          فلا يُظلم بصفر لم يستحقه.
        </p>
        <div class="note">
          <b>انتبه:</b> ترك الخانة فارغة يعني «غير معنيّ»، وكتابة <b>0</b> تعني «حصل على صفر».
          الفرق بينهما كبير في المعدل النهائي.
        </div>
        <div class="note-ok">
          <b>اقرأ الأرقام لا النقط وحدها.</b> إن كان معدل القسم منخفضًا مع تقارب النتائج،
          فالأرجح أن المشكل في الاختبار أو في تعلّم جماعي لم يقع — لا في المتعلمين فرادى.
          راجع <a href="/moujam/majal/taqwim">مصطلحات التقويم</a> و<a href="/chabakat-taqwim">شبكة التقويم بالمعايير</a>.
        </div>
      </div>

    </div>
  </div>
</div>

<script>
/** حاسبة المعدلات — كل الحساب داخل المتصفح. */
function mouaddalat() {
  function blankStudent(n) {
    return { name: '', marks: new Array(n).fill('') };
  }

  return {
    head: '',
    scale: 20,
    pass: 10,
    tests: [
      { name: 'الفرض 1', w: 1 },
      { name: 'الفرض 2', w: 1 },
      { name: 'الأنشطة', w: 1 }
    ],
    students: [blankStudent(3), blankStudent(3), blankStudent(3)],

    addTest() {
      this.tests.push({ name: '', w: 1 });
      this.students.forEach(function (s) { s.marks.push(''); });
    },

    removeTest(j) {
      this.tests.splice(j, 1);
      this.students.forEach(function (s) { s.marks.splice(j, 1); });
    },

    addStudent() { this.students.push(blankStudent(this.tests.length)); },

    addMany(k) {
      for (var i = 0; i < k; i++) { this.addStudent(); }
    },

    setMark(i, j, v) { this.students[i].marks[j] = v; },

    /** المعدل المرجّح، أو null إن لم تُدخل أي نقطة صالحة */
    avg(i) {
      var s = this.students[i];
      if (!s) { return null; }
      var sum = 0, wsum = 0;

      for (var j = 0; j < this.tests.length; j++) {
        var m = parseFloat(s.marks[j]);
        var w = parseFloat(this.tests[j].w);
        if (isNaN(m) || isNaN(w) || w <= 0) { continue; }
        sum += m * w;
        wsum += w;
      }

      return wsum > 0 ? sum / wsum : null;
    },

    get stats() {
      var vals = [];
      for (var i = 0; i < this.students.length; i++) {
        var a = this.avg(i);
        if (a !== null) { vals.push(a); }
      }
      if (!vals.length) { return { n: 0, mean: 0, max: 0, min: 0, pass: 0, rate: 0 }; }

      var self = this;
      var sum = vals.reduce(function (x, y) { return x + y; }, 0);
      var ok = vals.filter(function (v) { return v >= self.pass; }).length;

      return {
        n: vals.length,
        mean: sum / vals.length,
        max: Math.max.apply(null, vals),
        min: Math.min.apply(null, vals),
        pass: ok,
        rate: Math.round((ok / vals.length) * 100)
      };
    },

    /** تنبيهات على مدخلات غير متسقة */
    get warning() {
      var self = this;
      var bad = 0;
      this.students.forEach(function (s) {
        s.marks.forEach(function (m) {
          var v = parseFloat(m);
          if (!isNaN(v) && (v < 0 || v > self.scale)) { bad++; }
        });
      });
      if (bad) { return 'توجد ' + bad + ' نقطة خارج السلّم (0 إلى ' + this.scale + ') — تحقّق منها.'; }
      if (this.tests.every(function (t) { return !parseFloat(t.w); })) { return 'كل المعاملات صفر أو فارغة — لن يُحسب أي معدل.'; }

      return '';
    },

    clear() {
      if (window.confirm('ستُمسح كل النقط والأسماء. متأكد؟')) {
        this.head = '';
        this.students = [blankStudent(this.tests.length), blankStudent(this.tests.length), blankStudent(this.tests.length)];
      }
    },

    print() { window.print(); }
  };
}
</script>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
