<?php
/**
 * محرّك صفحة اختبار تفاعلي.
 *
 * تستدعيه كل صفحة اختبار بإحدى طريقتين:
 *   1) تعريف $quizSlug  — معرّف اختبار في data/ikhtibarat.json (الحالة العادية).
 *   2) تعريف $quiz مباشرةً — مصفوفة جاهزة بالبنية نفسها، لاختبار مركَّب
 *      لا يوجد في الملف (انظر /ikhtibarat/achwai/).
 *
 * ويمكن تمرير $quizNote (نص HTML قصير) ليظهر تحت شارات الرأس.
 *
 * كل المنطق يعمل داخل المتصفح؛ لا تُرسَل أي إجابة إلى الخادم ولا تُخزَّن.
 */

if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}

/* $quiz قد تكون مُعرَّفة سلفًا من الصفحة المستدعية */
$quiz = $quiz ?? null;

if (!$quiz) {
    foreach (tb_data('ikhtibarat')['quizzes'] ?? [] as $z) {
        if ($z['slug'] === ($quizSlug ?? null)) {
            $quiz = $z;
            break;
        }
    }
}

if (!$quiz) {
    http_response_code(500);
    exit('اختبار غير معرَّف: ' . e((string) ($quizSlug ?? '')));
}

$n    = count($quiz['qs']);
$tint = tb_tint($quiz['tint']);

/**
 * رابط الصفحة. الافتراضي /ikhtibarat/<المعرّف>/، ويمكن للصفحة المستدعية
 * تجاوزه بـ $quizCanonical إن كانت خارج مجلد الاختبارات (مثل /tahaddi-yawmi).
 */
$quizUrl = $quizCanonical ?? ('/ikhtibarat/' . $quiz['slug'] . '/');

/** عنوان الميتا. الافتراضي «اختبار <العنوان>…»، وتتجاوزه الصفحة بـ $quizMetaTitle */
$quizMetaTitle = $quizMetaTitle ?? null;

/* خيارات عدد الأسئلة — تُعرض فقط ما لا يتجاوز حجم البنك */
$lengths = array_values(array_filter([5, 10, 15, 20], static fn (int $x): bool => $x < $n));
$lengths[] = $n;

$meta = [
    'title'       => $quizMetaTitle ?? ('اختبار ' . $quiz['title'] . ': ' . tb_q_ar($n) . ' بتصحيح فوري'),
    'description' => $quiz['desc'] . ' بنك من ' . tb_q_ar($n) . '، اختر عددها وابدأ — تصحيح فوري وشرح لكل جواب، مجانًا وبلا تسجيل.',
    'canonical'   => $quizUrl,
    'image'       => '/assets/img/og-dourous.jpg',
    'section'     => 'dourous',
    'breadcrumb'  => [
        ['name' => 'الاختبارات التفاعلية', 'url' => '/ikhtibarat/'],
        ['name' => $quiz['title'], 'url' => $quizUrl],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'Quiz',
        'name'        => 'اختبار ' . $quiz['title'],
        'description' => $quiz['desc'],
        'url'         => tb_abs($quizUrl),
        'inLanguage'  => 'ar',
        'educationalLevel' => 'التعليم العالي والتكوين المهني',
        'about'       => ['@type' => 'Thing', 'name' => $quiz['title']],
        'publisher'   => ['@id' => SITE_URL . '/#organization'],
        'hasPart'     => array_map(static fn (array $q): array => [
            '@type'          => 'Question',
            'text'           => $q['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q['o'][$q['c']]],
        ], array_slice($quiz['qs'], 0, 10)),
    ]],
];

require __DIR__ . '/header.php';
?>

