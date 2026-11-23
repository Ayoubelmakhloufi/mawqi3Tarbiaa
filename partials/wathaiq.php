<?php
/**
 * محرّك صفحات الوثائق الرقمية.
 *
 * تستدعيه كل صفحة ملف بعد تعريف:
 *   $wSlug   معرّف الملف في data/wathaiq.json   (إلزامي)
 *   $wIntro  نص تعريفي بصيغة HTML                (اختياري لكن مُوصى به)
 *   $wFaq    [['q' => .., 'a' => ..], ...]        (اختياري)
 *
 * مصدر البيانات: data/wathaiq.json — تعديل مكان استضافة أي وثيقة
 * يتم من هناك بتغيير الحقل "h" بين "local" و "archive"، بلا لمس أي صفحة.
 */

if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}

/* ⚠️ تُستعمل $wSec لا $section: ملف header.php يحتوي حلقة foreach باسم $section
   فتدوس على المتغير بعد استدعائه، ويختفي المفتاح code. */
$W        = tb_data('wathaiq');
$sections = $W['sections'] ?? [];
$wSec  = null;
foreach ($sections as $s) {
    if ($s['slug'] === ($wSlug ?? null)) {
        $wSec = $s;
        break;
    }
}
if (!$wSec) {
    http_response_code(500);
    exit('ملف وثائق غير معرَّف: ' . e((string) ($wSlug ?? '')));
}

$docs = array_values(array_filter($W['docs'] ?? [], static fn (array $d): bool => $d['s'] === $wSlug));
$tint = tb_tint($wSec['tint']);
$faq  = $wFaq ?? [];

/**
 * رابط تحميل الوثيقة حسب مكان استضافتها.
 *
 * وثائق أرشيف الإنترنت كلها داخل ملف مضغوط واحد، و archive.org يستخرج ملفًا منه
 * عبر المسار: {archiveBase}/{المسار داخل المضغوط} — بشرط ترميز الشرطة المائلة
 * إلى %2F، لأنها جزء من اسم المدخل لا فاصل مسار. ولهذا يُستعمل rawurlencode
 * على المسار كاملًا، ولا يُدرَج معرّف القسم هنا (بخلاف الاستضافة المحلية).
 */
function tb_doc_url(array $d, array $W): string
{
    if (($d['h'] ?? '') === 'archive') {
        return $W['archiveBase'] . '/' . rawurlencode($d['f']);
    }

    return $W['localBase'] . '/' . $d['s'] . '/' . rawurlencode($d['f']);
}

/** تنسيق الحجم بصيغة مقروءة */
function tb_kb(int $kb): string
{
    return $kb >= 1024
        ? number_format($kb / 1024, 1) . ' م.ب'
        : $kb . ' ك.ب';
}

$totalKb = array_sum(array_column($docs, 'kb'));

$meta = [
    'title'       => $wSec['title'] . ' — وثائق رسمية للتحميل',
    /* الوصف مبنيّ ليبقى ضمن 160 حرفًا دون قصّ — وتُتجنَّب دوال mb_*
       لأن امتداد mbstring قد لا يكون مفعّلًا على كل الخوادم. */
    /* الصياغة خالية من النعوت عمدًا: تمييز العدد في العربية يتغيّر بين المفرد
       والمثنى والجمع، فأي نعت لاحق يختل مع بعض الأعداد. */
    'description' => $wSec['title'] . ' — ' . tb_docs_ar(count($docs))
                     . ' بصيغة PDF للتحميل المباشر من حقيبة الوثائق الرسمية'
                     . ' للمنظومة التربوية المغربية.',
    'canonical'   => '/maktaba/wathaiq/' . $wSec['slug'] . '/',
    'image'       => '/assets/img/og-maktaba.jpg',
    'section'     => 'maktaba',
    'breadcrumb'  => [
        ['name' => 'المكتبة الرقمية', 'url' => '/maktaba/'],
        ['name' => 'حقيبة الوثائق', 'url' => '/maktaba/wathaiq/'],
        ['name' => $wSec['title'], 'url' => '/maktaba/wathaiq/' . $wSec['slug'] . '/'],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'DataCatalog',
        'name'        => $wSec['title'],
        'url'         => tb_abs('/maktaba/wathaiq/' . $wSec['slug'] . '/'),
        'inLanguage'  => 'ar',
        'publisher'   => ['@id' => SITE_URL . '/#organization'],
        'dataset'     => array_map(static fn (array $d): array => [
            '@type'         => 'Dataset',
            'name'          => $d['t'],
            'encodingFormat'=> 'application/pdf',
            'distribution'  => [
                '@type'        => 'DataDownload',
                'encodingFormat' => 'application/pdf',
                'contentUrl'   => tb_doc_url($d, $W),
            ],
        ], array_slice($docs, 0, 100)),
    ]],
];

