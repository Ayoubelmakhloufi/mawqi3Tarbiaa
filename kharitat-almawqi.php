<?php
/**
 * خريطة المحتوى — فهرس بشري لكل ما في الموقع.
 *
 * ⚠️ تُبنى بالكامل من ملفات data/ ومن sections.json:
 * أي مقال أو مصطلح أو بنك أسئلة أو وثيقة تُضاف تظهر هنا وحدها.
 * لا قائمة مكتوبة يدويًا إلا الصفحات الثابتة والأدوات.
 *
 * تختلف عن sitemap.xml: تلك لمحركات البحث وهذه للزائر.
 */

require __DIR__ . '/partials/lib.php';

$sections = tb_sections();
$articles = tb_articles();
$M        = tb_data('moujam');
$terms    = $M['terms'] ?? [];
$cats     = $M['cats'] ?? [];
$quizzes  = tb_data('ikhtibarat')['quizzes'] ?? [];
$W        = tb_data('wathaiq');
$wSecs    = $W['sections'] ?? [];
$wDocs    = $W['docs'] ?? [];

$nQ = array_sum(array_map(static fn (array $z): int => count($z['qs']), $quizzes));

$meta = [
    'title'       => 'خريطة المحتوى — كل صفحات منصة تربية في مكان واحد',
    'description' => 'فهرس شامل لمنصة تربية: ' . count($articles) . ' مقالًا و' . tb_terms_ar(count($terms))
                   . ' و' . tb_q_ar($nQ) . ' و' . tb_docs_ar(count($wDocs)) . ' والأدوات والحاسبات — مرتبة في صفحة واحدة.',
    'canonical'   => '/kharitat-almawqi',
    'image'       => '/assets/img/og-default.jpg',
    'breadcrumb'  => [['name' => 'خريطة المحتوى', 'url' => '/kharitat-almawqi']],
];

require __DIR__ . '/partials/header.php';
?>

<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-400">
        <li><a href="/" class="hover:text-primary transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-slate-700" aria-current="page">خريطة المحتوى</li>
      </ol>
    </nav>
    <div class="max-w-3xl">
      <h1 class="text-3xl md:text-5xl font-black text-primary leading-tight mb-4">خريطة المحتوى</h1>
      <p class="text-lg text-slate-600 leading-relaxed">
        كل ما في الموقع في صفحة واحدة: <b><?= count($articles) ?> مقالًا</b> ·
        <b><?= e(tb_terms_ar(count($terms))) ?></b> · <b><?= e(tb_q_ar($nQ)) ?></b> ·
        <b><?= e(tb_docs_ar(count($wDocs))) ?></b> · الأدوات والحاسبات.
        وإن كنت لا تعرف من أين تبدأ، فابدأ من <a href="/ibda-min-huna" class="text-primary font-bold hover:underline">هنا</a>.
      </p>
    </div>
  </div>
</section>

