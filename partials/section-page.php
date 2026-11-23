<?php
/**
 * محرّك صفحات الأقسام — تستدعيه كل صفحة قسم بعد تعريف:
 *   $sectionSlug   معرّف القسم في data/sections.json   (إلزامي)
 *   $sectionIntro  نص تعريفي بصيغة HTML                (إلزامي)
 *   $sectionFaq    [['q' => .., 'a' => ..], ...]        (اختياري)
 *   $sectionExtra  HTML إضافي يُعرض قبل قائمة المقالات   (اختياري)
 */

if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}
require_once __DIR__ . '/card.php';

$section = tb_section($sectionSlug ?? null);
if (!$section) {
    http_response_code(500);
    exit('قسم غير معرَّف: ' . e((string) ($sectionSlug ?? '')));
}

$tint     = tb_tint($section['tint']);
$articles = tb_articles_in($section['slug']);
$featured = $articles[0] ?? null;
$rest     = array_slice($articles, 1);
$faq      = $sectionFaq ?? [];

$meta = [
    'title'       => $section['title'],
    'description' => $section['desc'],
    'canonical'   => tb_section_url($section['slug']),
    'section'     => $section['slug'],
    'image'       => '/assets/img/og-' . $section['slug'] . '.jpg',
    'breadcrumb'  => [['name' => $section['nav'], 'url' => tb_section_url($section['slug'])]],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => $section['title'],
        'description' => $section['desc'],
        'url'         => tb_abs(tb_section_url($section['slug'])),
        'inLanguage'  => 'ar',
        'isPartOf'    => ['@id' => SITE_URL . '/#website'],
        'mainEntity'  => [
            '@type'           => 'ItemList',
            'numberOfItems'   => count($articles),
            'itemListElement' => array_map(
                static fn (int $i, array $a): array => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'url'      => tb_abs(tb_article_url($a)),
                    'name'     => $a['title'],
                ],
                array_keys($articles),
                $articles
            ),
        ],
    ]],
];

if ($faq) {
    $meta['jsonld'][] = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(static fn (array $f): array => [
            '@type'          => 'Question',
            'name'           => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])],
        ], $faq),
    ];
}

require __DIR__ . '/header.php';
?>

<!-- ترويسة القسم -->
<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-16">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-400">
        <li><a href="/" class="hover:text-primary transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-slate-700" aria-current="page"><?= e($section['nav']) ?></li>
      </ol>
    </nav>

    <div class="flex flex-col md:flex-row md:items-start gap-6 max-w-4xl">
      <span class="w-16 h-16 shrink-0 rounded-2xl <?= e($tint['bg']) ?> <?= e($tint['text']) ?> flex items-center justify-center text-2xl ring-1 <?= e($tint['ring']) ?>">
        <i class="fas <?= e($section['icon']) ?>"></i>
      </span>
      <div>
        <h1 class="text-3xl md:text-5xl font-black text-primary leading-tight mb-3"><?= e($section['title']) ?></h1>
        <p class="text-lg md:text-xl text-slate-500 font-medium"><?= e($section['tagline']) ?></p>
      </div>
    </div>
  </div>
</section>

<!-- النص التعريفي -->
<section class="bg-white">
  <div class="container mx-auto px-4 md:px-6 pb-14 md:pb-20">
    <div class="article-body max-w-3xl"><?= $sectionIntro ?? '' ?></div>
  </div>
</section>

<?= $sectionExtra ?? '' ?>

<!-- المقالات -->
<section class="py-14 md:py-20 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="flex items-end justify-between gap-4 mb-8">
      <h2 class="text-2xl md:text-3xl font-black text-slate-900">مقالات القسم</h2>
      <?php if ($articles): ?>
      <span class="text-sm font-bold text-slate-400"><?= e(tb_count_ar(count($articles))) ?></span>
      <?php endif; ?>
    </div>

    <?php if (!$articles): ?>
      <div class="grid"><?php tb_empty_state($section['title']); ?></div>
    <?php else: ?>
      <div class="grid gap-6 mb-6">
        <?php tb_card($featured, true, false); ?>
      </div>
      <?php if ($rest): ?>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($rest as $a) { tb_card($a, false, false); } ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php if ($faq): ?>
<!-- أسئلة شائعة -->
<section class="py-14 md:py-20 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl mx-auto">
      <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-8">أسئلة شائعة حول <?= e($section['nav']) ?></h2>
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
          <?php /* بدون x-transition عمدًا — انظر التعليق في article-bottom.php:
                   تشغيل انتقالَي «مغادرة» و«دخول» في النقرة نفسها يؤخّر تطبيق display نقرةً كاملة. */ ?>
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

<?php
require __DIR__ . '/app-cta.php';
require __DIR__ . '/footer.php';
