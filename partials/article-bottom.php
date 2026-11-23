<?php
/** أسفل صفحة المقال — يلتقط الجسم المخزّن مؤقتًا ويعرض الصفحة كاملة. */

if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}

$body = (string) ob_get_clean();

/* str_word_count لا يتعامل مع العربية — نعتمد الفصل على المسافات بنمط unicode. */
$plain = trim((string) preg_replace('/\s+/u', ' ', strip_tags($body)));
$words = $plain === '' ? 0 : count(explode(' ', $plain));

/* ~150 كلمة في الدقيقة: سرعة قراءة واقعية للنص العربي غير الأدبي. */
$mins = max(1, (int) round($words / 150));

$minsLabel = match (true) {
    $mins === 1 => 'دقيقة واحدة',
    $mins === 2 => 'دقيقتان',
    $mins <= 10 => $mins . ' دقائق',
    default     => $mins . ' دقيقة',
} . ' قراءة';

$shareUrl  = rawurlencode(tb_abs($artUrl));
$shareText = rawurlencode($article['title']);
$updated   = ($article['updated'] ?? null) && $article['updated'] !== ($article['date'] ?? null)
    ? $article['updated']
    : null;
?>

<article>
  <!-- ترويسة المقال -->
  <header class="bg-white border-b border-slate-200">
    <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
      <div class="max-w-3xl">
        <nav aria-label="مسار التصفح" class="mb-6">
          <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-400">
            <li><a href="/" class="hover:text-primary transition-colors">الرئيسية</a></li>
            <?php if ($artSection): ?>
            <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
            <li><a href="<?= e(tb_section_url($artSection['slug'])) ?>" class="hover:text-primary transition-colors"><?= e($artSection['nav']) ?></a></li>
            <?php endif; ?>
          </ol>
        </nav>

        <?php if ($artSection): ?>
        <a href="<?= e(tb_section_url($artSection['slug'])) ?>"
           class="inline-flex items-center gap-1.5 text-xs font-black px-3 py-1.5 rounded-full <?= e($artTint['bg']) ?> <?= e($artTint['text']) ?> mb-5">
          <i class="fas <?= e($artSection['icon']) ?> text-[10px]"></i><?= e($artSection['nav']) ?>
        </a>
        <?php endif; ?>

        <h1 class="text-3xl md:text-5xl font-black text-primary leading-tight mb-5"><?= e($article['title']) ?></h1>

        <?php if (!empty($article['description'])): ?>
        <p class="text-lg md:text-xl text-slate-500 leading-relaxed mb-6"><?= e($article['description']) ?></p>
        <?php endif; ?>

        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm font-bold text-slate-400">
          <time datetime="<?= e($article['date'] ?? '') ?>"><i class="fas fa-calendar-day ml-1.5"></i><?= e(tb_date_ar($article['date'] ?? '')) ?></time>
          <span><i class="fas fa-clock ml-1.5"></i><?= e($minsLabel) ?></span>
          <?php if ($updated): ?>
          <span class="text-accent"><i class="fas fa-rotate ml-1.5"></i>حُدِّث في <?= e(tb_date_ar($updated)) ?></span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <?php if (!empty($article['image'])): ?>
  <div class="container mx-auto px-4 md:px-6 -mb-6 md:-mb-10 pt-8 md:pt-10">
    <img src="<?= e($article['image']) ?>" alt="<?= e($article['title']) ?>" width="1200" height="630"
         class="max-w-4xl mx-auto w-full rounded-3xl ring-1 ring-slate-200 object-cover">
  </div>
  <?php endif; ?>

  <!-- جسم المقال -->
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16">
    <div class="max-w-3xl mx-auto">
      <div id="toc" hidden class="bg-slate-50 ring-1 ring-slate-200 rounded-2xl p-5 md:p-6 mb-10">
        <p class="font-black text-slate-800 mb-3 flex items-center gap-2"><i class="fas fa-list-ul text-primary text-sm"></i>محاور المقال</p>
        <ul class="space-y-2 text-sm font-bold text-slate-600"></ul>
      </div>

      <?php if (!empty($articleKey)): ?>
      <div class="bg-emerald-50 ring-1 ring-emerald-200 rounded-2xl p-5 md:p-6 mb-10">
        <p class="font-black text-primary mb-3 flex items-center gap-2"><i class="fas fa-bolt text-accent text-sm"></i>الخلاصة في سطور</p>
        <ul class="space-y-2 text-[15px] text-emerald-900 leading-relaxed">
          <?php foreach ($articleKey as $point): ?>
          <li class="flex gap-2.5"><i class="fas fa-check text-accent text-xs mt-2 shrink-0"></i><span><?= $point ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <div class="article-body"><?= $body ?></div>

      <?php
      /**
       * مصطلحات المعجم الواردة في المقال — تُكتشف آليًا بمسح نصّه.
       *
       * لا تُعدَّل شفرة المقال ولا يُحقن فيه أي رابط: نبحث عن اسم المصطلح
       * في النص المجرّد ($plain) ثم نعرض الروابط في صندوق مستقل أسفله.
       * هكذا لا يُكسر أي وسم HTML مهما كان شكل المقال.
       *
       * تُتجاهل المصطلحات القصيرة (أقل من 5 محارف) لأنها تُطابق كلمات عادية.
       */
      $inArticle = [];
      foreach (tb_data('moujam')['terms'] ?? [] as $mTerm) {
          if (strlen($mTerm['t']) < 5) {
              continue;
          }
          if (strpos($plain, $mTerm['t']) !== false) {
              $inArticle[] = $mTerm;
          }
      }
      /* الأطول أولًا: «بيداغوجيا الكفايات» أدلّ من «الكفاية» */
      usort($inArticle, static fn (array $x, array $y): int => strlen($y['t']) <=> strlen($x['t']));
      $inArticle = array_slice($inArticle, 0, 12);

      if ($inArticle): ?>
      <section class="mt-14">
        <h2 class="text-2xl md:text-3xl font-black text-primary mb-3">مصطلحات وردت في هذا المقال</h2>
        <p class="text-slate-500 leading-relaxed mb-5">
          لكل مصطلح صفحة فيها تعريفه وشرح عملي لأثره داخل الفصل والمفاهيم المرتبطة به.
        </p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($inArticle as $mTerm): ?>
          <a href="/moujam/<?= e($mTerm['s']) ?>"
             class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-primary hover:text-white transition-colors"><?= e($mTerm['t']) ?></a>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <?php if (!empty($articleFaq)): ?>
      <!-- أسئلة شائعة -->
      <section class="mt-14">
        <h2 class="text-2xl md:text-3xl font-black text-primary mb-6">أسئلة شائعة</h2>
        <div class="space-y-3" x-data="{ open: 0 }">
          <?php foreach ($articleFaq as $i => $f): ?>
          <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200/80 overflow-hidden">
            <h3>
              <button type="button" @click="open = (open === <?= $i ?> ? null : <?= $i ?>)"
                      :aria-expanded="open === <?= $i ?> ? 'true' : 'false'"
                      class="w-full flex items-center justify-between gap-4 text-right p-5 font-black text-slate-800 hover:text-primary transition-colors">
                <span><?= e($f['q']) ?></span>
                <i class="fas fa-plus text-sm shrink-0 text-primary transition-transform" :class="open === <?= $i ?> && 'rotate-45'"></i>
              </button>
            </h3>
            <?php /* بدون x-transition عمدًا: تشغيل انتقال «مغادرة» و«دخول» في النقرة نفسها
                     يجعل Alpine يؤجّل تطبيق display فيتأخر الطي نقرةً كاملة.
                     الحركة هنا بـ CSS خالص عبر .faq-panel فلا تتدخل في منطق x-show. */ ?>
            <div x-show="open === <?= $i ?>" x-cloak class="faq-panel">
              <div class="article-body px-5 pb-5"><?= $f['a'] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- المشاركة -->
      <div class="mt-12 pt-8 border-t border-slate-200">
        <p class="font-black text-slate-800 mb-4">هل أفادك هذا المقال؟ شاركه مع زملائك</p>
        <div class="flex flex-wrap gap-3">
          <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>%20<?= $shareUrl ?>" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-xl font-bold hover:bg-green-700 transition-colors">
            <i class="fab fa-whatsapp"></i> واتساب
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-xl font-bold hover:bg-blue-700 transition-colors">
            <i class="fab fa-facebook-f"></i> فيسبوك
          </a>
          <button type="button" data-copy="<?= e(tb_abs($artUrl)) ?>"
                  class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 px-5 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors">
            <i class="fas fa-link"></i> <span>نسخ الرابط</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</article>

<?php $related = tb_related($article, 3); ?>
<?php if ($related): ?>
<section class="py-14 md:py-20 bg-slate-50 border-t border-slate-200">
  <div class="container mx-auto px-4 md:px-6">
    <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-8">اقرأ أيضًا</h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($related as $r) { tb_card($r); } ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
require __DIR__ . '/app-cta.php';
require __DIR__ . '/footer.php';
