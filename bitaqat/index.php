<?php
/**
 * بطاقات المراجعة — تُبنى من data/moujam.json نفسه.
 *
 * الفكرة: الاسترجاع من الذاكرة قبل رؤية الجواب. يرى الزائر وجه البطاقة
 * (المصطلح) فيحاول استحضار تعريفه، ثم يكشف الوجه الآخر ويحكم على نفسه.
 * ما يصنّفه «أراجعه» يعود في جولة ثانية.
 *
 * كل شيء داخل المتصفح: لا تُرسَل أي بيانات ولا تُخزَّن.
 */

require __DIR__ . '/../partials/lib.php';

$M     = tb_data('moujam');
$cats  = $M['cats'] ?? [];
$terms = $M['terms'] ?? [];

$catTitle = [];
foreach ($cats as $c) {
    $catTitle[$c['slug']] = $c['title'];
}

$cards = array_map(static fn (array $t): array => [
    't' => $t['t'],
    's' => $t['s'],
    'k' => $t['c'],
    'c' => $catTitle[$t['c']] ?? '',
    'd' => $t['d'],
    'x' => $t['x'] ?? '',
    'r' => $t['r'] ?? [],
], $terms);

$total = count($cards);

$meta = [
    'title'       => 'بطاقات مراجعة المصطلحات التربوية: ' . $total . ' بطاقة',
    'description' => 'راجع مصطلحات علوم التربية ببطاقات الاسترجاع: استحضر التعريف من ذاكرتك ثم اكشفه، وأعد ما لم تتقنه. ' . $total . ' بطاقة مجانًا وبلا تسجيل.',
    'canonical'   => '/bitaqat/',
    'image'       => '/assets/img/og-dourous.jpg',
    'section'     => 'dourous',
    'breadcrumb'  => [
        ['name' => 'دروس علوم التربية', 'url' => '/dourous/'],
        ['name' => 'بطاقات المراجعة', 'url' => '/bitaqat/'],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'LearningResource',
        'name'        => 'بطاقات مراجعة المصطلحات التربوية',
        'description' => 'بطاقات استرجاع لمراجعة مصطلحات علوم التربية والديداكتيك والتقويم.',
        'url'         => tb_abs('/bitaqat/'),
        'inLanguage'  => 'ar',
        'learningResourceType' => 'Flashcards',
        'educationalLevel'     => 'التعليم العالي والتكوين المهني',
        'isPartOf'    => ['@id' => SITE_URL . '/#website'],
        'publisher'   => ['@id' => SITE_URL . '/#organization'],
    ]],
];