<section class="py-10 md:py-14 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl space-y-6">

      <!-- ابدأ من هنا والأدوات -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-5">
          <i class="fas fa-compass text-accent"></i> المداخل والأدوات
        </h2>
        <ul class="grid gap-2.5 sm:grid-cols-2 text-slate-700 font-bold">
          <li><a href="/ibda-min-huna" class="hover:text-primary transition-colors">ابدأ من هنا — مسارك حسب حالك</a></li>
          <li><a href="/tahaddi-yawmi" class="hover:text-primary transition-colors">التحدّي اليومي</a></li>
          <li><a href="<?= e(CALC_URL) ?>" class="hover:text-primary transition-colors">حاسبة معدل الانتقاء (ENS · ESEF · FSE)</a></li>
          <li><a href="/hisab-intiqa-crmef" class="hover:text-primary transition-colors">حاسبة الانتقاء الأولي — CRMEF</a></li>
          <li><a href="/molid-jadada" class="hover:text-primary transition-colors">مولّد جذاذة درس</a></li>
          <li><a href="/mokhattat-almourajaa" class="hover:text-primary transition-colors">مخطّط المراجعة</a></li>
          <li><a href="/chabakat-taqwim" class="hover:text-primary transition-colors">مولّد شبكة التقويم بالمعايير</a></li>
          <li><a href="/hisab-mouaddalat" class="hover:text-primary transition-colors">حاسبة معدلات المتعلمين</a></li>
          <li><a href="/baht/" class="hover:text-primary transition-colors">البحث في الموقع</a></li>
          <li><a href="/tatbiq/" class="hover:text-primary transition-colors">تطبيق تربية</a></li>
        </ul>
      </div>

      <!-- الأقسام ومقالاتها -->
      <?php foreach ($sections as $s):
          $arts = tb_articles_in($s['slug']);
          $t = tb_tint($s['tint']); ?>
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8">
        <h2 class="text-xl font-black text-primary flex items-center gap-3 mb-2">
          <span class="w-10 h-10 shrink-0 rounded-xl <?= e($t['bg']) ?> <?= e($t['text']) ?> flex items-center justify-center">
            <i class="fas <?= e($s['icon']) ?>"></i>
          </span>
          <a href="<?= e(tb_section_url($s['slug'])) ?>" class="hover:underline"><?= e($s['title']) ?></a>
        </h2>
        <p class="text-sm font-bold text-slate-400 mb-5"><?= e(tb_count_ar(count($arts))) ?></p>
        <?php if ($arts): ?>
        <ul class="space-y-2 text-slate-700">
          <?php foreach ($arts as $a): ?>
          <li>
            <a href="<?= e(tb_article_url($a)) ?>" class="font-bold hover:text-primary transition-colors"><?= e($a['title']) ?></a>
            <span class="text-xs text-slate-400 font-bold"><?= e(tb_date_ar($a['updated'] ?? $a['date'])) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>

      <!-- المعجم -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-2">
          <i class="fas fa-book-open text-accent"></i>
          <a href="/moujam/" class="hover:underline">معجم المصطلحات التربوية</a>
        </h2>
        <p class="text-sm font-bold text-slate-400 mb-5">
          <?= e(tb_terms_ar(count($terms))) ?> — ولكل مصطلح صفحته الخاصة
        </p>
        <?php foreach ($cats as $c):
            $mine = array_values(array_filter($terms, static fn (array $t): bool => ($t['c'] ?? '') === $c['slug']));
            if (!$mine) { continue; } ?>
        <h3 class="font-black text-slate-800 mt-5 mb-2">
          <a href="/moujam/majal/<?= e($c['slug']) ?>" class="hover:text-primary transition-colors">
            <i class="fas <?= e($c['icon']) ?> text-xs ml-1"></i><?= e($c['title']) ?>
          </a>
          <span class="text-xs font-bold text-slate-400">(<?= count($mine) ?>)</span>
        </h3>
        <p class="flex flex-wrap gap-2 text-sm">
          <?php foreach ($mine as $t): ?>
          <a href="/moujam/<?= e($t['s']) ?>"
             class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold hover:bg-primary hover:text-white transition-colors"><?= e($t['t']) ?></a>
          <?php endforeach; ?>
        </p>
        <?php endforeach; ?>
      </div>

      <!-- الاختبارات -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-2">
          <i class="fas fa-clipboard-check text-accent"></i>
          <a href="/ikhtibarat/" class="hover:underline">الاختبارات التفاعلية</a>
        </h2>
        <p class="text-sm font-bold text-slate-400 mb-5"><?= e(tb_q_ar($nQ)) ?> في <?= count($quizzes) ?> بنوك</p>
        <ul class="grid gap-2.5 sm:grid-cols-2 text-slate-700 font-bold">
          <?php foreach ($quizzes as $z): ?>
          <li>
            <a href="/ikhtibarat/<?= e($z['slug']) ?>/" class="hover:text-primary transition-colors"><?= e($z['title']) ?></a>
            <span class="text-xs text-slate-400">(<?= count($z['qs']) ?>)</span>
          </li>
          <?php endforeach; ?>
          <li><a href="/ikhtibarat/achwai/" class="hover:text-primary transition-colors">عشوائي من كل المحاور</a></li>
          <li><a href="/tahaddi-yawmi" class="hover:text-primary transition-colors">التحدّي اليومي</a></li>
          <li><a href="/bitaqat/" class="hover:text-primary transition-colors">بطاقات المراجعة</a></li>
        </ul>
      </div>

      <!-- حقيبة الوثائق -->
      <?php if ($wSecs): ?>
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-2">
          <i class="fas fa-folder-open text-accent"></i>
          <a href="/maktaba/wathaiq/" class="hover:underline">حقيبة الوثائق الرقمية</a>
        </h2>
        <p class="text-sm font-bold text-slate-400 mb-5"><?= e(tb_docs_ar(count($wDocs))) ?> رسمية للتحميل</p>
        <ul class="grid gap-2.5 sm:grid-cols-2 text-slate-700 font-bold">
          <?php foreach ($wSecs as $w):
              $n = count(array_filter($wDocs, static fn (array $d): bool => $d['s'] === $w['slug']));
              if (!$n) { continue; } ?>
          <li>
            <a href="/maktaba/wathaiq/<?= e($w['slug']) ?>/" class="hover:text-primary transition-colors"><?= e($w['title']) ?></a>
            <span class="text-xs text-slate-400">(<?= $n ?>)</span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <!-- صفحات الموقع -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8">
        <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-5">
          <i class="fas fa-circle-info text-accent"></i> صفحات الموقع
        </h2>
        <ul class="grid gap-2.5 sm:grid-cols-2 text-slate-700 font-bold">
          <li><a href="/man-nahnu" class="hover:text-primary transition-colors">من نحن</a></li>
          <li><a href="/ittisal" class="hover:text-primary transition-colors">اتصل بنا</a></li>
          <li><a href="/mawadi" class="hover:text-primary transition-colors">المواضيع حسب الوسم</a></li>
          <li><a href="/rss.xml" class="hover:text-primary transition-colors">تغذية RSS</a></li>
          <li><a href="/privacy" class="hover:text-primary transition-colors">سياسة الخصوصية</a></li>
          <li><a href="/shourout" class="hover:text-primary transition-colors">شروط الخدمة</a></li>
          <li><a href="/delete-account" class="hover:text-primary transition-colors">حذف الحساب</a></li>
        </ul>
      </div>

    </div>
  </div>
</section>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
