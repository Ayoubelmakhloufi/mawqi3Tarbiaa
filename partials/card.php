<?php
/** بطاقة مقال قابلة لإعادة الاستعمال في الرئيسية وصفحات الأقسام والمقالات ذات الصلة. */
if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}

if (!function_exists('tb_card')) {
    /**
     * @param array $a       بيانات المقال
     * @param bool  $large   بطاقة مميّزة أعرض
     * @param bool  $showSec إظهار اسم القسم (مفيد في الرئيسية والبحث)
     */
    function tb_card(array $a, bool $large = false, bool $showSec = true): void
    {
        $section = tb_section($a['section'] ?? '');
        $tint    = tb_tint($section['tint'] ?? 'emerald');
        $url     = tb_article_url($a);
        ?>
        <article class="group h-full flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 overflow-hidden hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
          <?php if (!empty($a['image'])): ?>
          <a href="<?= e($url) ?>" tabindex="-1" aria-hidden="true" class="block overflow-hidden <?= $large ? 'aspect-[16/8]' : 'aspect-[16/9]' ?> bg-slate-100">
            <img src="<?= e($a['image']) ?>" alt="" loading="lazy" width="640" height="360"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          </a>
          <?php endif; ?>

          <div class="flex flex-col flex-1 p-5 md:p-6">
            <?php if ($showSec && $section): ?>
            <a href="<?= e(tb_section_url($section['slug'])) ?>"
               class="inline-flex items-center gap-1.5 self-start text-xs font-black px-2.5 py-1 rounded-full <?= e($tint['bg']) ?> <?= e($tint['text']) ?> mb-3">
              <i class="fas <?= e($section['icon']) ?> text-[10px]"></i><?= e($section['nav']) ?>
            </a>
            <?php endif; ?>

            <h3 class="<?= $large ? 'text-xl md:text-2xl' : 'text-lg' ?> font-black text-slate-900 leading-snug mb-2">
              <a href="<?= e($url) ?>" class="hover:text-primary transition-colors"><?= e($a['title']) ?></a>
            </h3>

            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-4"><?= e($a['description'] ?? '') ?></p>

            <div class="mt-auto flex items-center justify-between text-xs font-bold text-slate-400 pt-3 border-t border-slate-100">
              <time datetime="<?= e($a['date'] ?? '') ?>"><i class="fas fa-calendar-day ml-1.5"></i><?= e(tb_date_ar($a['date'] ?? '')) ?></time>
              <span class="text-primary group-hover:gap-2 inline-flex items-center gap-1 transition-all">اقرأ <i class="fas fa-arrow-left text-[10px]"></i></span>
            </div>
          </div>
        </article>
        <?php
    }
}

if (!function_exists('tb_empty_state')) {
    /** رسالة تظهر في قسم لا يحتوي مقالات بعد. */
    function tb_empty_state(string $sectionTitle): void
    {
        ?>
        <div class="col-span-full bg-white rounded-3xl ring-1 ring-slate-200/80 p-10 md:p-14 text-center">
          <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-5">
            <i class="fas fa-feather-pointed"></i>
          </div>
          <h3 class="text-xl font-black text-slate-800 mb-2">المقالات قيد الإعداد</h3>
          <p class="text-slate-500 max-w-md mx-auto leading-relaxed mb-7">
            نعمل حاليًا على تحرير محتوى قسم «<?= e($sectionTitle) ?>». في انتظار ذلك، يمكنك الاستفادة من
            دروس التطبيق التفاعلية ومن باقي أقسام الموقع.
          </p>
          <div class="flex flex-wrap justify-center gap-3">
            <a href="/tatbiq/" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-secondary transition-colors">تعرّف على تطبيق تربية</a>
            <a href="/dourous/" class="bg-slate-100 text-slate-700 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors">دروس علوم التربية</a>
          </div>
        </div>
        <?php
    }
}
