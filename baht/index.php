<?php
/**
 * البحث في الموقع — بحث موحّد في كل المحتوى.
 *
 * كان يبحث في المقالات وحدها (26)، فيخرج من يبحث عن «النقل الديداكتيكي»
 * بلا نتيجة رغم أن له صفحة مصطلح كاملة. صار يشمل:
 * المقالات · مصطلحات المعجم ومجالاته · وثائق الحقيبة · الاختبارات · الأدوات والصفحات.
 *
 * ⚠️ الفهرس يُبنى آليًا من data/: أي إضافة تدخل البحث وحدها.
 * الأسئلة (350) مستثناة عمدًا — نتائجها لا تفيد الباحث وتضاعف وزن الصفحة.
 */

require __DIR__ . '/../partials/lib.php';

/** مدخل في فهرس البحث */
function tb_hit(string $kind, string $title, string $desc, string $url, string $extra = ''): array
{
    return [
        'k' => $kind,
        't' => $title,
        'd' => $desc,
        'u' => $url,
        'h' => $title . ' ' . $desc . ' ' . $extra,
    ];
}

$index = [];

/* المقالات */
foreach (tb_articles() as $a) {
    $sec = tb_section($a['section'] ?? '');
    $index[] = tb_hit(
        'مقال',
        $a['title'],
        $a['description'] ?? '',
        tb_article_url($a),
        implode(' ', $a['tags'] ?? []) . ' ' . ($sec['title'] ?? '') . ' ' . ($sec['nav'] ?? '')
    );
}

/* مجالات المعجم ومصطلحاته */
$M        = tb_data('moujam');
$catTitle = [];
foreach ($M['cats'] ?? [] as $c) {
    $catTitle[$c['slug']] = $c['title'];
    $index[] = tb_hit('مجال', $c['title'], 'مجال في معجم المصطلحات التربوية', '/moujam/majal/' . $c['slug']);
}
foreach ($M['terms'] ?? [] as $t) {
    $index[] = tb_hit(
        'مصطلح',
        $t['t'],
        $t['d'],
        '/moujam/' . $t['s'],
        ($t['x'] ?? '') . ' ' . implode(' ', $t['r'] ?? []) . ' ' . ($catTitle[$t['c']] ?? '')
    );
}

/* الاختبارات */
foreach (tb_data('ikhtibarat')['quizzes'] ?? [] as $z) {
    $index[] = tb_hit('اختبار', 'اختبار ' . $z['title'], $z['desc'], '/ikhtibarat/' . $z['slug'] . '/');
}

/* وثائق الحقيبة */
$W      = tb_data('wathaiq');
$wTitle = [];
foreach ($W['sections'] ?? [] as $w) {
    $wTitle[$w['slug']] = $w['title'];
}
foreach ($W['docs'] ?? [] as $d) {
    $index[] = tb_hit('وثيقة', $d['t'], $wTitle[$d['s']] ?? 'وثيقة رسمية', '/maktaba/wathaiq/' . $d['s'] . '/', $d['g'] ?? '');
}