require __DIR__ . '/../partials/header.php';
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
        <li><a href="/dourous/" class="hover:text-accent transition-colors">دروس علوم التربية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">بطاقات المراجعة</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">بطاقات المراجعة</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-6">
        <b><?= $total ?> بطاقة</b> مبنية من معجم المصطلحات. ترى المصطلح أولًا فتحاول
        <b>استحضار تعريفه من ذاكرتك</b>، ثم تكشف الجواب وتحكم على نفسك —
        وما لم تتقنه يعود في جولة ثانية.
      </p>
      <div class="flex flex-wrap gap-3 text-sm font-bold">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-brain"></i> استرجاع نشط</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-filter"></i> تصفية حسب المجال</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-rotate-left"></i> إعادة ما لم تتقنه</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-lock"></i> بلا تسجيل</span>
      </div>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14"
     x-data="bitaqat(<?= e(json_encode($cards, JSON_UNESCAPED_UNICODE)) ?>)"
     @keydown.window="hotkey($event)">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl mx-auto">

      <!-- الإعداد -->
      <div x-show="!started" x-cloak class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-10 shadow-sm">
        <h2 class="text-xl md:text-2xl font-black text-primary flex items-center gap-2.5 mb-3">
          <i class="fas fa-layer-group text-accent"></i> اختر المجال
        </h2>
        <p class="text-slate-500 leading-relaxed mb-6">
          راجع كل المصطلحات دفعة واحدة، أو ركّز على مجال واحد قبل الامتحان.
          تُخلط البطاقات في كل جولة.
        </p>

        <div class="flex flex-wrap gap-2 mb-8">
          <button type="button" @click="cat = ''"
                  :class="cat === '' ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                  class="px-4 py-2.5 rounded-xl text-sm font-bold ring-1 transition-all">
            كل المجالات <span class="opacity-60"><?= $total ?></span>
          </button>
          <?php foreach ($cats as $c):
              $cn = count(array_filter($terms, static fn (array $t): bool => $t['c'] === $c['slug']));
              if (!$cn) { continue; } ?>
          <button type="button" @click="cat = '<?= e($c['slug']) ?>'"
                  :class="cat === '<?= e($c['slug']) ?>' ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                  class="px-4 py-2.5 rounded-xl text-sm font-bold ring-1 transition-all">
            <i class="fas <?= e($c['icon']) ?> text-xs ml-1"></i>
            <?= e($c['title']) ?> <span class="opacity-60"><?= $cn ?></span>
          </button>
          <?php endforeach; ?>
        </div>

        <button type="button" @click="start()"
                class="w-full bg-primary text-white py-4 rounded-xl font-black text-lg hover:bg-secondary transition-all shadow-lg shadow-primary/20">
          ابدأ المراجعة (<span x-text="pool.length"></span> بطاقة)
          <i class="fas fa-arrow-left mr-2 text-sm"></i>
        </button>
      </div>

      <!-- الجولة -->
      <div x-show="started && !done" x-cloak>

        <div class="flex items-center justify-between text-sm font-black text-slate-500 mb-2">
          <span>البطاقة <span x-text="i + 1"></span> من <span x-text="deck.length"></span></span>
          <span class="flex items-center gap-4">
            <span class="text-emerald-600"><i class="fas fa-circle-check"></i> <span x-text="knownCount"></span></span>
            <span class="text-amber-700"><i class="fas fa-rotate-left"></i> <span x-text="reviewCount"></span></span>
          </span>
        </div>
        <div class="h-2 bg-slate-200 rounded-full overflow-hidden mb-6">
          <div class="h-full bg-accent rounded-full transition-all duration-300"
               :style="'width:' + ((i / deck.length) * 100) + '%'"></div>
        </div>

        <!-- البطاقة -->
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 shadow-sm overflow-hidden">
          <div class="p-6 md:p-10 text-center">
            <span class="inline-flex items-center gap-1.5 text-xs font-black px-3 py-1.5 rounded-full bg-slate-100 text-slate-500 mb-5"
                  x-text="cur.c"></span>
            <h2 class="text-2xl md:text-4xl font-black text-primary leading-snug" x-text="cur.t"></h2>

            <p x-show="!flipped" class="text-slate-400 font-bold mt-6 leading-relaxed">
              استحضر التعريف في ذهنك أولًا، ثم اكشف الجواب.
            </p>

            <div x-show="flipped" x-cloak class="faq-panel mt-6 text-right">
              <p class="text-slate-800 leading-relaxed font-medium mb-3" x-text="cur.d"></p>
              <p x-show="cur.x" class="text-slate-600 leading-relaxed text-[15px] bg-slate-50 rounded-xl px-4 py-3 border-r-4 border-accent/40" x-text="cur.x"></p>
              <p x-show="cur.r.length" class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                <span class="font-bold text-slate-400">انظر أيضًا:</span>
                <template x-for="r in cur.r" :key="r">
                  <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold" x-text="r"></span>
                </template>
              </p>
              <p class="mt-4 text-sm">
                <a :href="'/moujam/#' + cur.s" class="text-primary font-bold hover:underline">
                  افتح المصطلح في المعجم <i class="fas fa-arrow-left text-xs"></i>
                </a>
              </p>
            </div>
          </div>

          <div class="border-t border-slate-100 p-5">
            <button type="button" x-show="!flipped" @click="flipped = true"
                    class="w-full bg-primary text-white py-4 rounded-xl font-black text-lg hover:bg-secondary transition-all shadow-lg shadow-primary/20">
              اكشف الجواب
            </button>

            <div x-show="flipped" x-cloak class="grid grid-cols-2 gap-3">
              <button type="button" @click="mark('r')"
                      class="bg-amber-50 text-amber-800 ring-1 ring-amber-200 py-4 rounded-xl font-black hover:bg-slate-100 transition-colors">
                <i class="fas fa-rotate-left ml-2"></i>أراجعها
              </button>
              <button type="button" @click="mark('k')"
                      class="bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200 py-4 rounded-xl font-black hover:bg-slate-100 transition-colors">
                <i class="fas fa-check ml-2"></i>أتقنتها
              </button>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between gap-3 mt-5">
          <button type="button" @click="prev()" :disabled="i === 0"
                  class="bg-white ring-1 ring-slate-200 text-slate-600 px-5 py-3 rounded-xl font-bold hover:ring-primary/30 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
            <i class="fas fa-arrow-right ml-2 text-xs"></i>السابقة
          </button>
          <button type="button" @click="reset()"
                  class="text-sm font-bold text-slate-500 hover:text-primary transition-colors">
            <i class="fas fa-sliders ml-1"></i>غيّر المجال
          </button>
          <button type="button" @click="skip()"
                  class="bg-white ring-1 ring-slate-200 text-slate-600 px-5 py-3 rounded-xl font-bold hover:ring-primary/30 transition-all">
            تخطَّ<i class="fas fa-arrow-left mr-2 text-xs"></i>
          </button>
        </div>

        <p class="mt-5 text-center text-xs text-slate-400 font-bold">
          <i class="fas fa-keyboard"></i>
          اختصارات: <b>مسافة</b> لكشف الجواب · <b>1</b> أراجعها · <b>2</b> أتقنتها
        </p>
      </div>

      <!-- الحصيلة -->
      <div x-show="done" x-cloak class="rounded-3xl overflow-hidden ring-1 ring-slate-200 shadow-sm">
        <div class="text-center px-6 py-10 bg-primary">
          <p class="text-white/70 font-medium mb-1">حصيلة الجولة</p>
          <p class="my-2">
            <span class="text-6xl md:text-7xl font-black text-gold" x-text="knownCount"></span>
            <span class="text-2xl font-bold text-white/50"> / <span x-text="deck.length"></span></span>
          </p>
          <p class="text-xl font-black text-white">بطاقة أتقنتها</p>
        </div>

        <div class="bg-white p-6 md:p-8">
          <p x-show="reviewCount === 0" class="text-center text-emerald-700 font-black text-lg mb-6">
            🎉 أتقنت كل البطاقات في هذه الجولة
          </p>

          <div class="flex flex-wrap gap-3">
            <button type="button" x-show="reviewCount > 0" @click="again()"
                    class="w-full bg-accent text-white py-4 rounded-xl font-black hover:bg-secondary transition-colors">
              <i class="fas fa-rotate-left ml-2"></i>أعد ما لم تتقنه
              (<span x-text="reviewCount"></span>)
            </button>
            <button type="button" @click="start()"
                    class="flex-1 bg-primary text-white py-4 rounded-xl font-black hover:bg-secondary transition-colors">
              <i class="fas fa-rotate-right ml-2"></i>جولة جديدة
            </button>
            <button type="button" @click="reset()"
                    class="flex-1 bg-slate-100 text-slate-700 py-4 rounded-xl font-black hover:bg-slate-200 transition-colors">
              <i class="fas fa-sliders ml-2"></i>غيّر المجال
            </button>
          </div>

          <div class="mt-6 bg-slate-50 ring-1 ring-slate-200 rounded-2xl p-5 article-body">
            <p>
              بعد المراجعة، <b>اختبر نفسك فعلًا</b> في
              <a href="/ikhtibarat/">الاختبارات التفاعلية</a> — الاسترجاع تحت ضغط السؤال
              يكشف ما لا تكشفه البطاقة. وللتعريفات الكاملة عد إلى <a href="/moujam/">المعجم</a>.
            </p>
          </div>
        </div>
      </div>

      <p class="mt-6 text-center text-sm text-slate-400 font-bold">
        <i class="fas fa-lock text-xs"></i>
        مراجعتك تبقى في متصفحك — لا تُرسَل إلى أي خادم ولا تُخزَّن.
      </p>
    </div>
  </div>
