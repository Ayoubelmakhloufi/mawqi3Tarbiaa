<?php
/** بطاقة "تطبيق تربية" — تُدرج في كل صفحات المحتوى لإبقاء رابط المشروع حاضرًا. */
if (!defined('TB_ROOT')) {
    require __DIR__ . '/lib.php';
}
?>
<section class="py-16 md:py-20 gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 relative z-10">
    <div class="max-w-4xl mx-auto text-center text-white">
      <div class="inline-flex items-center gap-2 bg-white/10 text-accent px-4 py-2 rounded-full text-sm font-bold mb-6">
        <i class="fas fa-certificate text-gold"></i>
        <span>مجاني بالكامل · بدون اشتراك إلزامي</span>
      </div>
      <h2 class="text-2xl md:text-4xl font-black mb-5 leading-tight">
        كل ما تقرأه هنا… <span class="text-accent">تتدرّب عليه في التطبيق</span>
      </h2>
      <p class="text-slate-300 text-base md:text-lg mb-9 max-w-2xl mx-auto leading-relaxed">
        شجرة مهارات متدرجة، اختبارات تفاعلية، مكتبة رقمية وباحث ذكي عن الملفات — كلها في تطبيق واحد
        يرافقك من التحضير للمباراة حتى التكوين بالمركز الجهوي.
      </p>
      <div class="flex flex-wrap justify-center gap-3">
        <a href="<?= e(PLAY_URL) ?>" target="_blank" rel="noopener"
           class="flex items-center gap-3 bg-white text-primary px-7 py-4 rounded-2xl font-black hover:bg-slate-100 transition-all shadow-xl">
          <i class="fab fa-google-play text-lg"></i>
          <span>تحميل من Google Play</span>
        </a>
        <a href="/tatbiq/" class="flex items-center gap-3 border-2 border-white/30 text-white px-7 py-4 rounded-2xl font-bold hover:bg-white/10 transition-all">
          <span>تعرّف على التطبيق</span>
          <i class="fas fa-arrow-left text-sm"></i>
        </a>
      </div>
    </div>
  </div>
</section>
