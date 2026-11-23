<?php
/**
 * تغذية RSS — تُقدَّم على الرابط /rss.xml عبر قاعدة في .htaccess.
 * تُولَّد تلقائيًا من data/articles.json فلا تحتاج أي تحديث يدوي.
 */

require __DIR__ . '/partials/lib.php';

header('Content-Type: application/rss+xml; charset=UTF-8');

$articles = array_slice(tb_articles(), 0, 30);
$sections = [];
foreach (tb_sections() as $s) {
    $sections[$s['slug']] = $s['title'];
}

/** تاريخ بصيغة RFC-822 التي يتطلبها RSS */
function tb_rfc822(string $date): string
{
    $ts = strtotime($date) ?: time();

    return date('D, d M Y H:i:s O', $ts);
}

$lastBuild = $articles
    ? tb_rfc822($articles[0]['updated'] ?? $articles[0]['date'])
    : tb_rfc822(date('Y-m-d'));

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?= e(SITE_NAME) ?> — <?= e(SITE_TAG) ?></title>
    <link><?= e(SITE_URL) ?>/</link>
    <description>بوابة تربوية مغربية: مباراة التعليم، الإجازة في التربية والمدارس العليا، التعليم الرقمي، دروس علوم التربية، ومكتبة وأدوات مجانية.</description>
    <language>ar</language>
    <lastBuildDate><?= e($lastBuild) ?></lastBuildDate>
    <atom:link href="<?= e(tb_abs('/rss.xml')) ?>" rel="self" type="application/rss+xml" />
    <image>
      <url><?= e(tb_abs('/assets/img/og-default.jpg')) ?></url>
      <title><?= e(SITE_NAME) ?></title>
      <link><?= e(SITE_URL) ?>/</link>
    </image>
<?php foreach ($articles as $a):
    $url = tb_abs(tb_article_url($a)); ?>
    <item>
      <title><?= e($a['title']) ?></title>
      <link><?= e($url) ?></link>
      <guid isPermaLink="true"><?= e($url) ?></guid>
      <pubDate><?= e(tb_rfc822($a['updated'] ?? $a['date'])) ?></pubDate>
      <description><?= e($a['description']) ?></description>
<?php if (!empty($sections[$a['section']])): ?>
      <category><?= e($sections[$a['section']]) ?></category>
<?php endif; ?>
<?php foreach (($a['tags'] ?? []) as $tag): ?>
      <category><?= e($tag) ?></category>
<?php endforeach; ?>
<?php if (!empty($a['image'])): ?>
      <enclosure url="<?= e(tb_abs($a['image'])) ?>" type="image/jpeg" length="0" />
<?php endif; ?>
    </item>
<?php endforeach; ?>
  </channel>
</rss>