if ($faq) {
    $meta['jsonld'][] = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(static fn (array $f): array => [
            '@type'          => 'Question',
            'name'           => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($f['a']))],
        ], $faq),
    ];
}

require __DIR__ . '/header.php';
?>

<!-- ترويسة الملف -->
<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-400">
        <li><a href="/" class="hover:text-primary transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/maktaba/" class="hover:text-primary transition-colors">المكتبة الرقمية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/maktaba/wathaiq/" class="hover:text-primary transition-colors">حقيبة الوثائق</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-slate-700" aria-current="page"><?= e($wSec['title']) ?></li>
      </ol>
    </nav>

    <div class="flex flex-col md:flex-row md:items-start gap-6 max-w-4xl">
      <span class="w-16 h-16 shrink-0 rounded-2xl <?= e($tint['bg']) ?> <?= e($tint['text']) ?> flex items-center justify-center text-2xl ring-1 <?= e($tint['ring']) ?>">
        <i class="fas <?= e($wSec['icon']) ?>"></i>
      </span>
      <div>
        <p class="text-sm font-black text-slate-400 mb-1">الملف <?= e($wSec['code']) ?></p>
        <h1 class="text-3xl md:text-4xl font-black text-primary leading-tight mb-3"><?= e($wSec['title']) ?></h1>
        <p class="text-lg text-slate-500 font-medium">
          <?= e(tb_docs_ar(count($docs))) ?> · <?= e(tb_kb($totalKb)) ?> · صيغة PDF
        </p>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($wIntro)): ?>
<section class="bg-white">
  <div class="container mx-auto px-4 md:px-6 pb-12">
    <div class="article-body max-w-3xl"><?= $wIntro ?></div>
  </div>
</section>
<?php endif; ?>