/* الأدوات والصفحات — القائمة الوحيدة المكتوبة يدويًا هنا */
foreach ([
    ['أداة',  'حاسبة معدل الانتقاء', 'للمدارس العليا للأساتذة: ENS و ESEF و FSE', CALC_URL, 'معدل انتقاء حساب'],
    ['أداة',  'حاسبة الانتقاء الأولي — CRMEF', 'نقطتك على 80 من الباكالوريا والإجازة والسن', '/hisab-intiqa-crmef', 'مباراة التعليم مراكز جهوية'],
    ['أداة',  'مولّد جذاذة درس', 'ثلاثة نماذج: المقاطع والمراحل والمبسّط، جاهزة للطباعة', '/molid-jadada', 'جذاذة تخطيط درس مقاطع'],
    ['أداة',  'مولّد شبكة التقويم بالمعايير', 'معايير ومؤشرات ومستويات تحكّم للتصحيح', '/chabakat-taqwim', 'تصحيح معايير روجيرس'],
    ['أداة',  'حاسبة معدلات المتعلمين', 'معدل مرجّح لكل متعلم وإحصائيات القسم', '/hisab-mouaddalat', 'نقط معدل قسم'],
    ['أداة',  'مخطّط المراجعة', 'جدول أسبوعي حتى يوم المباراة بمراجعة متباعدة', '/mokhattat-almourajaa', 'خطة مراجعة تنظيم'],
    ['أداة',  'بطاقات المراجعة', 'استرجاع نشط للمصطلحات قبل كشف التعريف', '/bitaqat/', 'حفظ مصطلحات مراجعة'],
    ['أداة',  'التحدّي اليومي', 'عشرة أسئلة تتغيّر كل يوم', '/tahaddi-yawmi', 'اختبار يومي'],
    ['صفحة', 'ابدأ من هنا', 'مسارك في الموقع حسب حالك: مترشح أو طالب أو متدرّب أو ممارس', '/ibda-min-huna', 'دليل بداية'],
    ['صفحة', 'خريطة المحتوى', 'كل صفحات الموقع مرتبة في صفحة واحدة', '/kharitat-almawqi', 'فهرس'],
    ['صفحة', 'المواضيع حسب الوسم', 'تصفّح المقالات عبر وسومها', '/mawadi', 'وسوم تصنيف'],
    ['صفحة', 'حقيبة الوثائق الرقمية', 'الوثائق الرسمية للمنظومة التربوية للتحميل', '/maktaba/wathaiq/', 'مذكرات أطر مرجعية مناهج'],
    ['صفحة', 'من نحن', 'تعريف بمشروع منصة تربية', '/man-nahnu', ''],
    ['صفحة', 'اتصل بنا', 'راسلنا واقترح', '/ittisal', ''],
] as [$k, $t, $d, $u, $x]) {
    $index[] = tb_hit($k, $t, $d, $u, $x);
}

$meta = [
    'title'       => 'البحث في الموقع',
    'description' => 'ابحث في كل محتوى منصة تربية: المقالات ومصطلحات المعجم والوثائق الرسمية والاختبارات والأدوات — في خانة واحدة.',
    'canonical'   => '/baht/',
    'breadcrumb'  => [['name' => 'البحث في الموقع', 'url' => '/baht/']],
    'noindex'     => true, // صفحة أداة لا محتوى فريد — نمنع فهرستها ونُبقي على تتبع الروابط
];

require __DIR__ . '/../partials/header.php';
?>