</div>

<section class="bg-white py-14 md:py-20 border-t border-slate-200">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl article-body">
      <h2>لماذا البطاقات أنفع من إعادة قراءة المعجم؟</h2>
      <p>
        حين تعيد قراءة تعريف مكتوب أمامك، يشعر ذهنك بالألفة فيقرأها إتقانًا. وهذا هو
        <b>وهم المعرفة</b>: أنت تعرف النص لأنك رأيته لا لأنك تملكه. أما البطاقة فتقلب
        الترتيب: تسألك أولًا ثم تُريك الجواب، فتنكشف الفجوة فورًا.
      </p>

      <div class="note-ok">
        <b>القاعدة العملية:</b> لا تكشف الجواب قبل أن تحاول فعلًا. المحاولة الفاشلة
        التي تسبق رؤية الجواب تجعلك تتذكره أفضل ممّن قرأه مباشرة — والجهد هنا مطلوب لا مكروه.
      </div>

      <h2>كيف تستعملها؟</h2>
      <ol>
        <li><b>صفِّ حسب المجال</b> إن كنت تراجع محورًا بعينه، أو خذ كل البطاقات لمسح شامل.</li>
        <li><b>احكم على نفسك بصدق.</b> «أتقنتها» تعني أنك استحضرت التعريف كاملًا قبل الكشف، لا أنك تعرّفت عليه بعده.</li>
        <li><b>أعد ما لم تتقنه</b> في الجولة الثانية حتى تفرغ القائمة.</li>
        <li><b>أعد الجولة بعد يومين</b> لا بعد ساعة — التباعد هو ما يثبّت.</li>
        <li>ثم <b>انتقل إلى <a href="/ikhtibarat/">الاختبارات</a></b> — فالسؤال بأربعة خيارات محك أصعب من البطاقة.</li>
      </ol>

      <div class="note-info">
        <b>البطاقات مبنية من <a href="/moujam/">معجم المصطلحات</a></b> — كل مصطلح يُضاف إلى
        المعجم يظهر هنا تلقائيًا. وإن نقصك مصطلح راسلنا على
        <a href="mailto:<?= e(MAIL_MAIN) ?>"><?= e(MAIL_MAIN) ?></a>.
      </div>
    </div>
  </div>