<!-- قائمة الوثائق -->
<section class="py-12 md:py-16 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto" x-data="wathaiqList()">

      <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <h2 class="text-2xl md:text-3xl font-black text-slate-900">الوثائق المتاحة</h2>
        <span class="text-sm font-bold text-slate-400"><?= e(tb_docs_ar(count($docs))) ?></span>
      </div>

      <!-- بحث داخل الملف -->
      <div class="relative mb-6">
        <input type="search" x-model="q" placeholder="ابحث في وثائق هذا الملف…"
               class="w-full rounded-2xl border-2 border-slate-200 bg-white px-5 py-4 pr-12 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
        <i class="fas fa-magnifying-glass absolute top-1/2 -translate-y-1/2 right-5 text-slate-400"></i>
      </div>

      <ul class="space-y-2.5">
        <?php
        /* عناوين المجموعات الفرعية تُخفى أثناء البحث حتى لا تبقى معلّقة بلا وثائق */
        $lastGroup = null;
        foreach ($docs as $i => $d):
            $url   = tb_doc_url($d, $W);
            $group = $d['g'] ?? null;
            /* رقم الوثيقة في اللائحة الرسمية، مأخوذ من اسم الملف taalim/NN.pdf */
            $docNum = preg_match('~(\d+)\.pdf$~', $d['f'], $m)
                ? str_pad($m[1], 3, '0', STR_PAD_LEFT)
                : '';
            if ($group !== null && $group !== $lastGroup):
                $lastGroup = $group;
                $inGroup   = count(array_filter($docs, static fn (array $x): bool => ($x['g'] ?? null) === $group)); ?>
        <li x-show="q === ''" class="pt-4">
          <h3 class="flex flex-wrap items-center gap-2.5 text-sm font-black text-slate-500 leading-relaxed">
            <i class="fas fa-folder-open text-accent"></i>
            <span><?= e($group) ?></span>
            <span class="text-slate-400 font-bold"><?= e(tb_docs_ar($inGroup)) ?></span>
          </h3>
        </li>
        <?php endif; ?>
        <li x-show="q === '' || $el.dataset.t.includes(q)" data-t="<?= e($d['t']) ?>">
          <a href="<?= e($url) ?>" download
             data-name="<?= e(tb_safe_filename($d['t'], $docNum)) ?>"
             data-size="<?= (int) $d['kb'] * 1024 ?>"
             @click.prevent="grab($event, <?= $i ?>)"
             class="group flex items-start gap-4 bg-white rounded-2xl ring-1 ring-slate-200/80 p-4 md:p-5 hover:ring-primary/30 hover:shadow-md transition-all">
            <span class="w-10 h-10 shrink-0 rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 flex items-center justify-center">
              <i class="fas fa-file-pdf"></i>
            </span>
            <span class="flex-1 min-w-0">
              <span class="block font-bold text-slate-800 leading-snug group-hover:text-primary transition-colors"><?= e($d['t']) ?></span>
              <span class="block text-xs font-bold text-slate-400 mt-1.5">
                PDF · <?= e(tb_kb($d['kb'])) ?>
                <span x-show="pct[<?= $i ?>] !== undefined" x-cloak class="text-primary">
                  · <span x-text="label(<?= $i ?>)"></span>
                </span>
              </span>
            </span>
            <span class="shrink-0 self-center text-primary opacity-60 group-hover:opacity-100 transition-opacity">
              <i class="fas" :class="pct[<?= $i ?>] === undefined ? 'fa-download' : (pct[<?= $i ?>] === 100 ? 'fa-circle-check' : 'fa-circle-notch fa-spin')"></i>
            </span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>

      <p x-show="q !== ''" x-cloak class="mt-6 text-center text-slate-400 font-bold text-sm">
        اكتب كلمة من عنوان الوثيقة لتصفيتها
      </p>

      <div class="article-body mt-10">
        <div class="note">
          <b>تنبيه مهم:</b> هذه وثائق مرجعية تُنشر للاستئناس والتكوين الذاتي.
          <b>النصوص التنظيمية تُحدَّث وتُنسخ باستمرار</b>، والمذكرات المرتبطة بسنة معينة تفقد صلاحيتها بانتهائها.
          تحقّق دائمًا من <b>سريان المفعول</b> في المصدر الرسمي قبل الاعتماد على أي وثيقة في إجراء رسمي.
        </div>
      </div>

    </div>
  </div>
</section>

<?php if ($faq): ?>
<section class="py-12 md:py-16 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-8">أسئلة شائعة</h2>
      <div class="space-y-3" x-data="{ open: 0 }">
        <?php foreach ($faq as $i => $f): ?>
        <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200/80 overflow-hidden">
          <h3>
            <button type="button" @click="open = (open === <?= $i ?> ? null : <?= $i ?>)"
                    :aria-expanded="open === <?= $i ?> ? 'true' : 'false'"
                    class="w-full flex items-center justify-between gap-4 text-right p-5 font-black text-slate-800 hover:text-primary transition-colors">
              <span><?= e($f['q']) ?></span>
              <i class="fas fa-plus text-sm shrink-0 text-primary transition-transform" :class="open === <?= $i ?> && 'rotate-45'"></i>
            </button>
          </h3>
          <div x-show="open === <?= $i ?>" x-cloak class="faq-panel">
            <div class="article-body px-5 pb-5 text-slate-600"><?= $f['a'] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- تنقّل بين ملفات الحقيبة -->
<section class="py-12 md:py-16 bg-slate-50 border-t border-slate-200">
  <div class="container mx-auto px-4 md:px-6">
    <h2 class="text-xl md:text-2xl font-black text-slate-900 mb-6">ملفات أخرى في الحقيبة</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($sections as $s):
          if ($s['slug'] === $wSlug) { continue; }
          $n = count(array_filter($W['docs'], static fn (array $d): bool => $d['s'] === $s['slug']));
          if (!$n) { continue; }
          $t = tb_tint($s['tint']); ?>
      <a href="/maktaba/wathaiq/<?= e($s['slug']) ?>/"
         class="group flex flex-col bg-white rounded-2xl ring-1 ring-slate-200/80 p-5 hover:ring-primary/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
        <span class="w-10 h-10 rounded-xl <?= e($t['bg']) ?> <?= e($t['text']) ?> ring-1 <?= e($t['ring']) ?> flex items-center justify-center mb-3">
          <i class="fas <?= e($s['icon']) ?>"></i>
        </span>
        <span class="font-black text-slate-800 leading-snug text-sm group-hover:text-primary transition-colors"><?= e($s['title']) ?></span>
        <span class="text-xs font-bold text-slate-400 mt-2"><?= e(tb_docs_ar($n)) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