<section class="gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-5">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-accent transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/ikhtibarat/" class="hover:text-accent transition-colors">الاختبارات</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page"><?= e($quiz['title']) ?></li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-4xl font-black leading-tight mb-3">اختبار <?= e($quiz['title']) ?></h1>
      <p class="text-lg text-slate-300 leading-relaxed mb-5"><?= e($quiz['desc']) ?></p>
      <div class="flex flex-wrap gap-3 text-sm font-bold">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-database"></i> بنك من <?= $n ?> سؤالًا</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-shuffle"></i> أسئلة عشوائية كل مرة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-bolt"></i> تصحيح فوري</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-stopwatch"></i> مؤقّت اختياري</span>
      </div>
      <?php if (!empty($quizNote)): ?>
      <p class="text-sm text-slate-300 leading-relaxed mt-5 bg-white/5 rounded-xl px-4 py-3"><?= $quizNote ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14"
     x-data="quiz(<?= e(json_encode($quiz['qs'], JSON_UNESCAPED_UNICODE)) ?>)"
     @keydown.window="hotkey($event)">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl mx-auto">

      <!-- شاشة الإعداد -->
      <div x-show="!started" x-cloak class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-10 shadow-sm">
        <h2 class="text-xl md:text-2xl font-black text-primary flex items-center gap-2.5 mb-3">
          <i class="fas fa-sliders text-accent"></i> كم سؤالًا تريد؟
        </h2>
        <p class="text-slate-500 leading-relaxed mb-7">
          تُختار الأسئلة <b>عشوائيًا</b> من بنك يضم <b><?= $n ?> سؤالًا</b>،
          فتحصل على اختبار مختلف في كل محاولة.
        </p>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-8">
          <?php foreach ($lengths as $len): ?>
          <button type="button" @click="count = <?= $len ?>"
                  :class="count === <?= $len ?> ? 'bg-primary text-white ring-primary shadow-lg shadow-primary/20' : 'bg-white text-slate-700 ring-slate-200 hover:ring-primary/40'"
                  class="py-4 rounded-2xl ring-1 font-black transition-all">
            <span class="block text-2xl"><?= $len ?></span>
            <span class="block text-xs font-bold opacity-70 mt-0.5">
              <?= $len === $n ? 'كل الأسئلة' : 'أسئلة' ?>
            </span>
          </button>
          <?php endforeach; ?>
        </div>

        <!-- المؤقّت -->
        <h3 class="font-black text-primary flex items-center gap-2.5 mb-3">
          <i class="fas fa-stopwatch text-accent"></i> المؤقّت
        </h3>
        <div class="grid grid-cols-2 gap-3 mb-7">
          <button type="button" @click="timed = false"
                  :class="!timed ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-700 ring-slate-200 hover:ring-primary/40'"
                  class="py-3.5 rounded-2xl ring-1 font-bold transition-all">
            بلا مؤقّت
          </button>
          <button type="button" @click="timed = true"
                  :class="timed ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-700 ring-slate-200 hover:ring-primary/40'"
                  class="py-3.5 rounded-2xl ring-1 font-bold transition-all">
            <span x-text="'دقيقة لكل سؤال (' + count + ')'"></span>
          </button>
        </div>

        <div class="bg-slate-50 rounded-2xl p-5 mb-7 text-sm text-slate-600 leading-relaxed">
          <b class="text-slate-800">حد النجاح:</b>
          <span x-text="Math.ceil(count * 0.7)"></span> إجابة صحيحة من
          <span x-text="count"></span> <span class="text-slate-400">(70%)</span>
        </div>

        <button type="button" @click="start()"
                class="w-full bg-primary text-white py-4 rounded-xl font-black text-lg hover:bg-secondary transition-all shadow-lg shadow-primary/20">
          ابدأ الاختبار <i class="fas fa-arrow-left mr-2 text-sm"></i>
        </button>
      </div>

      <!-- شريط التقدّم -->
      <div x-show="started && !done" x-cloak class="mb-6">
        <div class="flex items-center justify-between text-sm font-black text-slate-500 mb-2">
          <span>السؤال <span x-text="i + 1"></span> من <span x-text="total"></span></span>
          <span class="flex items-center gap-4">
            <span x-show="timed" :class="left <= 30 ? 'text-red-600' : 'text-slate-500'">
              <i class="fas fa-stopwatch"></i> <span x-text="clock"></span>
            </span>
            <span><i class="fas fa-circle-check text-accent"></i> <span x-text="score"></span> صحيحة</span>
          </span>
        </div>
        <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
          <div class="h-full bg-accent rounded-full transition-all duration-300"
               :style="'width:' + ((i / total) * 100) + '%'"></div>
        </div>
      </div>

      <!-- السؤال -->
      <div x-show="started && !done" x-cloak class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 shadow-sm">
        <h2 class="text-xl md:text-2xl font-black text-slate-900 leading-snug mb-6" x-text="cur.q"></h2>

        <div class="space-y-3">
          <template x-for="(opt, k) in cur.o" :key="k">
            <button type="button" @click="pick(k)" :disabled="answered"
                    :class="cls(k)"
                    class="w-full text-right p-4 md:p-5 rounded-2xl ring-1 font-bold leading-relaxed transition-all flex items-start gap-3 disabled:cursor-default">
              <span class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-sm"
                    :class="badge(k)" x-text="letter(k)"></span>
              <span x-text="opt" class="flex-1"></span>
              <i x-show="answered && k === cur.c" class="fas fa-check text-emerald-600 mt-1"></i>
              <i x-show="answered && k === chosen && k !== cur.c" class="fas fa-xmark text-red-600 mt-1"></i>
            </button>
          </template>
        </div>

        <!-- الشرح -->
        <div x-show="answered" x-cloak class="faq-panel mt-6">
          <div class="rounded-2xl p-5 ring-1"
               :class="chosen === cur.c ? 'bg-emerald-50 ring-emerald-200' : 'bg-amber-50 ring-amber-200'">
            <p class="font-black mb-2" :class="chosen === cur.c ? 'text-emerald-800' : 'text-amber-800'"
               x-text="chosen === cur.c ? '✅ إجابة صحيحة' : '❌ إجابة خاطئة'"></p>
            <div class="article-body text-[15px]" :class="chosen === cur.c ? 'text-emerald-900' : 'text-amber-900'"
                 x-html="cur.e"></div>
          </div>

          <button type="button" @click="next()"
                  class="w-full mt-5 bg-primary text-white py-4 rounded-xl font-black hover:bg-secondary transition-all shadow-lg shadow-primary/20">
            <span x-text="i + 1 < total ? 'السؤال التالي ←' : 'اعرض النتيجة'"></span>
          </button>
        </div>

        <p class="mt-5 text-center text-xs text-slate-400 font-bold">
          <i class="fas fa-keyboard"></i>
          اختصارات: <b>1</b> إلى <b>4</b> لاختيار الجواب · <b>Enter</b> للانتقال
        </p>
      </div>

      <!-- النتيجة -->
      <div x-show="done" x-cloak>
        <div class="rounded-3xl overflow-hidden ring-1 ring-slate-200 shadow-sm">
          <div class="text-center px-6 py-10" :class="score >= min ? 'bg-primary' : 'bg-slate-800'">
            <p class="text-white/70 font-medium mb-1">نتيجتك</p>
            <p class="my-2">
              <span class="text-6xl md:text-7xl font-black" :class="score >= min ? 'text-gold' : 'text-white'" x-text="score"></span>
              <span class="text-2xl font-bold text-white/50"> / <span x-text="total"></span></span>
            </p>
            <p class="text-xl font-black text-white mb-1" x-text="verdict()"></p>
            <p class="text-white/60 font-medium text-sm">
              <span x-text="Math.round(score / total * 100) + '%'"></span>
              <span x-show="timeUp"> · انتهى الوقت</span>
              <span x-show="skipped > 0"> · <span x-text="skipped"></span> بلا جواب</span>
            </p>
          </div>

          <div class="bg-white p-6 md:p-8">
            <p x-show="wrong.length === 0 && skipped === 0" class="text-center text-emerald-700 font-black text-lg mb-6">
              🎉 كل الإجابات صحيحة — إتقان تام
            </p>

            <div class="flex flex-wrap gap-3">
              <button type="button" x-show="wrong.length > 0 || skipped > 0" @click="retryWrong()"
                      class="w-full bg-accent text-white py-4 rounded-xl font-black hover:bg-secondary transition-colors">
                <i class="fas fa-rotate-left ml-2"></i>أعد ما أخطأت فيه فقط
                (<span x-text="wrong.length + skipped"></span>)
              </button>
              <button type="button" @click="again()"
                      class="flex-1 bg-primary text-white py-4 rounded-xl font-black hover:bg-secondary transition-colors">
                <i class="fas fa-rotate-right ml-2"></i>أسئلة جديدة
              </button>
              <button type="button" @click="reset()"
                      class="flex-1 bg-slate-100 text-slate-700 py-4 rounded-xl font-black hover:bg-slate-200 transition-colors">
                <i class="fas fa-sliders ml-2"></i>غيّر الإعدادات
              </button>
              <a href="/ikhtibarat/" class="w-full text-center bg-white ring-1 ring-slate-200 text-slate-600 py-3 rounded-xl font-bold hover:ring-primary/30 transition-all">
                اختبار آخر
              </a>
            </div>

            <div class="mt-6 bg-slate-50 ring-1 ring-slate-200 rounded-2xl p-5 article-body">
              <p>
                راجع المفاهيم في <a href="/moujam/">معجم المصطلحات</a> و<a href="/bitaqat/">بطاقات المراجعة</a>
                و<a href="/dourous/">دروس علوم التربية</a>،
                أو تدرّب أكثر على <a href="<?= e(PLAY_URL) ?>" target="_blank" rel="noopener">تطبيق تربية</a> مجانًا.
              </p>
            </div>
          </div>
        </div>

        <!-- المراجعة الكاملة -->
        <div class="mt-8">
          <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h3 class="text-lg font-black text-primary flex items-center gap-2">
              <i class="fas fa-list-check text-accent"></i> مراجعة الأسئلة
            </h3>
            <button type="button" @click="allOpen = !allOpen; open = null"
                    class="text-sm font-bold text-slate-600 bg-white ring-1 ring-slate-200 rounded-xl px-4 py-2 hover:ring-primary/30 transition-all">
              <span x-text="allOpen ? 'اطوِ الكل' : 'افتح الكل'"></span>
            </button>
          </div>

          <div class="space-y-3">
            <template x-for="(q, k) in qs" :key="k">
              <div class="bg-white rounded-2xl ring-1 ring-slate-200 overflow-hidden">
                <button type="button" @click="open = (open === k ? null : k)"
                        class="w-full text-right p-4 flex items-start gap-3">
                  <span class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-sm" :class="mark(k)">
                    <i class="fas" :class="icon(k)"></i>
                  </span>
                  <span class="flex-1 font-bold text-slate-800 text-[15px] leading-relaxed" x-text="(k + 1) + '. ' + q.q"></span>
                  <i class="fas fa-chevron-down text-xs text-slate-400 mt-1.5 transition-transform"
                     :class="(allOpen || open === k) && 'rotate-180'"></i>
                </button>

                <div x-show="allOpen || open === k" x-cloak class="faq-panel px-4 pb-5">
                  <div class="space-y-2 mb-4">
                    <template x-for="(opt, j) in q.o" :key="j">
                      <p class="rounded-xl px-4 py-2.5 text-[15px] leading-relaxed ring-1" :class="rev(k, j)">
                        <b x-text="letter(j) + '. '"></b><span x-text="opt"></span>
                        <span x-show="j === q.c" class="font-black"> — الصحيح</span>
                        <span x-show="j === answers[k] && j !== q.c" class="font-black"> — جوابك</span>
                      </p>
                    </template>
                  </div>
                  <p x-show="answers[k] === null" class="text-sm font-bold text-slate-500 mb-3">
                    <i class="fas fa-circle-minus"></i> لم تُجب عن هذا السؤال
                  </p>
                  <div class="bg-slate-50 ring-1 ring-slate-200 rounded-2xl p-4 article-body text-[15px]" x-html="q.e"></div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>

      <p class="mt-6 text-center text-sm text-slate-400 font-bold">
        <i class="fas fa-lock text-xs"></i>
        إجاباتك تبقى في متصفحك — لا تُرسَل إلى أي خادم ولا تُخزَّن.
      </p>
    </div>
  </div>
