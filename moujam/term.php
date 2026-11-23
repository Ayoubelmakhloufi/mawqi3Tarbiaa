<?php
/**
 * صفحة مصطلح مستقلة — /moujam/<المعرّف>
 *
 * تُبنى آليًا من data/moujam.json، فكل مصطلح يُضاف إلى المعجم تصير له صفحته
 * ويدخل خريطة الموقع وحده. لا صيانة ولا ملف بيانات إضافي.
 *
 * التوجيه: قاعدة في .htaccess تُحوّل /moujam/<slug> إلى هذا الملف مع ?s=<slug>
 * (ويحاكيها _dev-router.php محليًا).
 */

require __DIR__ . '/../partials/lib.php';

$M     = tb_data('moujam');
$terms = $M['terms'] ?? [];
$cats  = $M['cats'] ?? [];

$want = (string) ($_GET['s'] ?? '');

$idx = -1;
foreach ($terms as $k => $t) {
    if (($t['s'] ?? '') === $want) {
        $idx = $k;
        break;
    }
}

/* معرّف غير معروف: صفحة 404 حقيقية لا صفحة فارغة */
if ($idx < 0) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

$term = $terms[$idx];
$prev = $terms[$idx - 1] ?? null;
$next = $terms[$idx + 1] ?? null;

$cat = null;
foreach ($cats as $c) {
    if ($c['slug'] === ($term['c'] ?? '')) {
        $cat = $c;
        break;
    }
}
$tint = tb_tint($cat['tint'] ?? 'emerald');

/* خريطة الاسم → المعرّف لتحويل «انظر أيضًا» إلى روابط */
$bySlug = [];
foreach ($terms as $t) {
    $bySlug[$t['t']] = $t['s'];
}

/* مصطلحات أخرى من المجال نفسه */
$sameCat = array_values(array_filter(
    $terms,
    static fn (array $t): bool => ($t['c'] ?? '') === ($term['c'] ?? '') && $t['s'] !== $term['s']
));
shuffle($sameCat);
$sameCat = array_slice($sameCat, 0, 6);

/* الاختبار الموافق للمجال، إن عُرِّف في moujam.json */
$quizSlug  = $cat['quiz'] ?? null;
$quizTitle = null;
if ($quizSlug) {
    foreach (tb_data('ikhtibarat')['quizzes'] ?? [] as $z) {
        if ($z['slug'] === $quizSlug) {
            $quizTitle = $z['title'];
            break;
        }
    }
}

/* وصف الميتا: التعريف نفسه، مقصوصًا عند حدّ حرف كامل إن طال */
$desc = trim($term['d']);
if (strlen($desc) > 300) {
    $desc = rtrim(tb_utf8_head($desc, 290)) . '…';
}

