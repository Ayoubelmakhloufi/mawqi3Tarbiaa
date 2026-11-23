<?php
/**
 * صفحة مجال في المعجم — /moujam/majal/<المعرّف>
 *
 * تُبنى آليًا من data/moujam.json. كل مجال يعرض مقدّمته ومصطلحاته كلها
 * ويربط بالاختبار الموافق له (حقل quiz في تعريف الفئة).
 *
 * ⚠️ المسار مستقل عمدًا (/majal/) لأن معرّفي الفئتين bidaghojia و didaktik
 * يطابقان معرّفَي مصطلحين، فلو وُضعت الفئات على /moujam/<slug> لتصادمت معهما.
 */

require __DIR__ . '/../partials/lib.php';

$M     = tb_data('moujam');
$cats  = $M['cats'] ?? [];
$terms = $M['terms'] ?? [];

$want = (string) ($_GET['c'] ?? '');

$cat = null;
$pos = -1;
foreach ($cats as $k => $c) {
    if ($c['slug'] === $want) {
        $cat = $c;
        $pos = $k;
        break;
    }
}

if (!$cat) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

$mine = array_values(array_filter($terms, static fn (array $t): bool => ($t['c'] ?? '') === $cat['slug']));
$tint = tb_tint($cat['tint']);

/* الاختبار الموافق */
$quizTitle = null;
$quizCount = 0;
if (!empty($cat['quiz'])) {
    foreach (tb_data('ikhtibarat')['quizzes'] ?? [] as $z) {
        if ($z['slug'] === $cat['quiz']) {
            $quizTitle = $z['title'];
            $quizCount = count($z['qs']);
            break;
        }
    }
}

/* المجالات الأخرى للتنقل */
$others = array_values(array_filter($cats, static fn (array $c): bool => $c['slug'] !== $cat['slug']));

$meta = [
    'title'       => $cat['title'] . ' — ' . tb_terms_ar(count($mine)) . ' بشرح عملي',
    'description' => 'مصطلحات ' . $cat['title'] . ' في علوم التربية: ' . tb_terms_ar(count($mine))
                   . ' بتعريف دقيق وشرح عملي يبيّن أثره داخل الفصل، مع اختبار تفاعلي للتحقق من فهمك.',
    'canonical'   => '/moujam/majal/' . $cat['slug'],
    'image'       => '/assets/img/og-dourous.jpg',
    'section'     => 'dourous',
    'breadcrumb'  => [
        ['name' => 'دروس علوم التربية', 'url' => '/dourous/'],
        ['name' => 'معجم المصطلحات', 'url' => '/moujam/'],
        ['name' => $cat['title'], 'url' => '/moujam/majal/' . $cat['slug']],
    ],
    'jsonld'      => [[
        '@context'   => 'https://schema.org',
        '@type'      => 'CollectionPage',
        'name'       => 'مصطلحات ' . $cat['title'],
        'url'        => tb_abs('/moujam/majal/' . $cat['slug']),
        'inLanguage' => 'ar',
        'isPartOf'   => ['@id' => SITE_URL . '/#website'],
        'mainEntity' => [
            '@type'         => 'ItemList',
            'numberOfItems' => count($mine),
            'itemListElement' => array_map(
                static fn (int $i, array $t): array => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'url'      => tb_abs('/moujam/' . $t['s']),
                    'name'     => $t['t'],
                ],
                array_keys($mine),
                $mine
            ),
        ],
    ]],
];

require __DIR__ . '/../partials/header.php';
?>

<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-400">
        <li><a href="/" class="hover:text-primary transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/dourous/" class="hover:text-primary transition-colors">دروس علوم التربية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/moujam/" class="hover:text-primary transition-colors">المعجم</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-slate-700" aria-current="page"><?= e($cat['title']) ?></li>
      </ol>
    </nav>

    <div class="max-w-3xl">
      <span class="w-16 h-16 rounded-2xl <?= e($tint['bg']) ?> <?= e($tint['text']) ?> ring-1 <?= e($tint['ring']) ?> flex items-center justify-center text-2xl mb-5">
        <i class="fas <?= e($cat['icon']) ?>"></i>
      </span>
      <h1 class="text-3xl md:text-5xl font-black text-primary leading-tight mb-4"><?= e($cat['title']) ?></h1>
      <p class="text-lg text-slate-700 leading-relaxed"><?= $cat['intro'] ?? '' ?></p>
    </div>
  </div>
</section>