</div>

<script>
/**
 * منطق الاختبار التفاعلي — يعمل بالكامل داخل المتصفح.
 * @param {Array} bank بنك الأسئلة الكامل؛ يُنتقى منه عشوائيًا العدد الذي يختاره الزائر.
 */
function quiz(bank) {
  return {
    bank: bank,
    qs: [],
    answers: [],                        // جواب الزائر لكل سؤال، أو null إن لم يُجب
    count: Math.min(10, bank.length),   // الاختيار الافتراضي
    timed: false,
    left: 0,                            // الثواني المتبقية
    timer: null,
    timeUp: false,
    started: false,
    done: false,
    i: 0,
    chosen: null,
    answered: false,
    open: null,                         // السؤال المفتوح في المراجعة
    allOpen: false,

    get total() { return this.qs.length; },
    get min() { return Math.ceil(this.total * 0.7); },
    get cur() { return this.qs[this.i] || { q: '', o: [], c: 0, e: '' }; },

    get score() {
      return this.answers.reduce((s, a, k) => s + (a !== null && a === this.qs[k].c ? 1 : 0), 0);
    },
    get wrong() {
      return this.answers.reduce((acc, a, k) => {
        if (a !== null && a !== this.qs[k].c) { acc.push(k); }
        return acc;
      }, []);
    },
    get skipped() {
      return this.answers.reduce((s, a) => s + (a === null ? 1 : 0), 0);
    },
    get clock() {
      var m = Math.floor(Math.max(0, this.left) / 60);
      var s = Math.max(0, this.left) % 60;
      return m + ':' + (s < 10 ? '0' : '') + s;
    },

    /** خلط فيشر-ييتس على نسخة، دون المساس بالبنك الأصلي */
    shuffle(arr) {
      var a = arr.slice();
      for (var k = a.length - 1; k > 0; k--) {
        var j = Math.floor(Math.random() * (k + 1));
        var t = a[k]; a[k] = a[j]; a[j] = t;
      }
      return a;
    },

    /** تهيئة جولة على مجموعة أسئلة معطاة */
    run(list) {
      this.stopTimer();
      this.qs = list;
      this.answers = list.map(function () { return null; });
      this.i = 0;
      this.chosen = null;
      this.answered = false;
      this.open = null;
      this.allOpen = false;
      this.timeUp = false;
      this.done = false;
      this.started = true;
      if (this.timed) {
        this.left = list.length * 60;
        this.startTimer();
      }
    },

    startTimer() {
      var self = this;
      this.timer = setInterval(function () {
        self.left--;
        if (self.left <= 0) {
          self.timeUp = true;
          self.finish();
        }
      }, 1000);
    },

    stopTimer() {
      if (this.timer) { clearInterval(this.timer); this.timer = null; }
    },

    finish() {
      this.stopTimer();
      this.done = true;
    },

    /** يبدأ اختبارًا جديدًا بعدد الأسئلة المختار */
    start() {
      this.run(this.shuffle(this.bank).slice(0, Math.min(this.count, this.bank.length)));
    },

    /** إعادة بنفس العدد لكن بأسئلة جديدة */
    again() {
      this.start();
      this.top();
    },

    /** إعادة الأسئلة التي أخطأ فيها أو تركها فقط */
    retryWrong() {
      var self = this;
      var list = this.qs.filter(function (q, k) {
        return self.answers[k] === null || self.answers[k] !== q.c;
      });
      if (!list.length) { return; }
      this.run(this.shuffle(list));
      this.top();
    },

    /** العودة إلى شاشة الإعدادات */
    reset() {
      this.stopTimer();
      this.started = false;
      this.done = false;
      this.top();
    },

    top() { this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' }); },

    letter(k) { return ['أ', 'ب', 'ج', 'د', 'هـ'][k] || (k + 1); },

    pick(k) {
      if (this.answered || this.done) { return; }
      this.chosen = k;
      this.answered = true;
      this.answers[this.i] = k;
    },

    next() {
      if (this.i + 1 < this.total) {
        this.i++;
        this.chosen = null;
        this.answered = false;
        this.top();
      } else {
        this.finish();
      }
    },

    /** اختصارات لوحة المفاتيح أثناء الاختبار */
    hotkey(ev) {
      if (!this.started || this.done || ev.ctrlKey || ev.altKey || ev.metaKey) { return; }
      var tag = (ev.target.tagName || '').toLowerCase();
      if (tag === 'input' || tag === 'textarea' || tag === 'select') { return; }

      var num = parseInt(ev.key, 10);
      if (num >= 1 && num <= this.cur.o.length && !this.answered) {
        ev.preventDefault();
        this.pick(num - 1);
      } else if ((ev.key === 'Enter' || ev.key === ' ') && this.answered) {
        ev.preventDefault();
        this.next();
      }
    },

    /** أصناف زر الخيار حسب حالة التصحيح */
    cls(k) {
      if (!this.answered) {
        return 'bg-white ring-slate-200 text-slate-700 hover:ring-primary/40 hover:bg-slate-50';
      }
      if (k === this.cur.c) { return 'bg-emerald-50 ring-emerald-300 text-emerald-900'; }
      if (k === this.chosen) { return 'bg-red-50 ring-red-300 text-red-900'; }

      return 'bg-white ring-slate-200 text-slate-400';
    },

    badge(k) {
      if (!this.answered) { return 'bg-slate-100 text-slate-500'; }
      if (k === this.cur.c) { return 'bg-emerald-600 text-white'; }
      if (k === this.chosen) { return 'bg-red-600 text-white'; }

      return 'bg-slate-100 text-slate-400';
    },

    /** شارة السؤال في قائمة المراجعة */
    mark(k) {
      if (this.answers[k] === null) { return 'bg-slate-200 text-slate-500'; }
      return this.answers[k] === this.qs[k].c ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white';
    },

    icon(k) {
      if (this.answers[k] === null) { return 'fa-minus'; }
      return this.answers[k] === this.qs[k].c ? 'fa-check' : 'fa-xmark';
    },

    /** أصناف خيار داخل قائمة المراجعة */
    rev(k, j) {
      if (j === this.qs[k].c) { return 'bg-emerald-50 ring-emerald-200 text-emerald-900'; }
      if (j === this.answers[k]) { return 'bg-red-50 ring-red-300 text-red-900'; }

      return 'bg-white ring-slate-200 text-slate-500';
    },

    verdict() {
      var p = this.score / this.total;
      if (p === 1) { return 'إتقان تام'; }
      if (this.score >= this.min) { return 'ناجح — مستوى جيد'; }
      if (p >= 0.5) { return 'قريب — راجع الأخطاء'; }

      return 'يحتاج مراجعة';
    }
  };
}
</script>

<?php
require __DIR__ . '/app-cta.php';
require __DIR__ . '/footer.php';