$meta = [
    'title'       => $term['t'] . ' — تعريفه ودلالته في علوم التربية',
    'description' => $desc,
    'canonical'   => '/moujam/' . $term['s'],
    'image'       => '/assets/img/og-dourous.jpg',
    'section'     => 'dourous',
    'breadcrumb'  => [
        ['name' => 'دروس علوم التربية', 'url' => '/dourous/'],
        ['name' => 'معجم المصطلحات', 'url' => '/moujam/'],
        ['name' => $term['t'], 'url' => '/moujam/' . $term['s']],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'DefinedTerm',
        'name'        => $term['t'],
        'description' => $term['d'],
        'url'         => tb_abs('/moujam/' . $term['s']),
        'inLanguage'  => 'ar',
        'inDefinedTermSet' => [
            '@type' => 'DefinedTermSet',
            'name'  => 'معجم المصطلحات التربوية',
            'url'   => tb_abs('/moujam/'),
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
        <li class="text-slate-700" aria-current="page"><?= e($term['t']) ?></li>
      </ol>
    </nav>

    <div class="max-w-3xl">
      <?php if ($cat): ?>
      <a href="/moujam/majal/<?= e($cat['slug']) ?>"
         class="inline-flex items-center gap-1.5 text-xs font-black px-3 py-1.5 rounded-full <?= e($tint['bg']) ?> <?= e($tint['text']) ?> ring-1 <?= e($tint['ring']) ?> mb-4 hover:underline">
        <i class="fas <?= e($cat['icon']) ?> text-[10px]"></i>
        <?= e($cat['title']) ?>
      </a>
      <?php endif; ?>
      <h1 class="text-3xl md:text-5xl font-black text-primary leading-tight mb-4"><?= e($term['t']) ?></h1>
      <p class="text-lg md:text-xl text-slate-700 leading-relaxed font-medium"><?= e($term['d']) ?></p>
    </div>
  </div>
</section>

<section class="py-10 md:py-14 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl">

      <?php if (!empty($term['x'])): ?>
      <div class="bg-white rounded-2xl ring-1 ring-slate-200 p-6 md:p-7 mb-6">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-3">
          <i class="fas fa-lightbulb text-accent"></i> ماذا يعني هذا داخل الفصل؟
        </h2>
        <p class="text-slate-700 leading-relaxed text-base"><?= e($term['x']) ?></p>
      </div>
      <?php endif; ?>

      <?php
      $links = array_values(array_filter(
          $term['r'] ?? [],
          static fn (string $r): bool => isset($bySlug[$r])
      ));
      if ($links): ?>
      <div class="bg-white rounded-2xl ring-1 ring-slate-200 p-6 md:p-7 mb-6">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-4">
          <i class="fas fa-diagram-project text-accent"></i> مفاهيم مرتبطة
        </h2>
        <p class="text-slate-500 leading-relaxed text-[15px] mb-4">
          المفاهيم التربوية شبكة لا قائمة — وفهم الروابط بينها أهم من حفظ التعاريف منفصلة.
        </p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($links as $r): ?>
          <a href="/moujam/<?= e($bySlug[$r]) ?>"
             class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-primary hover:text-white transition-colors"><?= e($r) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- خطوتك التالية -->
      <div class="bg-white rounded-2xl ring-1 ring-slate-200 p-6 md:p-7 mb-6 article-body">
        <h2>بعد أن فهمت المصطلح</h2>
        <p>
          <b>لا تكتفِ بالتعريف.</b> ما يميّز الجواب الممتاز في المباراة أن توظّف المفهوم
          في تحليل وضعية، لا أن تستظهره. جرّب هذه الخطوات الثلاث:
        </p>
        <ol>
          <li><b>استرجعه من ذاكرتك</b> في <a href="/bitaqat/">بطاقات المراجعة</a> بعد يومين لا بعد ساعة.</li>
          <?php if ($quizSlug && $quizTitle): ?>
          <li><b>اختبر فهمك</b> في <a href="/ikhtibarat/<?= e($quizSlug) ?>/">اختبار <?= e($quizTitle) ?></a> — بتصحيح فوري وشرح لكل جواب.</li>
          <?php else: ?>
          <li><b>اختبر فهمك</b> في <a href="/ikhtibarat/">الاختبارات التفاعلية</a> — بتصحيح فوري وشرح لكل جواب.</li>
          <?php endif; ?>
          <li><b>اربطه بمثال من قسمك</b> أو من درس تابعته. المفهوم الذي لا مثال له يُنسى بسرعة.</li>
        </ol>
      </div>

      <?php if ($sameCat): ?>
      <h2 class="text-xl font-black text-slate-900 mb-4">مصطلحات أخرى في <?= e($cat['title'] ?? 'المجال نفسه') ?></h2>
      <div class="grid gap-3 sm:grid-cols-2 mb-8">
        <?php foreach ($sameCat as $s): ?>
        <a href="/moujam/<?= e($s['s']) ?>"
           class="group bg-white rounded-2xl ring-1 ring-slate-200/80 p-5 hover:ring-primary/30 hover:shadow-md transition-all">
          <span class="block font-black text-slate-800 group-hover:text-primary transition-colors mb-2"><?= e($s['t']) ?></span>
          <span class="block text-sm text-slate-500 leading-relaxed"><?= e($s['d']) ?></span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- السابق والتالي -->
      <div class="flex flex-wrap items-start gap-3">
        <?php if ($prev): ?>
        <a href="/moujam/<?= e($prev['s']) ?>" class="flex-1 bg-white ring-1 ring-slate-200 rounded-2xl p-4 hover:ring-primary/30 transition-all">
          <span class="block text-xs font-bold text-slate-400 mb-1"><i class="fas fa-angle-right"></i> السابق</span>
          <span class="block font-bold text-slate-700"><?= e($prev['t']) ?></span>
        </a>
        <?php endif; ?>
        <?php if ($next): ?>
        <a href="/moujam/<?= e($next['s']) ?>" class="flex-1 bg-white ring-1 ring-slate-200 rounded-2xl p-4 hover:ring-primary/30 transition-all">
          <span class="block text-xs font-bold text-slate-400 mb-1">التالي <i class="fas fa-angle-left"></i></span>
          <span class="block font-bold text-slate-700"><?= e($next['t']) ?></span>
        </a>
        <?php endif; ?>
      </div>

      <p class="mt-6 text-center">
        <a href="/moujam/" class="inline-flex items-center gap-2 text-primary font-bold hover:underline">
          <i class="fas fa-list text-xs"></i> عد إلى المعجم كاملًا (<?= count($terms) ?> مصطلحًا)
        </a>
      </p>

    </div>
  </div>
</section>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