<section class="py-10 md:py-14 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl">

      <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <h2 class="text-2xl font-black text-slate-900">مصطلحات هذا المجال</h2>
        <span class="text-sm font-bold text-slate-400"><?= e(tb_terms_ar(count($mine))) ?></span>
      </div>

      <div class="space-y-3 mb-10">
        <?php foreach ($mine as $t): ?>
        <a href="/moujam/<?= e($t['s']) ?>"
           class="group block bg-white rounded-2xl ring-1 ring-slate-200/80 p-5 md:p-6 hover:ring-primary/30 hover:shadow-md transition-all">
          <span class="block text-lg font-black text-slate-800 group-hover:text-primary transition-colors mb-2"><?= e($t['t']) ?></span>
          <span class="block text-slate-600 leading-relaxed"><?= e($t['d']) ?></span>
        </a>
        <?php endforeach; ?>
      </div>

      <?php if ($quizTitle): ?>
      <a href="/ikhtibarat/<?= e($cat['quiz']) ?>/"
         class="group flex flex-col md:flex-row items-start gap-5 bg-primary rounded-3xl p-6 md:p-8 mb-8 hover:bg-secondary transition-colors">
        <span class="w-12 h-12 shrink-0 rounded-2xl bg-white/10 text-gold flex items-center justify-center text-xl">
          <i class="fas fa-clipboard-check"></i>
        </span>
        <span class="flex-1 min-w-0">
          <span class="block text-xl font-black text-white mb-2">اختبر فهمك: <?= e($quizTitle) ?></span>
          <span class="block text-slate-300 leading-relaxed">
            <b class="text-gold"><?= $quizCount ?> سؤالًا</b> بتصحيح فوري وشرح لكل جواب.
            الاسترجاع تحت ضغط السؤال يكشف ما لا تكشفه القراءة.
          </span>
        </span>
        <span class="shrink-0 inline-flex items-center gap-2 bg-white/10 text-white font-black rounded-xl px-5 py-3 group-hover:gap-3 transition-all">
          ابدأ <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>
      <?php endif; ?>

      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body mb-8">
        <h2>كيف تراجع هذا المجال؟</h2>
        <ol>
          <li><b>اقرأ المصطلحات مرة واحدة</b> لتكوّن صورة عامة — لا تحاول الحفظ في هذه المرحلة.</li>
          <li><b>افتح <a href="/bitaqat/">بطاقات المراجعة</a></b> وصفِّ على هذا المجال:
              البطاقة تعرض المصطلح وتطلب التعريف من ذاكرتك قبل كشفه.</li>
          <?php if ($quizTitle): ?>
          <li><b>اختبر نفسك</b> في <a href="/ikhtibarat/<?= e($cat['quiz']) ?>/">اختبار <?= e($quizTitle) ?></a>،
              ثم استعمل زر «أعد ما أخطأت فيه فقط».</li>
          <?php endif; ?>
          <li><b>عد بعد يومين</b> لا بعد ساعة — التباعد هو ما يثبّت المعلومة.</li>
        </ol>
      </div>

      <h2 class="text-xl font-black text-slate-900 mb-4">مجالات أخرى في المعجم</h2>
      <div class="grid gap-3 sm:grid-cols-2">
        <?php foreach ($others as $o):
            $ot = tb_tint($o['tint']);
            $on = count(array_filter($terms, static fn (array $t): bool => ($t['c'] ?? '') === $o['slug'])); ?>
        <a href="/moujam/majal/<?= e($o['slug']) ?>"
           class="group flex items-center gap-4 bg-white rounded-2xl ring-1 ring-slate-200/80 p-5 hover:ring-primary/30 hover:shadow-md transition-all">
          <span class="w-10 h-10 shrink-0 rounded-xl <?= e($ot['bg']) ?> <?= e($ot['text']) ?> flex items-center justify-center">
            <i class="fas <?= e($o['icon']) ?>"></i>
          </span>
          <span class="min-w-0">
            <span class="block font-black text-slate-800 group-hover:text-primary transition-colors"><?= e($o['title']) ?></span>
            <span class="block text-xs font-bold text-slate-400 mt-0.5"><?= e(tb_terms_ar($on)) ?></span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>

      <p class="mt-8 text-center">
        <a href="/moujam/" class="inline-flex items-center gap-2 text-primary font-bold hover:underline">
          <i class="fas fa-list text-xs"></i> المعجم كاملًا (<?= e(tb_terms_ar(count($terms))) ?>)
        </a>
      </p>

    </div>
  </div>
</section>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