/**
 * قائمة الوثائق: بحث فوري + تحميل مباشر.
 *
 * لماذا لا يكفي رابط عادي بخاصية download؟
 *   أرشيف الإنترنت يرسل «Content-Disposition: inline» فيعرض المتصفح الـPDF
 *   بدل تنزيله، وخاصية download يتجاهلها المتصفح عبر النطاقات (cross-origin).
 *   فالطريق الوحيد لبدء تحميل فوري هو جلب الملف وتحويله إلى Blob محلي —
 *   و CORS مسموح من أرشيف الإنترنت لأي نطاق فيعمل هذا.
 *
 * ومكسب إضافي: يُحفظ الملف باسمه العربي الكامل بدل «01.pdf».
 */
function wathaiqList() {
  return {
    q: '',
    pct: {},   /* فهرس الوثيقة ← نسبة التحميل، أو -1 قبل وصول أول بايت */

    init() {
      var self = this;
      /* التحميل يجري في الصفحة نفسها، فمغادرتها تُلغيه. والوثائق الكبيرة
         قد تستغرق دقائق لأن أرشيف الإنترنت يستخرجها من مضغوط لحظيًا. */
      window.addEventListener('beforeunload', function (ev) {
        for (var k in self.pct) {
          if (self.pct[k] < 100) {
            ev.preventDefault();
            ev.returnValue = '';

            return '';
          }
        }
      });
    },

    label(i) {
      var p = this.pct[i];
      if (p === undefined) { return ''; }
      if (p >= 100) { return 'اكتمل التحميل'; }
      if (p < 0) { return 'جارٍ التحميل…'; }

      return 'جارٍ التحميل ' + p + '٪';
    },

    grab(ev, i) {
      var el   = ev.currentTarget;
      var url  = el.href;
      var name = el.dataset.name;
      var size = parseInt(el.dataset.size || '0', 10);
      var self = this;

      if (this.pct[i] !== undefined) { return; }   /* تحميل جارٍ — تجاهل النقر */

      /* متصفح قديم: افتح الوثيقة بدل تعطيل الزر */
      if (!window.fetch || !window.URL || !URL.createObjectURL) {
        window.open(url, '_blank', 'noopener');

        return;
      }

      this.pct[i] = -1;

      fetch(url, { credentials: 'omit' })
        .then(function (res) {
          if (!res.ok) { throw new Error('HTTP ' + res.status); }

          /* الخادم لا يرسل Content-Length (النقل مقطّع لأن الملف يُستخرج من
             مضغوط لحظيًا)، فتُحسب النسبة على الحجم المعروف في wathaiq.json */
          if (!res.body || !res.body.getReader || !size) { return res.blob(); }

          var reader = res.body.getReader();
          var chunks = [];
          var got    = 0;

          return (function pump() {
            return reader.read().then(function (r) {
              if (r.done) { return new Blob(chunks, { type: 'application/pdf' }); }
              chunks.push(r.value);
              got += r.value.length;
              self.pct[i] = Math.min(99, Math.round((got / size) * 100));

              return pump();
            });
          })();
        })
        .then(function (blob) {
          var obj = URL.createObjectURL(blob);
          var a   = document.createElement('a');
          a.href     = obj;
          a.download = name;
          document.body.appendChild(a);
          a.click();
          a.remove();
          setTimeout(function () { URL.revokeObjectURL(obj); }, 5000);

          self.pct[i] = 100;
          setTimeout(function () { delete self.pct[i]; }, 2500);
        })
        .catch(function () {
          /* فشل الجلب (انقطاع أو حجب): افتح الوثيقة في لسان جديد بدل ترك الزر ميتًا */
          delete self.pct[i];
          window.open(url, '_blank', 'noopener');
        });
    }
  };
}
</script>

<?php
require __DIR__ . '/app-cta.php';
require __DIR__ . '/footer.php';
