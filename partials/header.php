<?php
/**
 * الهيدر الموحّد — يُعدَّل هنا مرة واحدة فيتغيّر في كل صفحات الموقع.
 *
 * كل صفحة تعرّف $meta قبل استدعاء هذا الملف:
 *   'title'       عنوان الصفحة (بدون اسم الموقع، يُضاف تلقائيًا)
 *   'description' وصف الميتا (150-160 حرفًا)
 *   'canonical'   المسار من الجذر، مثل '/dourous/'
 *   'image'       صورة المشاركة (اختياري)
 *   'section'     معرّف القسم النشط لإبرازه في القائمة (اختياري)
 *   'type'        website (افتراضي) أو article
 *   'breadcrumb'  مصفوفة [['name'=>..,'url'=>..], ...] (اختياري)
 *   'jsonld'      بيانات منظمة إضافية (اختياري)
 *   'noindex'     true لمنع الفهرسة (اختياري)
 */

if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}

$meta = array_merge([
    'title'       => SITE_NAME,
    'description' => SITE_TAG,
    'canonical'   => '/',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => null,
    'type'        => 'website',
    'breadcrumb'  => [],
    'jsonld'      => [],
    'noindex'     => false,
], $meta ?? []);

$isHome    = $meta['canonical'] === '/';
$pageTitle = $isHome
    ? SITE_NAME . ' — ' . SITE_TAG
    : $meta['title'] . ' | ' . SITE_NAME;
$canonical = tb_abs($meta['canonical']);

/* ــــ البيانات المنظمة ــــ */
$schemas = [];

if ($isHome) {
    $schemas[] = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        '@id'      => SITE_URL . '/#organization',
        'name'     => SITE_NAME,
        'url'      => SITE_URL . '/',
        'logo'     => tb_abs('/assets/img/logo-512.png'),
        'email'    => MAIL_MAIN,
        'sameAs'   => [FB_URL, IG_URL, WA_URL],
    ];
    $schemas[] = [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        '@id'             => SITE_URL . '/#website',
        'name'            => SITE_NAME,
        'url'             => SITE_URL . '/',
        'inLanguage'      => 'ar',
        'publisher'       => ['@id' => SITE_URL . '/#organization'],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => SITE_URL . '/baht/?q={search_term_string}'],
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

if ($meta['breadcrumb']) {
    $items = [['name' => 'الرئيسية', 'url' => '/']];
    array_push($items, ...$meta['breadcrumb']);
    $schemas[] = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => array_map(
            static fn (int $i, array $c): array => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $c['name'],
                'item'     => tb_abs($c['url']),
            ],
            array_keys($items),
            $items
        ),
    ];
}

foreach ($meta['jsonld'] as $extra) {
    $schemas[] = $extra;
}

