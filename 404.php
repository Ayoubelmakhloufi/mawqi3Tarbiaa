<?php
/**
 * صفحة 404.
 *
 * ⚠️ التضمين محروس: تستدعي هذه الصفحةَ صفحاتٌ حمّلت `lib.php` سلفًا
 * (`moujam/term.php` و`moujam/majal.php` عند معرّف غير معروف).
 * وبلا الحارس تُعاد الدوال فيقع Fatal error — ورمز الحالة يبقى 404
 * فلا يكشفه فحص الحالة وحده، بل فحص جسم الصفحة.
 */
if (!defined('TB_ROOT')) {
    require __DIR__ . '/partials/lib.php';
}

http_response_code(404);

$meta = [
    'title'       => 'الصفحة غير موجودة',
    'description' => 'الصفحة التي تبحث عنها غير متوفرة. تصفّح أقسام منصة تربية أو ابحث في الموقع.',
    'canonical'   => '/404',
    'noindex'     => true,
];

require __DIR__ . '/partials/header.php';
?>

<div class="container mx-auto px-4 md:px-6 py-16 md:py-24">
  <div class="max-w-2xl mx-auto text-center">
    <p class="text-7xl md:text-9xl font-black text-primary/10 leading-none mb-4">404</p>
    <h1 class="text-3xl md:text-4xl font-black text-primary mb-4">هذه الصفحة غير موجودة</h1>
    <p class="text-slate-500 text-lg leading-relaxed mb-10">
      ربما تغيّر الرابط أو حُذفت الصفحة. لا بأس — إليك أهم الوجهات في الموقع.
    </p>

    <!-- بحث مباشر: يوصل الزائر إلى ما جاء لأجله بدل أن يغادر -->
    <form action="/baht/" method="get" class="relative max-w-lg mx-auto mb-10">
      <i class="fas fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true"></i>
      <label for="nf-q" class="sr-only">ابحث في الموقع</label>
      <input type="search" id="nf-q" name="q" autocomplete="off"
             placeholder="ابحث عمّا جئت لأجله…"
             class="w-full rounded-2xl border-2 border-slate-200 bg-white pr-14 pl-5 py-4 text-lg font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
    </form>

    <div class="flex flex-wrap justify-center gap-3 mb-12">
      <a href="/ibda-min-huna" class="bg-primary text-white px-6 py-3.5 rounded-xl font-bold hover:bg-secondary transition-colors shadow-lg shadow-primary/20">ابدأ من هنا</a>
      <a href="/" class="bg-slate-100 text-slate-700 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors">الصفحة الرئيسية</a>
      <a href="/kharitat-almawqi" class="bg-slate-100 text-slate-700 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors">خريطة المحتوى</a>
    </div>

    <p class="text-sm font-black text-slate-400 mb-4">أكثر ما يبحث عنه الزوار</p>
    <div class="flex flex-wrap justify-center gap-2 mb-12">
      <?php foreach ([
          ['الاختبارات التفاعلية', '/ikhtibarat/'],
          ['معجم المصطلحات', '/moujam/'],
          ['بطاقات المراجعة', '/bitaqat/'],
          ['حقيبة الوثائق', '/maktaba/wathaiq/'],
          ['حاسبة معدل الانتقاء', CALC_URL],
          ['مولّد الجذاذة', '/molid-jadada'],
      ] as [$label, $url]): ?>
      <a href="<?= e($url) ?>" class="bg-white ring-1 ring-slate-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm hover:ring-primary/30 hover:text-primary transition-all"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 text-right">
      <?php foreach (tb_sections() as $section): $t = tb_tint($section['tint']); ?>
      <a href="<?= e(tb_section_url($section['slug'])) ?>"
         class="flex items-center gap-3 bg-white ring-1 ring-slate-200/80 rounded-2xl p-4 hover:ring-primary/30 hover:shadow-md transition-all">
        <span class="w-10 h-10 shrink-0 rounded-xl <?= e($t['bg']) ?> <?= e($t['text']) ?> flex items-center justify-center">
          <i class="fas <?= e($section['icon']) ?>"></i>
        </span>
        <span class="font-bold text-slate-700 text-sm"><?= e($section['nav']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
