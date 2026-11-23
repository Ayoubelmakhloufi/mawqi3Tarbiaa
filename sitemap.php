<?php
/**
 * خريطة الموقع — تُقدَّم على الرابط /sitemap.xml عبر قاعدة في .htaccess.
 * تُولَّد تلقائيًا من data/sections.json و data/articles.json،
 * فلا تحتاج إلى أي تحديث يدوي عند نشر مقال جديد.
 */

require __DIR__ . '/partials/lib.php';

header('Content-Type: application/xml; charset=UTF-8');

/** آخر تعديل لملف في الموقع، بصيغة W3C */
function tb_file_date(string $relative): string
{
    $path = TB_ROOT . '/' . ltrim($relative, '/');
    $time = is_file($path) ? filemtime($path) : time();

    return date('Y-m-d', $time ?: time());
}

$urls = [];

/* الصفحات الثابتة */
$urls[] = ['loc' => '/',                 'changefreq' => 'weekly',  'priority' => '1.0', 'lastmod' => tb_file_date('index.php')];
$urls[] = ['loc' => '/tatbiq/',          'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => tb_file_date('tatbiq/index.php')];
$urls[] = ['loc' => '/ikhtibarat/', 'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => tb_file_date('data/ikhtibarat.json')];
foreach ((tb_data('ikhtibarat')['quizzes'] ?? []) as $z) {
    $urls[] = ['loc' => '/ikhtibarat/' . $z['slug'] . '/', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => tb_file_date('data/ikhtibarat.json')];
}
/* الاختبار العشوائي المركَّب — ليس في ikhtibarat.json فيُضاف يدويًا */
$urls[] = ['loc' => '/ikhtibarat/achwai/', 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => tb_file_date('data/ikhtibarat.json')];
$urls[] = ['loc' => '/moujam/', 'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => tb_file_date('data/moujam.json')];
/* صفحة مجال وصفحة مستقلة لكل مصطلح — تدخل الخريطة وحدها عند أي إضافة */
foreach ((tb_data('moujam')['cats'] ?? []) as $cCat) {
    $urls[] = ['loc' => '/moujam/majal/' . $cCat['slug'], 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => tb_file_date('data/moujam.json')];
}
foreach ((tb_data('moujam')['terms'] ?? []) as $tTerm) {
    $urls[] = ['loc' => '/moujam/' . $tTerm['s'], 'changefreq' => 'yearly', 'priority' => '0.6', 'lastmod' => tb_file_date('data/moujam.json')];
}
$urls[] = ['loc' => '/bitaqat/', 'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('data/moujam.json')];
$urls[] = ['loc' => '/hisab-intiqa-crmef', 'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => tb_file_date('hisab-intiqa-crmef.php')];
$urls[] = ['loc' => CALC_URL, 'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => tb_file_date(CALC_FILE)];

/* صفحات الأقسام */
foreach (tb_sections() as $section) {
    $articles = tb_articles_in($section['slug']);
    $lastmod  = $articles
        ? max(array_map(static fn (array $a): string => $a['updated'] ?? $a['date'], $articles))
        : tb_file_date($section['slug'] . '/index.php');

    $urls[] = [
        'loc'        => tb_section_url($section['slug']),
        'changefreq' => $section['slug'] === 'mostajaddat' ? 'daily' : 'weekly',
        'priority'   => '0.8',
        'lastmod'    => $lastmod,
    ];
}

/* المقالات */
foreach (tb_articles() as $article) {
    $urls[] = [
        'loc'        => tb_article_url($article),
        'changefreq' => 'monthly',
        'priority'   => !empty($article['featured']) ? '0.8' : '0.7',
        'lastmod'    => $article['updated'] ?? $article['date'],
        'image'      => $article['image'] ?? null,
        'title'      => $article['title'] ?? null,
    ];
}

/* حقيبة الوثائق الرقمية — تُقرأ من data/wathaiq.json */
$wathaiq = tb_data('wathaiq');
if (!empty($wathaiq['sections'])) {
    $urls[] = ['loc' => '/maktaba/wathaiq/', 'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('maktaba/wathaiq/index.php')];

    foreach ($wathaiq['sections'] as $w) {
        $has = array_filter($wathaiq['docs'] ?? [], static fn (array $d): bool => $d['s'] === $w['slug']);
        if (!$has) {
            continue;
        }
        $urls[] = [
            'loc'        => '/maktaba/wathaiq/' . $w['slug'] . '/',
            'changefreq' => 'monthly',
            'priority'   => '0.7',
            'lastmod'    => tb_file_date('maktaba/wathaiq/' . $w['slug'] . '/index.php'),
        ];
    }
}

$urls[] = ['loc' => '/tahaddi-yawmi',        'changefreq' => 'daily',   'priority' => '0.8', 'lastmod' => date('Y-m-d')];
$urls[] = ['loc' => '/ibda-min-huna',        'changefreq' => 'monthly', 'priority' => '0.9', 'lastmod' => tb_file_date('ibda-min-huna.php')];
$urls[] = ['loc' => '/molid-jadada',         'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('molid-jadada.php')];
$urls[] = ['loc' => '/mokhattat-almourajaa', 'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('mokhattat-almourajaa.php')];
$urls[] = ['loc' => '/chabakat-taqwim',      'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('chabakat-taqwim.php')];
$urls[] = ['loc' => '/mouassassat/',         'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('mouassassat/index.php')];
$urls[] = ['loc' => '/jamiat/',              'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('jamiat/index.php')];
$urls[] = ['loc' => '/hisab-mouaddalat',     'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => tb_file_date('hisab-mouaddalat.php')];
$urls[] = ['loc' => '/kharitat-almawqi',     'changefreq' => 'weekly',  'priority' => '0.7', 'lastmod' => date('Y-m-d')];
$urls[] = ['loc' => '/mawadi',               'changefreq' => 'weekly',  'priority' => '0.7', 'lastmod' => tb_file_date('data/articles.json')];
$urls[] = ['loc' => '/man-nahnu', 'changefreq' => 'yearly', 'priority' => '0.6', 'lastmod' => tb_file_date('man-nahnu.php')];
$urls[] = ['loc' => '/ittisal',   'changefreq' => 'yearly', 'priority' => '0.5', 'lastmod' => tb_file_date('ittisal.php')];

/* الصفحات القانونية */
foreach ([['/privacy', 'privacy.php'], ['/shourout', 'shourout.php'], ['/delete-account', 'delete-account.php']] as [$loc, $file]) {
    $urls[] = ['loc' => $loc, 'changefreq' => 'yearly', 'priority' => '0.3', 'lastmod' => tb_file_date($file)];
}

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<?php foreach ($urls as $url): ?>
  <url>
    <loc><?= e(tb_abs($url['loc'])) ?></loc>
    <lastmod><?= e($url['lastmod']) ?></lastmod>
    <changefreq><?= e($url['changefreq']) ?></changefreq>
    <priority><?= e($url['priority']) ?></priority>
<?php if (!empty($url['image'])): ?>
    <image:image>
      <image:loc><?= e(tb_abs($url['image'])) ?></image:loc>
      <image:title><?= e($url['title'] ?? '') ?></image:title>
    </image:image>
<?php endif; ?>
  </url>
<?php endforeach; ?>
</urlset>