/* ــــ القائمة ــــ */
$navGroups = [
    ['label' => 'المباريات والمسالك', 'group' => 'concours'],
    ['label' => 'التعلّم والموارد',   'group' => 'maktaba'],
];
$active = $meta['section'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($meta['description']) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<?php if ($meta['noindex']): ?>
<meta name="robots" content="noindex, follow">
<?php else: ?>
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
<?php endif; ?>

<meta property="og:type" content="<?= e($meta['type']) ?>">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:locale" content="ar_MA">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<meta property="og:description" content="<?= e($meta['description']) ?>">
<meta property="og:image" content="<?= e(tb_image($meta['image'])) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($meta['title']) ?>">
<meta name="twitter:description" content="<?= e($meta['description']) ?>">
<meta name="twitter:image" content="<?= e(tb_image($meta['image'])) ?>">

<meta name="theme-color" content="#1F4529">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="icon" href="/assets/img/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="/assets/css/tarbiaa.css?v=1">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

<link rel="alternate" type="application/rss+xml" title="<?= e(SITE_NAME) ?> — أحدث المقالات" href="/rss.xml">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<?php foreach ($schemas as $schema): ?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php endforeach; ?>
</head>
<body class="font-tajawal bg-slate-50 text-slate-900 overflow-x-hidden antialiased">

<a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:right-4 focus:z-[200] focus:bg-primary focus:text-white focus:px-5 focus:py-3 focus:rounded-xl">تخطَّ إلى المحتوى</a>

<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/70" x-data="{ mobileMenu: false, open: null }" @keydown.escape="open = null; mobileMenu = false">
  <div class="container mx-auto px-4 md:px-6">
    <div class="flex items-center justify-between gap-4 py-3">

      <a href="/" class="flex items-center gap-2.5 shrink-0" aria-label="<?= e(SITE_NAME) ?> — الصفحة الرئيسية">
        <img src="/assets/img/logo.svg" alt="" width="44" height="44" class="w-10 h-10 md:w-11 md:h-11 object-contain">
        <span class="text-xl md:text-2xl font-black text-primary tracking-tighter">منصة تربية</span>
      </a>

      <nav class="hidden lg:flex items-center gap-1 font-bold text-[15px] text-slate-600" aria-label="القائمة الرئيسية">
        <a href="/" class="px-3 py-2 rounded-lg hover:text-primary hover:bg-slate-100 transition-colors<?= $active === 'home' ? ' text-primary bg-slate-100' : '' ?>">الرئيسية</a>

        <?php foreach ($navGroups as $i => $g):
            $children  = tb_sections_in_group($g['group']);
            $inThisTab = in_array($active, array_column($children, 'slug'), true); ?>
        <div class="relative" @mouseenter="open = <?= $i ?>" @mouseleave="open = null">
          <button type="button" @click="open = (open === <?= $i ?> ? null : <?= $i ?>)"
                  :aria-expanded="open === <?= $i ?> ? 'true' : 'false'"
                  class="flex items-center gap-1.5 px-3 py-2 rounded-lg hover:text-primary hover:bg-slate-100 transition-colors<?= $inThisTab ? ' text-primary bg-slate-100' : '' ?>">
            <?= e($g['label']) ?>
            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="open === <?= $i ?> && 'rotate-180'"></i>
          </button>
          <div x-show="open === <?= $i ?>" x-cloak x-transition.opacity.duration.150ms
               class="absolute top-full right-0 pt-2 w-72">
            <ul class="bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 p-2 overflow-hidden">
              <?php foreach ($children as $child): $t = tb_tint($child['tint']); ?>
              <li>
                <a href="<?= e(tb_section_url($child['slug'])) ?>" class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors<?= $active === $child['slug'] ? ' bg-slate-50' : '' ?>">
                  <span class="w-9 h-9 shrink-0 rounded-lg <?= e($t['bg']) ?> <?= e($t['text']) ?> flex items-center justify-center">
                    <i class="fas <?= e($child['icon']) ?>"></i>
                  </span>
                  <span class="min-w-0">
                    <span class="block font-bold text-slate-800 leading-snug"><?= e($child['nav']) ?></span>
                    <span class="block text-xs font-medium text-slate-500 mt-0.5"><?= e($child['tagline']) ?></span>
                  </span>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <?php endforeach; ?>

        <?php /* أقسام المجموعة main تُولَّد من sections.json لا تُكتب يدويًا،
                 حتى لا يسقط قسم من القائمة عند أي تعديل. */ ?>
        <?php foreach (tb_sections_in_group('main') as $mainSec): ?>
        <a href="<?= e(tb_section_url($mainSec['slug'])) ?>"
           class="px-3 py-2 rounded-lg hover:text-primary hover:bg-slate-100 transition-colors<?= $active === $mainSec['slug'] ? ' text-primary bg-slate-100' : '' ?>"><?= e($mainSec['nav']) ?></a>
        <?php endforeach; ?>

        <!-- مدخل الزائر الجديد: يبقى ظاهرًا لأنه أهم رابط لمن يصل أول مرة -->
        <a href="/ibda-min-huna" class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-accent hover:bg-slate-100 transition-colors<?= $active === 'ibda' ? ' bg-slate-100' : '' ?>">
          <i class="fas fa-compass text-xs"></i> ابدأ من هنا
        </a>
      </nav>

      <div class="flex items-center gap-2">
        <a href="/baht/" aria-label="البحث في الموقع" class="w-10 h-10 rounded-full hover:bg-slate-100 text-slate-500 hover:text-primary flex items-center justify-center transition-colors">
          <i class="fas fa-magnifying-glass"></i>
        </a>

        <!-- رابط تطبيق تربية: ظاهر دائمًا في كل الصفحات وعلى كل الشاشات -->
        <a href="/tatbiq/" class="flex items-center gap-2 bg-primary text-white px-3.5 md:px-5 py-2.5 rounded-full font-bold text-sm hover:bg-secondary transition-all shadow-lg shadow-primary/20 whitespace-nowrap<?= $active === 'tatbiq' ? ' ring-2 ring-accent ring-offset-2' : '' ?>">
          <i class="fas fa-mobile-screen-button"></i>
          <span>تطبيق تربية</span>
        </a>

        <button type="button" @click="mobileMenu = !mobileMenu" :aria-expanded="mobileMenu ? 'true' : 'false'"
                aria-label="فتح القائمة" class="lg:hidden w-10 h-10 rounded-lg text-xl text-primary hover:bg-slate-100 flex items-center justify-center">
          <i class="fas" :class="mobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- قائمة الهاتف -->
  <div x-show="mobileMenu" x-cloak x-transition.opacity.duration.150ms
       class="lg:hidden bg-white border-t border-slate-200 shadow-xl max-h-[75vh] overflow-y-auto">
    <div class="container mx-auto px-4 py-4 space-y-1">
      <a href="/" class="block px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50">الرئيسية</a>
      <a href="/ibda-min-huna" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50<?= $active === 'ibda' ? ' bg-slate-50' : '' ?>">
        <span class="w-9 h-9 shrink-0 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
          <i class="fas fa-compass"></i>
        </span>
        <span class="font-bold text-slate-700">ابدأ من هنا</span>
      </a>
      <?php foreach (tb_sections() as $navSec): $t = tb_tint($navSec['tint']); ?>
      <a href="<?= e(tb_section_url($navSec['slug'])) ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50<?= $active === $navSec['slug'] ? ' bg-slate-50' : '' ?>">
        <span class="w-9 h-9 shrink-0 rounded-lg <?= e($t['bg']) ?> <?= e($t['text']) ?> flex items-center justify-center">
          <i class="fas <?= e($navSec['icon']) ?>"></i>
        </span>
        <span class="font-bold text-slate-700"><?= e($navSec['nav']) ?></span>
      </a>
      <?php endforeach; ?>
      <a href="/baht/" class="block px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50">البحث في الموقع</a>
      <a href="/tatbiq/" class="flex items-center justify-center gap-2 bg-primary text-white text-center py-3.5 rounded-xl font-black mt-3">
        <i class="fas fa-mobile-screen-button"></i> تطبيق تربية
      </a>
    </div>
  </div>
</header>

<main id="main">
