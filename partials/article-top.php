<?php
/**
 * أعلى صفحة المقال.
 *
 * الاستعمال في maqalat/<slug>.php:
 *   <?php $slug = 'my-slug'; require __DIR__ . '/../partials/article-top.php'; ?>
 *   ... جسم المقال بوسوم HTML عادية ...
 *   <?php require __DIR__ . '/../partials/article-bottom.php'; ?>
 *
 * كل بيانات المقال (العنوان، الوصف، التاريخ، الصورة، القسم) تُقرأ من data/articles.json
 * اعتمادًا على $slug — مصدر حقيقة واحد لا يقبل التضارب.
 *
 * اختياريًا يمكن تعريف هذين المتغيرين قبل استدعاء هذا الملف:
 *   $articleFaq  [['q' => .., 'a' => ..], ...]  أسئلة شائعة تُعرض في آخر المقال
 *                                               وتُرسل إلى جوجل كبيانات FAQPage.
 *   $articleKey  ['نقطة', 'نقطة', ...]          خلاصة سريعة تُعرض في صندوق أعلى المقال.
 */

if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}
require_once __DIR__ . '/card.php';

$article = tb_article($slug ?? '');

if (!$article) {
    http_response_code(404);
    $meta = [
        'title'       => 'المقال غير متوفر',
        'description' => 'هذا المقال غير مسجَّل في فهرس الموقع.',
        'canonical'   => '/maqalat/' . ($slug ?? ''),
        'noindex'     => true,
    ];
    require __DIR__ . '/header.php';
    echo '<div class="container mx-auto px-6 py-24 text-center">'
       . '<h1 class="text-3xl font-black text-primary mb-4">المقال غير متوفر</h1>'
       . '<p class="text-slate-500 mb-8">لم يُعثر على «' . e((string) ($slug ?? '')) . '» في <code>data/articles.json</code>.</p>'
       . '<a href="/" class="bg-primary text-white px-6 py-3 rounded-xl font-bold">العودة إلى الرئيسية</a></div>';
    require __DIR__ . '/footer.php';
    exit;
}

$artSection = tb_section($article['section'] ?? '');
$artTint    = tb_tint($artSection['tint'] ?? 'emerald');
$artUrl     = tb_article_url($article);

$meta = [
    'title'       => $article['title'],
    'description' => $article['description'] ?? '',
    'canonical'   => $artUrl,
    'image'       => $article['image'] ?? null,
    'section'     => $article['section'] ?? null,
    'type'        => 'article',
    'breadcrumb'  => array_values(array_filter([
        $artSection ? ['name' => $artSection['nav'], 'url' => tb_section_url($artSection['slug'])] : null,
        ['name' => $article['title'], 'url' => $artUrl],
    ])),
    'jsonld'      => [[
        '@context'         => 'https://schema.org',
        '@type'            => 'Article',
        'headline'         => $article['title'],
        'description'      => $article['description'] ?? '',
        'image'            => tb_image($article['image'] ?? null),
        'datePublished'    => $article['date'] ?? '',
        'dateModified'     => $article['updated'] ?? $article['date'] ?? '',
        'inLanguage'       => 'ar',
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => tb_abs($artUrl)],
        'author'           => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL . '/'],
        'publisher'        => [
            '@type' => 'Organization',
            'name'  => SITE_NAME,
            'logo'  => ['@type' => 'ImageObject', 'url' => tb_abs('/assets/img/logo-512.png')],
        ],
        'articleSection'   => $artSection['title'] ?? '',
        'keywords'         => implode('، ', $article['tags'] ?? []),
    ]],
];

/* الأسئلة الشائعة داخل المقال تُرسل كبيانات FAQPage لتظهر في نتائج البحث */
$articleFaq = $articleFaq ?? [];
if ($articleFaq) {
    $meta['jsonld'][] = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(static fn (array $f): array => [
            '@type'          => 'Question',
            'name'           => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($f['a']))],
        ], $articleFaq),
    ];
}

require __DIR__ . '/header.php';

/* شريط تقدّم القراءة — في المقالات وحدها لأنها الصفحات الطويلة */
echo '<div class="read-progress" aria-hidden="true"><span></span></div>';

/* يُخزَّن جسم المقال مؤقتًا حتى نحسب مدة القراءة قبل عرض الترويسة. */
ob_start();
