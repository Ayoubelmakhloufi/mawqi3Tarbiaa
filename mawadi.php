<?php
/**
 * المواضيع حسب الوسم — تصفّح المقالات عبر وسومها.
 *
 * كان حقل tags في data/articles.json معطَّلًا تمامًا: يُكتب ولا يُقرأ في أي صفحة.
 * هذه الصفحة تفتحه للتصفّح.
 *
 * ⚠️ صفحة واحدة لا 79 صفحة وسم منفصلة: أغلب الوسوم لها مقال واحد،
 * وصفحة بمقال واحد صفحة هزيلة يعاقب عليها جوجل. فالتصفية هنا داخل الصفحة.
 */

require __DIR__ . '/partials/lib.php';
require_once __DIR__ . '/partials/card.php';

$articles = tb_articles();

/* الوسم => المقالات، مرتّبة بالأكثر ورودًا */
$byTag = [];
foreach ($articles as $a) {
    foreach ($a['tags'] ?? [] as $tag) {
        $byTag[$tag][] = $a;
    }
}
uasort($byTag, static fn (array $x, array $y): int => count($y) <=> count($x));

/* وسوم لها مقالان فأكثر تُعرض أولًا كـ«محاور رئيسية» */
$main  = array_filter($byTag, static fn (array $v): bool => count($v) >= 2);
$other = array_filter($byTag, static fn (array $v): bool => count($v) < 2);

$meta = [
    'title'       => 'المواضيع — تصفّح مقالات الموقع حسب الوسم',
    'description' => 'كل مواضيع منصة تربية في صفحة واحدة: ' . count($byTag) . ' وسمًا يغطي '
                   . count($articles) . ' مقالًا في مباراة التعليم والإجازة في التربية وعلوم التربية والتعليم الرقمي.',
    'canonical'   => '/mawadi',
    'image'       => '/assets/img/og-default.jpg',
    'breadcrumb'  => [['name' => 'المواضيع', 'url' => '/mawadi']],
];

require __DIR__ . '/partials/header.php';
?>

<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-400">
        <li><a href="/" class="hover:text-primary transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-slate-700" aria-current="page">المواضيع</li>
      </ol>
    </nav>
    <div class="max-w-3xl">
      <h1 class="text-3xl md:text-5xl font-black text-primary leading-tight mb-4">المواضيع</h1>
      <p class="text-lg text-slate-600 leading-relaxed">
        <b><?= count($byTag) ?> وسمًا</b> يغطي <b><?= count($articles) ?> مقالًا</b>.
        اضغط أي وسم لترى مقالاته وحدها — التصفية فورية بلا إعادة تحميل.
      </p>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14" x-data="{ tag: '' }">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl">

      <!-- الوسوم -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-8">
        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-4">
          <i class="fas fa-tags text-accent"></i> محاور رئيسية
        </h2>
        <div class="flex flex-wrap gap-2 mb-7">
          <button type="button" @click="tag = ''"
                  :class="tag === '' ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                  class="px-4 py-2 rounded-xl text-sm font-bold ring-1 transition-all">
            الكل <span class="opacity-60"><?= count($articles) ?></span>
          </button>
          <?php foreach ($main as $tag => $list): ?>
          <button type="button" @click="tag = (tag === <?= e(json_encode($tag, JSON_UNESCAPED_UNICODE)) ?> ? '' : <?= e(json_encode($tag, JSON_UNESCAPED_UNICODE)) ?>)"
                  :class="tag === <?= e(json_encode($tag, JSON_UNESCAPED_UNICODE)) ?> ? 'bg-primary text-white ring-primary' : 'bg-white text-slate-600 ring-slate-200 hover:ring-primary/30'"
                  class="px-4 py-2 rounded-xl text-sm font-bold ring-1 transition-all">
            <?= e($tag) ?> <span class="opacity-60"><?= count($list) ?></span>
          </button>
          <?php endforeach; ?>
        </div>

        <h2 class="text-lg font-black text-primary flex items-center gap-2.5 mb-4">
          <i class="fas fa-tag text-accent"></i> وسوم أخرى
        </h2>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($other as $tag => $list): ?>
          <button type="button" @click="tag = (tag === <?= e(json_encode($tag, JSON_UNESCAPED_UNICODE)) ?> ? '' : <?= e(json_encode($tag, JSON_UNESCAPED_UNICODE)) ?>)"
                  :class="tag === <?= e(json_encode($tag, JSON_UNESCAPED_UNICODE)) ?> ? 'bg-primary text-white ring-primary' : 'bg-slate-100 text-slate-500 ring-slate-200 hover:ring-primary/30'"
                  class="px-3 py-1.5 rounded-lg text-xs font-bold ring-1 transition-all">
            <?= e($tag) ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <p class="mb-5 text-sm font-bold text-slate-400">
        <span x-show="tag === ''">كل المقالات (<?= count($articles) ?>)</span>
        <span x-show="tag !== ''" x-cloak>
          مقالات وسم «<span x-text="tag"></span>»
          <button type="button" @click="tag = ''" class="text-primary hover:underline mr-2">اعرض الكل</button>
        </span>
      </p>

      <!-- المقالات -->
      <div class="grid gap-5 sm:grid-cols-2">
        <?php foreach ($articles as $a):
            $hay = ' ' . implode(' | ', $a['tags'] ?? []) . ' '; ?>
        <div data-tags="<?= e($hay) ?>"
             x-show="tag === '' || $el.dataset.tags.indexOf(' ' + tag + ' ') !== -1 || $el.dataset.tags.indexOf('| ' + tag + ' ') !== -1 || $el.dataset.tags.indexOf(' ' + tag + ' |') !== -1">
          <?php tb_card($a); ?>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-10 bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body">
        <h2>طرق أخرى للتصفّح</h2>
        <ul>
          <li><b><a href="/kharitat-almawqi">خريطة المحتوى</a></b> — كل صفحات الموقع مرتبة في صفحة واحدة.</li>
          <li><b><a href="/baht/">البحث</a></b> — إن كنت تبحث عن كلمة بعينها في المقالات.</li>
          <li><b><a href="/moujam/">المعجم</a></b> — إن كنت تبحث عن تعريف مصطلح لا عن مقال.</li>
          <li><b><a href="/ibda-min-huna">ابدأ من هنا</a></b> — إن لم تكن تعرف ما تبحث عنه أصلًا.</li>
        </ul>
      </div>

    </div>
  </div>
</div>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
