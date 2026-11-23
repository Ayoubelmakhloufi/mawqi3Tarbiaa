<?php
require __DIR__ . '/../partials/lib.php';

$M     = tb_data('moujam');
$cats  = $M['cats'] ?? [];
$terms = $M['terms'] ?? [];

$meta = [
    'title'       => 'معجم المصطلحات التربوية: ' . count($terms) . ' مصطلحًا مشروحًا',
    'description' => 'معجم شامل لمصطلحات علوم التربية والديداكتيك والتقويم: تعريف واضح لكل مصطلح مع شرح عملي وأمثلة من الفصل الدراسي.',
    'canonical'   => '/moujam/',
    'image'       => '/assets/img/og-dourous.jpg',
    'section'     => 'dourous',
    'breadcrumb'  => [
        ['name' => 'دروس علوم التربية', 'url' => '/dourous/'],
        ['name' => 'معجم المصطلحات التربوية', 'url' => '/moujam/'],
    ],
    'jsonld'      => [[
        '@context'     => 'https://schema.org',
        '@type'        => 'DefinedTermSet',
        'name'         => 'معجم المصطلحات التربوية',
        'url'          => tb_abs('/moujam/'),
        'inLanguage'   => 'ar',
        'publisher'    => ['@id' => SITE_URL . '/#organization'],
        'hasDefinedTerm' => array_map(static fn (array $t): array => [
            '@type'       => 'DefinedTerm',
            'name'        => $t['t'],
            'description' => $t['d'],
            'url'         => tb_abs('/moujam/' . $t['s']),
        ], $terms),
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
        <li class="text-white/80" aria-current="page">المعجم</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">معجم المصطلحات التربوية</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed">
        <b><?= count($terms) ?> مصطلحًا</b> في علوم التربية والديداكتيك والتقويم — كل واحد بتعريف دقيق
        و<b>شرح عملي</b> يبيّن أثره داخل الفصل، مع روابط بين المفاهيم المترابطة.
      </p>
    </div>
  </div>
</section>

<div x-data="moujam()" class="bg-slate-50">

  <!-- البحث والتصفية -->
  <div class="sticky top-[72px] z-30 bg-slate-50/95 backdrop-blur border-b border-slate-200">
    <div class="container mx-auto px-4 md:px-6 py-5">
      <div class="max-w-4xl mx-auto">
        <div class="relative mb-4">
          <input type="search" x-model="q" @input="cat = ''"
                 placeholder="ابحث عن مصطلح… (مثال: الكفاية، النقل الديداكتيكي، السقالات)"
                 class="w-full rounded-2xl border-2 border-slate-200 bg-white px-5 py-4 pr-12 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
          <i class="fas fa-magnifying-glass absolute top-1/2 -translate-y-1/2 right-5 text-slate-400"></i>
        </div>

        <div class="flex flex-wrap gap-2">
          <button type="button" @click="cat = ''; q = ''"
                  :class="cat === '' && q === '' ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                  class="px-4 py-2 rounded-xl text-sm font-bold ring-1 transition-all">
            الكل <span class="opacity-60"><?= count($terms) ?></span>
          </button>
          <?php foreach ($cats as $c):
              $n = count(array_filter($terms, static fn (array $t): bool => $t['c'] === $c['slug']));
              if (!$n) { continue; } ?>
          <button type="button" @click="cat = (cat === '<?= e($c['slug']) ?>' ? '' : '<?= e($c['slug']) ?>'); q = ''"
                  :class="cat === '<?= e($c['slug']) ?>' ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                  class="px-4 py-2 rounded-xl text-sm font-bold ring-1 transition-all">
            <i class="fas <?= e($c['icon']) ?> text-xs ml-1"></i>
            <?= e($c['title']) ?> <span class="opacity-60"><?= $n ?></span>
          </button>
          <?php endforeach; ?>
        </div>

        <p class="mt-3 text-sm font-bold text-slate-400">
          <span x-show="q !== '' || cat !== ''" x-cloak>
            <span x-text="shown"></span> نتيجة
            <button type="button" @click="q=''; cat=''" class="text-primary hover:underline mr-2">إلغاء التصفية</button>
          </span>
        </p>

        <?php /* صفحة مستقلة لكل مجال: رابط ثابت قابل للمشاركة والفهرسة،
                 بخلاف التصفية أعلاه التي تعمل داخل الصفحة فقط. */ ?>
        <p class="mt-3 flex flex-wrap items-center gap-2 text-sm">
          <span class="font-bold text-slate-400">صفحات المجالات:</span>
          <?php foreach ($cats as $c): ?>
          <a href="/moujam/majal/<?= e($c['slug']) ?>"
             class="px-2.5 py-1 rounded-lg bg-white ring-1 ring-slate-200 text-slate-600 font-bold hover:ring-primary/30 hover:text-primary transition-all"><?= e($c['title']) ?></a>
          <?php endforeach; ?>
        </p>
      </div>
    </div>
  </div>

  <!-- المصطلحات -->
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <div class="max-w-4xl mx-auto space-y-4">
      <?php foreach ($terms as $t):
          $c = null;
          foreach ($cats as $cc) { if ($cc['slug'] === $t['c']) { $c = $cc; break; } }
          $tint = tb_tint($c['tint'] ?? 'emerald');
          $hay  = $t['t'] . ' ' . $t['d'] . ' ' . $t['x'] . ' ' . implode(' ', $t['r']); ?>
      <article id="<?= e($t['s']) ?>"
               class="term scroll-mt-44 bg-white rounded-2xl ring-1 ring-slate-200/80 p-6 md:p-7 hover:ring-primary/20 transition-all"
               data-c="<?= e($t['c']) ?>" data-h="<?= e($hay) ?>"
               x-show="ok($el)" x-cloak>

        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
          <h2 class="text-xl md:text-2xl font-black text-primary flex items-center gap-2">
            <a href="/moujam/<?= e($t['s']) ?>" class="hover:underline decoration-accent/40 underline-offset-4"><?= e($t['t']) ?></a>
            <button type="button" @click="copy('<?= e($t['s']) ?>', $event)"
                    class="shrink-0 w-7 h-7 rounded-lg text-slate-300 hover:text-primary hover:bg-slate-100 transition-colors text-sm no-print"
                    aria-label="نسخ رابط المصطلح" title="نسخ رابط المصطلح">
              <i class="fas fa-link" aria-hidden="true"></i>
            </button>
          </h2>
          <span class="inline-flex items-center gap-1.5 shrink-0 text-xs font-black px-3 py-1.5 rounded-full <?= e($tint['bg']) ?> <?= e($tint['text']) ?> ring-1 <?= e($tint['ring']) ?>">
            <i class="fas <?= e($c['icon'] ?? 'fa-tag') ?> text-[10px]"></i>
            <?= e($c['title'] ?? '') ?>
          </span>
        </div>

        <p class="text-slate-800 leading-relaxed font-medium mb-4"><?= e($t['d']) ?></p>

        <?php /* الشرح العملي والمفاهيم المرتبطة على صفحة المصطلح لا هنا —
                 تفاديًا لازدواج المحتوى بين الفهرس والصفحة المستقلة. */ ?>
        <a href="/moujam/<?= e($t['s']) ?>"
           class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
          الشرح العملي والمفاهيم المرتبطة
          <i class="fas fa-arrow-left text-xs"></i>
        </a>
      </article>
      <?php endforeach; ?>

      <p x-show="shown === 0" x-cloak class="text-center py-16">
        <i class="fas fa-magnifying-glass text-4xl text-slate-300 mb-4 block"></i>
        <span class="block font-black text-slate-500 mb-2">لا مصطلح يطابق بحثك</span>
        <button type="button" @click="q=''; cat=''" class="text-primary font-bold hover:underline">اعرض كل المصطلحات</button>
      </p>
    </div>
  </div>

  <!-- شرح -->
  <section class="bg-white py-14 md:py-20 border-t border-slate-200">
    <div class="container mx-auto px-4 md:px-6">
      <div class="max-w-3xl article-body">
        <h2>كيف تستعمل هذا المعجم؟</h2>
        <ul>
          <li><b>ابحث مباشرة</b> عن أي مصطلح في خانة البحث أعلاه — النتائج فورية.</li>
          <li><b>صفِّ حسب المجال</b> لتراجع مجالًا كاملًا قبل الامتحان.</li>
          <li><b>اتبع روابط «انظر أيضًا»</b> — المفاهيم التربوية مترابطة، وفهم الشبكة أهم من حفظ التعاريف منفصلة.</li>
          <li><b>انسخ رابط أي مصطلح</b> بالضغط على عنوانه، وشاركه مع زملائك.</li>
        </ul>

        <div class="note-ok">
          <b>نصيحة للمترشحين:</b> لا تحفظ التعريف وحده. لكل مصطلح هنا <b>سطر شرح عملي</b>
          يبيّن أثره داخل الفصل — وهذا بالضبط ما يميّز الجواب الممتاز في المباراة:
          أن توظّف المفهوم في تحليل وضعية لا أن تستظهره.
        </div>

        <div class="note-ok">
          <b>🎯 اختبر ما راجعته:</b> بعد قراءة المصطلحات، راجعها في
          <a href="/bitaqat/">بطاقات المراجعة</a> — تعرض عليك المصطلح وتطلب التعريف من ذاكرتك —
          ثم اختبر نفسك في <a href="/ikhtibarat/">الاختبارات التفاعلية</a>
          (<?= array_sum(array_map(static fn (array $z): int => count($z['qs']), tb_data('ikhtibarat')['quizzes'] ?? [])) ?> سؤالًا بتصحيح فوري).
          الاسترجاع من الذاكرة يثبّت المعلومة أقوى من إعادة القراءة بكثير.
        </div>

        <h2>للتعمق في كل مجال</h2>
        <div class="table-wrap">
          <table>
            <thead><tr><th>المجال</th><th>المقال المفصّل</th></tr></thead>
            <tbody>
              <tr><td>البيداغوجيا والكفايات</td><td><a href="/maqalat/bidaghojia-alkafayat">بيداغوجيا الكفايات: من الهدف إلى الكفاية</a></td></tr>
              <tr><td>الديداكتيك</td><td><a href="/maqalat/didaktik-3ama-wa-khassa">الديداكتيك العامة والخاصة</a></td></tr>
              <tr><td>نظريات التعلم</td><td><a href="/maqalat/nadariyat-attaallom">نظريات التعلم الأربع</a></td></tr>
              <tr><td>التقويم</td><td><a href="/maqalat/anwa3-attaqwim">أنواع التقويم الثلاثة</a></td></tr>
              <tr><td>الفوارق والدعم</td><td><a href="/maqalat/bidaghojia-alfariqiya">البيداغوجيا الفارقية</a></td></tr>
              <tr><td>التخطيط</td><td><a href="/maqalat/kayfa-tabni-jadada">كيف تبني جذاذة درس ناجحة</a></td></tr>
            </tbody>
          </table>
        </div>

        <div class="note-info">
          <b>ينقصك مصطلح؟</b> راسلنا على <a href="mailto:<?= e(MAIL_MAIN) ?>"><?= e(MAIL_MAIN) ?></a>
          وسنضيفه. المعجم يُحدَّث باستمرار.
        </div>
      </div>
    </div>
  </section>
</div>

<script>
function moujam() {
  return {
    q: '',
    cat: '',
    shown: 0,

    /**
     * دالة نقية تمامًا: تقرّر ظهور بطاقة مصطلح ولا تُعدّل أي حالة.
     * ⚠️ لا تضع أي إسناد داخلها — تعديل خاصية تفاعلية داخل تعبير x-show
     * يُنشئ حلقة تحديث لا نهائية توقف Alpine ويختفي كل المحتوى.
     */
    ok(el) {
      var q = this.q.trim();

      return (this.cat === '' || el.dataset.c === this.cat) &&
             (q === '' || el.dataset.h.indexOf(q) !== -1);
    },

    /** يُحسب العدّاد خارج دورة العرض، عبر $watch */
    recount() {
      var q = this.q.trim(), cat = this.cat;
      this.shown = Array.prototype.filter.call(
        document.querySelectorAll('.term'),
        function (el) {
          return (cat === '' || el.dataset.c === cat) &&
                 (q === '' || el.dataset.h.indexOf(q) !== -1);
        }
      ).length;
    },

    /** ينسخ رابط المصطلح ويؤكد بصريًا على الزر نفسه */
    copy(slug, ev) {
      /* يُنسخ رابط صفحة المصطلح المستقلة لا مرساة داخل الفهرس */
      var url = location.origin + '/moujam/' + slug;
      var btn = ev.currentTarget;
      var done = function () {
        var icon = btn.querySelector('i');
        icon.className = 'fas fa-check';
        btn.classList.add('text-emerald-600');
        setTimeout(function () {
          icon.className = 'fas fa-link';
          btn.classList.remove('text-emerald-600');
        }, 1400);
      };

      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(done).catch(function () { location.hash = slug; });
      } else {
        /* بديل للمتصفحات القديمة أو الاتصال غير الآمن */
        var ta = document.createElement('textarea');
        ta.value = url;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); done(); } catch (e) { location.hash = slug; }
        document.body.removeChild(ta);
      }
    },

    init() {
      this.recount();
      this.$watch('q', function () { this.recount(); }.bind(this));
      this.$watch('cat', function () { this.recount(); }.bind(this));

      /* فتح المصطلح المقصود عند الوصول برابط #slug */
      if (location.hash) {
        var el = document.querySelector(decodeURIComponent(location.hash));
        if (el) { setTimeout(function () { el.scrollIntoView({ block: 'center' }); }, 150); }
      }
    }
  };
}
</script>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
