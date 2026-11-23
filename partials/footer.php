<?php
/** الفوتر الموحّد — يُعدَّل هنا مرة واحدة فيتغيّر في كل صفحات الموقع. */
if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}
?>
</main>

<footer class="bg-slate-900 text-white pt-16 pb-8">
  <div class="container mx-auto px-4 md:px-6">
    <div class="grid gap-10 md:grid-cols-12 mb-12">

      <div class="md:col-span-4">
        <div class="flex items-center gap-3 mb-5">
          <img src="/assets/img/logo.svg" alt="" width="40" height="40" class="w-10 h-10 brightness-0 invert">
          <span class="text-2xl font-black tracking-tighter">منصة تربية</span>
        </div>
        <p class="text-slate-400 leading-relaxed text-sm mb-6">
          مشروع تربوي رقمي مستقل وغير ربحي، صُمم لخدمة المترشحين للمباريات المهنية وطلبة علوم التربية بالمغرب،
          معتمدًا على أحدث تقنيات التعليم الرقمي والذكاء الاصطناعي.
        </p>
        <div class="flex gap-3">
          <a href="<?= e(FB_URL) ?>" target="_blank" rel="noopener" aria-label="فيسبوك" class="w-10 h-10 rounded-full bg-white/5 hover:bg-blue-600 flex items-center justify-center transition-colors"><i class="fab fa-facebook-f"></i></a>
          <a href="<?= e(IG_URL) ?>" target="_blank" rel="noopener" aria-label="إنستغرام" class="w-10 h-10 rounded-full bg-white/5 hover:bg-pink-600 flex items-center justify-center transition-colors"><i class="fab fa-instagram"></i></a>
          <a href="<?= e(WA_URL) ?>" target="_blank" rel="noopener" aria-label="قناة واتساب" class="w-10 h-10 rounded-full bg-white/5 hover:bg-green-600 flex items-center justify-center transition-colors"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>

      <div class="md:col-span-5">
        <h2 class="text-base font-bold mb-5 text-accent">أقسام الموقع</h2>
        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 text-sm text-slate-400">
          <?php foreach (tb_sections() as $section): ?>
          <li><a href="<?= e(tb_section_url($section['slug'])) ?>" class="hover:text-accent transition-colors"><?= e($section['nav']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="/ibda-min-huna" class="hover:text-accent transition-colors">ابدأ من هنا</a></li>
          <li><a href="/ikhtibarat/" class="hover:text-accent transition-colors">الاختبارات التفاعلية</a></li>
          <li><a href="/tahaddi-yawmi" class="hover:text-accent transition-colors">التحدّي اليومي</a></li>
          <li><a href="/moujam/" class="hover:text-accent transition-colors">معجم المصطلحات</a></li>
          <li><a href="/bitaqat/" class="hover:text-accent transition-colors">بطاقات المراجعة</a></li>
          <li><a href="/mawadi" class="hover:text-accent transition-colors">المواضيع حسب الوسم</a></li>
          <li><a href="/kharitat-almawqi" class="hover:text-accent transition-colors">خريطة المحتوى</a></li>
          <li><a href="/tatbiq/" class="hover:text-accent transition-colors">تطبيق تربية</a></li>
          <li><a href="/mouassassat/" class="hover:text-accent transition-colors">دليل المؤسسات التعليمية</a></li>
          <li><a href="/jamiat/" class="hover:text-accent transition-colors">دليل المؤسسات الجامعية</a></li>
          <li><a href="/cle/" class="hover:text-accent transition-colors">دليل الإجازة في التربية (CLE)</a></li>
          <li><a href="/baht/" class="hover:text-accent transition-colors">البحث في الموقع</a></li>
        </ul>
      </div>

      <div class="md:col-span-3">
        <h2 class="text-base font-bold mb-5 text-accent">التواصل والقانون</h2>
        <ul class="space-y-2.5 text-sm text-slate-400">
          <li><a href="mailto:<?= e(MAIL_MAIN) ?>" class="hover:text-accent transition-colors break-all"><?= e(MAIL_MAIN) ?></a></li>
          <li><a href="mailto:<?= e(MAIL_DEV) ?>" class="hover:text-accent transition-colors break-all"><?= e(MAIL_DEV) ?></a></li>
          <li><a href="/man-nahnu" class="hover:text-accent transition-colors">من نحن</a></li>
          <li><a href="/ittisal" class="hover:text-accent transition-colors">اتصل بنا</a></li>
          <li><a href="/privacy" class="hover:text-accent transition-colors">سياسة الخصوصية</a></li>
          <li><a href="/shourout" class="hover:text-accent transition-colors">شروط الخدمة</a></li>
          <li><a href="/delete-account" class="hover:text-accent transition-colors">حذف الحساب</a></li>
        </ul>
        <a href="<?= e(DEV_FB_URL) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2.5 mt-6 p-3 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all text-sm">
          <i class="fab fa-facebook text-blue-400"></i>
          <span class="font-bold">المطور: أيوب المخلوفي</span>
        </a>
      </div>
    </div>

    <div class="border-t border-white/10 pt-8 text-center text-slate-500 text-sm">
      <p>صُنع بكل <span class="text-red-500 mx-1">❤️</span> لخدمة المنظومة التربوية ولأجل نجاحكم.</p>
      <p class="mt-2 text-xs opacity-60">&copy; <?= date('Y') ?> منصة تربية — جميع الحقوق محفوظة.</p>
    </div>
  </div>
</footer>

<script src="/assets/js/site.js?v=1" defer></script>

<!-- زر الصعود إلى الأعلى -->
<button type="button" id="toTop" class="to-top" aria-label="العودة إلى أعلى الصفحة">
  <i class="fas fa-arrow-up" aria-hidden="true"></i>
</button>

<script>
/* تسجيل Service Worker — يمكّن تثبيت الموقع كتطبيق ويعمل جزئيًا بلا اتصال.
   الاستراتيجية «الشبكة أولًا» للصفحات، فلا خطر من عرض محتوى قديم. */
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/sw.js').catch(function () { /* تجاهل الفشل بصمت */ });
  });
}

/* زر الصعود + شريط تقدّم القراءة.
   يُحسبان في إطار requestAnimationFrame حتى لا يُثقلا التمرير. */
(function () {
  'use strict';

  var btn = document.getElementById('toTop');
  var bar = document.querySelector('.read-progress > span');
  var ticking = false;

  function update() {
    var y = window.pageYOffset || document.documentElement.scrollTop;

    if (btn) {
      btn.classList.toggle('show', y > 600);
    }

    if (bar) {
      var h = document.documentElement.scrollHeight - window.innerHeight;
      bar.style.width = (h > 0 ? Math.min(100, (y / h) * 100) : 0) + '%';
    }

    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (!ticking) {
      ticking = true;
      window.requestAnimationFrame(update);
    }
  }, { passive: true });

  if (btn) {
    btn.addEventListener('click', function () {
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    });
  }

  /* المتصفح يستعيد موضع التمرير عند إعادة التحميل وقد يتم ذلك بعد تنفيذ هذا السكربت،
     كما تتوقف أُطر الرسم حين تكون اللسانة مخفية. لذلك نُحدّث الحالة في الحالتين. */
  window.addEventListener('load', update);
  document.addEventListener('visibilitychange', function () {
    if (!document.hidden) { update(); }
  });

  update();
})();
</script>
</body>
</html>