</section>

<script>
/**
 * بطاقات المراجعة — كل المنطق داخل المتصفح.
 * @param {Array} all كل البطاقات المبنية من المعجم.
 */
function bitaqat(all) {
  return {
    all: all,
    cat: '',
    deck: [],
    marks: {},        // slug -> 'k' أتقنها | 'r' يراجعها
    i: 0,
    flipped: false,
    started: false,
    done: false,

    /** البطاقات المطابقة للمجال المختار (تُعرض في زر البدء) */
    get pool() {
      var cat = this.cat;
      return cat === '' ? this.all : this.all.filter(function (c) { return c.k === cat; });
    },
    get cur() { return this.deck[this.i] || { t: '', c: '', d: '', x: '', r: [], s: '' }; },
    get knownCount() { return this.count('k'); },
    get reviewCount() { return this.count('r'); },

    count(kind) {
      var m = this.marks, n = 0;
      for (var k in m) { if (m[k] === kind) { n++; } }
      return n;
    },

    shuffle(arr) {
      var a = arr.slice();
      for (var k = a.length - 1; k > 0; k--) {
        var j = Math.floor(Math.random() * (k + 1));
        var t = a[k]; a[k] = a[j]; a[j] = t;
      }
      return a;
    },

    run(list) {
      this.deck = list;
      this.marks = {};
      this.i = 0;
      this.flipped = false;
      this.done = false;
      this.started = true;
    },

    /** جولة جديدة على المجال المختار */
    start() {
      if (!this.pool.length) { return; }
      this.run(this.shuffle(this.pool));
      this.top();
    },

    /** جولة على ما صُنّف «أراجعها» فقط */
    again() {
      var m = this.marks;
      var list = this.deck.filter(function (c) { return m[c.s] === 'r'; });
      if (!list.length) { return; }
      this.run(this.shuffle(list));
      this.top();
    },

    reset() {
      this.started = false;
      this.done = false;
      this.top();
    },

    top() { this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' }); },

    /** يسجّل الحكم على البطاقة الحالية وينتقل */
    mark(kind) {
      if (!this.flipped) { return; }
      this.marks[this.cur.s] = kind;
      this.advance();
    },

    /** انتقال بلا حكم */
    skip() { this.advance(); },

    advance() {
      if (this.i + 1 < this.deck.length) {
        this.i++;
        this.flipped = false;
        this.top();
      } else {
        this.done = true;
      }
    },

    prev() {
      if (this.i === 0) { return; }
      this.i--;
      this.flipped = false;
      this.top();
    },

    hotkey(ev) {
      if (!this.started || this.done || ev.ctrlKey || ev.altKey || ev.metaKey) { return; }
      var tag = (ev.target.tagName || '').toLowerCase();
      if (tag === 'input' || tag === 'textarea' || tag === 'select') { return; }

      if ((ev.key === ' ' || ev.key === 'Enter') && !this.flipped) {
        ev.preventDefault();
        this.flipped = true;
      } else if (ev.key === '1' && this.flipped) {
        ev.preventDefault();
        this.mark('r');
      } else if (ev.key === '2' && this.flipped) {
        ev.preventDefault();
        this.mark('k');
      } else if (ev.key === 'ArrowLeft') {
        ev.preventDefault();
        this.skip();
      } else if (ev.key === 'ArrowRight') {
        ev.preventDefault();
        this.prev();
      }
    }
  };
}
</script>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