<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <div class="max-w-3xl">
      <h1 class="text-3xl md:text-4xl font-black text-primary mb-3">البحث في الموقع</h1>
      <p class="text-slate-500 text-lg">
        <b><?= count($index) ?></b> مدخلًا: المقالات والمصطلحات والوثائق والاختبارات والأدوات — النتائج فورية.
      </p>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14"
     x-data="baht(<?= e(json_encode($index, JSON_UNESCAPED_UNICODE)) ?>)">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl">

      <div class="relative mb-5">
        <i class="fas fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true"></i>
        <label for="search-input" class="sr-only">ابحث في محتوى الموقع</label>
        <input type="search" id="search-input" autocomplete="off" x-model="q" x-ref="box"
               placeholder="مثال: النقل الديداكتيكي، معدل الانتقاء، الأطر المرجعية…"
               class="w-full rounded-2xl border-2 border-slate-200 bg-white pr-14 pl-5 py-4 text-lg font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
      </div>

      <div class="flex flex-wrap gap-2 mb-6">
        <button type="button" @click="kind = ''"
                :class="kind === '' ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                class="px-4 py-2 rounded-xl text-sm font-bold ring-1 transition-all">
          الكل <span class="opacity-60" x-text="results.length"></span>
        </button>
        <template x-for="k in kinds" :key="k">
          <button type="button" @click="kind = (kind === k ? '' : k)"
                  :class="kind === k ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                  class="px-4 py-2 rounded-xl text-sm font-bold ring-1 transition-all">
            <span x-text="k"></span> <span class="opacity-60" x-text="countOf(k)"></span>
          </button>
        </template>
      </div>

      <p class="mb-5 text-sm font-bold text-slate-400">
        <span x-show="q.trim() === ''">اكتب كلمة للبحث، أو تصفّح حسب النوع.</span>
        <span x-show="q.trim() !== ''" x-cloak>
          <span x-text="shown.length"></span> نتيجة معروضة من <span x-text="results.length"></span>
        </span>
      </p>

      <div class="space-y-3">
        <template x-for="(r, i) in shown" :key="r.u + i">
          <a :href="r.u"
             class="group block bg-white rounded-2xl ring-1 ring-slate-200/80 p-5 hover:ring-primary/30 hover:shadow-md transition-all">
            <span class="flex flex-wrap items-center gap-2 mb-2">
              <span class="text-xs font-black px-2.5 py-1 rounded-lg" :class="badge(r.k)" x-text="r.k"></span>
              <span class="font-black text-slate-800 group-hover:text-primary transition-colors" x-text="r.t"></span>
            </span>
            <span class="block text-sm text-slate-500 leading-relaxed" x-text="r.d"></span>
          </a>
        </template>
      </div>

      <p x-show="shown.length === 0" x-cloak class="text-center py-16">
        <i class="fas fa-magnifying-glass text-4xl text-slate-300 mb-4 block"></i>
        <span class="block font-black text-slate-500 mb-2">لا نتيجة تطابق بحثك</span>
        <span class="block text-slate-400">
          جرّب كلمة أقصر، أو تصفّح <a href="/kharitat-almawqi" class="text-primary font-bold hover:underline">خريطة المحتوى</a>.
        </span>
      </p>

      <p x-show="more > 0" x-cloak class="mt-6 text-center">
        <button type="button" @click="limit += 40"
                class="bg-white ring-1 ring-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold hover:ring-primary/30 transition-all">
          اعرض <span x-text="more"></span> نتيجة أخرى
        </button>
      </p>

    </div>
  </div>
</div>

<script>
/**
 * البحث الموحّد — كل الفهرس داخل الصفحة، فالنتائج فورية بلا أي طلب شبكة.
 * @param {Array} index مداخل البحث المبنية من ملفات data/.
 */
function baht(index) {
  return {
    index: index,
    q: '',
    kind: '',
    limit: 40,

    get kinds() {
      var seen = [];
      this.index.forEach(function (r) { if (seen.indexOf(r.k) === -1) { seen.push(r.k); } });

      return seen;
    },

    /** المطابقات كلها، قبل قصّها على الحد المعروض */
    get results() {
      var q = this.q.trim();
      var kind = this.kind;

      return this.index.filter(function (r) {
        if (kind && r.k !== kind) { return false; }

        return q === '' || r.h.indexOf(q) !== -1;
      });
    },

    get shown() { return this.results.slice(0, this.limit); },
    get more() { return Math.max(0, this.results.length - this.limit); },

    countOf(k) {
      var q = this.q.trim();

      return this.index.filter(function (r) {
        return r.k === k && (q === '' || r.h.indexOf(q) !== -1);
      }).length;
    },

    badge(k) {
      var map = {
        'مقال':   'bg-emerald-50 text-emerald-700',
        'مصطلح':  'bg-indigo-50 text-indigo-700',
        'مجال':   'bg-sky-50 text-sky-700',
        'وثيقة':  'bg-rose-50 text-rose-600',
        'اختبار': 'bg-amber-50 text-amber-700',
        'أداة':   'bg-teal-50 text-teal-700'
      };

      return map[k] || 'bg-slate-100 text-slate-500';
    },

    init() {
      /* أي بحث أو تصفية جديدة تعيد العدّ من الأعلى */
      this.$watch('q', function () { this.limit = 40; }.bind(this));
      this.$watch('kind', function () { this.limit = 40; }.bind(this));

      /* يدعم /baht/?q=... وهو الرابط المعلن في بيانات SearchAction لجوجل */
      var p = new URLSearchParams(location.search).get('q');
      if (p) { this.q = p; }
      this.$refs.box.focus();
    }
  };
}
</script>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
