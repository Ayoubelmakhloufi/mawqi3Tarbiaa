<?php
require __DIR__ . '/partials/lib.php';
require_once __DIR__ . '/partials/card.php';

$meta = [
    'title'       => SITE_NAME,
    'description' => 'بوابة تربوية مغربية: مباراة التعليم، الإجازة في التربية والمدارس العليا، التعليم الرقمي، دروس علوم التربية، ومكتبة وأدوات مجانية.',
    'canonical'   => '/',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'home',
];

$latest = tb_latest(6);

/* أعداد الواجهة — تُقرأ من مصادرها فلا تتقادم أبدًا */
$nArticles = count(tb_articles());
$nTerms    = count(tb_data('moujam')['terms'] ?? []);
$nDocs     = count(tb_data('wathaiq')['docs'] ?? []);
$nQ        = array_sum(array_map(
    static fn (array $z): int => count($z['qs']),
    tb_data('ikhtibarat')['quizzes'] ?? []
));

require __DIR__ . '/partials/header.php';
?>

<!-- الواجهة -->
<section class="gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-accent rounded-full blur-3xl -translate-y-1/3 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
  </div>

  <div class="container mx-auto px-4 md:px-6 py-16 md:py-24 relative z-10">
    <div class="max-w-3xl">
      <div class="inline-flex items-center gap-2 bg-white/10 text-accent px-4 py-2 rounded-full text-sm font-bold mb-6">
        <i class="fas fa-certificate text-gold"></i>
        <span>مشروع تربوي مغربي مستقل · مجاني بالكامل</span>
      </div>

      <h1 class="text-4xl md:text-6xl font-black text-white leading-[1.15] mb-6">
        كل ما تحتاجه لمسارك التربوي <br>
        <span class="text-accent">في مكان واحد</span>
      </h1>

      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-9">
        مباراة التعليم، الإجازة في التربية والمدارس العليا للأساتذة، التكوين بالمراكز الجهوية،
        دروس علوم التربية والديداكتيك، والتعليم الرقمي — بمحتوى واضح وأدوات عملية وتطبيق يرافقك في جيبك.
      </p>

      <div class="flex flex-wrap gap-4">
        <a href="/ibda-min-huna" class="flex items-center gap-3 bg-white text-primary px-7 py-4 rounded-2xl font-black hover:bg-slate-100 transition-all shadow-xl">
          <i class="fas fa-compass"></i>
          <span>ابدأ من هنا</span>
        </a>
        <a href="/tatbiq/" class="flex items-center gap-3 border-2 border-white/30 text-white px-7 py-4 rounded-2xl font-bold hover:bg-white/10 transition-all">
          <i class="fas fa-mobile-screen-button"></i>
          <span>تطبيق تربية</span>
        </a>
      </div>

      <!-- أعداد حقيقية تُقرأ من ملفات data/ -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12 max-w-2xl">
        <div>
          <p class="text-3xl font-black text-accent"><?= $nQ ?></p>
          <p class="text-sm font-bold text-slate-400">سؤالًا بتصحيح فوري</p>
        </div>
        <div>
          <p class="text-3xl font-black text-accent"><?= $nTerms ?></p>
          <p class="text-sm font-bold text-slate-400">مصطلحًا مشروحًا</p>
        </div>
        <div>
          <p class="text-3xl font-black text-accent"><?= $nDocs ?></p>
          <p class="text-sm font-bold text-slate-400">وثيقة رسمية</p>
        </div>
        <div>
          <p class="text-3xl font-black text-accent"><?= $nArticles ?></p>
          <p class="text-sm font-bold text-slate-400">مقالًا تربويًا</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- الأقسام -->
<section id="aqsam" class="py-16 md:py-24 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-2xl mb-12">
      <span class="text-accent font-black uppercase tracking-widest text-sm">أقسام الموقع</span>
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-3 mb-4">من أين تريد أن تبدأ؟</h2>
      <p class="text-slate-500 text-lg leading-relaxed">
        كل قسم بوابة قائمة بذاتها: شرح مفصّل، مقالات محدَّثة، ووثائق وأدوات تخدم هدفك مباشرة.
      </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach (tb_sections() as $section):
          $t     = tb_tint($section['tint']);
          $count = count(tb_articles_in($section['slug'])); ?>
      <a href="<?= e(tb_section_url($section['slug'])) ?>"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl <?= e($t['bg']) ?> <?= e($t['text']) ?> flex items-center justify-center text-xl mb-5 ring-1 <?= e($t['ring']) ?>">
          <i class="fas <?= e($section['icon']) ?>"></i>
        </span>
        <h3 class="text-lg font-black text-slate-900 leading-snug mb-2 group-hover:text-primary transition-colors"><?= e($section['title']) ?></h3>
        <p class="text-sm text-slate-500 leading-relaxed mb-5 flex-1"><?= e($section['tagline']) ?></p>
        <span class="flex items-center justify-between text-xs font-bold pt-4 border-t border-slate-100">
          <span class="text-slate-400"><?= $count ? e(tb_count_ar($count)) : 'قيد الإثراء' ?></span>
          <span class="text-primary inline-flex items-center gap-1 group-hover:gap-2 transition-all">ادخل القسم <i class="fas fa-arrow-left text-[10px]"></i></span>
        </span>
      </a>
      <?php endforeach; ?>

      <!-- بطاقة التطبيق ضمن الشبكة -->
      <a href="/tatbiq/" class="group flex flex-col gradient-bg text-white rounded-3xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ring-1 ring-primary/20">
        <span class="w-12 h-12 rounded-2xl bg-white/10 text-accent flex items-center justify-center text-xl mb-5">
          <i class="fas fa-mobile-screen-button"></i>
        </span>
        <h3 class="text-lg font-black leading-snug mb-2">تطبيق تربية للأندرويد</h3>
        <p class="text-sm text-slate-300 leading-relaxed mb-5 flex-1">شجرة مهارات، اختبارات تفاعلية، مكتبة رقمية وباحث ذكي — مجانًا.</p>
        <span class="flex items-center justify-between text-xs font-bold pt-4 border-t border-white/10">
          <span class="text-accent">مجاني بالكامل</span>
          <span class="inline-flex items-center gap-1 group-hover:gap-2 transition-all">اكتشف التطبيق <i class="fas fa-arrow-left text-[10px]"></i></span>
        </span>
      </a>
    </div>
  </div>
</section>

<!-- تدرّب واختبر -->
<section class="py-16 md:py-24 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-2xl mb-12">
      <span class="text-accent font-black uppercase tracking-widest text-sm">لا تكتفِ بالقراءة</span>
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-3 mb-4">تدرّب واختبر نفسك</h2>
      <p class="text-slate-500 text-lg leading-relaxed">
        إعادة قراءة الملخص تمنح <b>إحساسًا كاذبًا بالإتقان</b> — أنت تعرف النص لأنك رأيته
        لا لأنك تملكه. الاسترجاع من الذاكرة هو ما يثبّت فعلًا، وهذا ما تفعله هذه الأدوات.
      </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <a href="/ikhtibarat/" class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-clipboard-check"></i>
        </span>
        <h3 class="text-lg font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">الاختبارات التفاعلية</h3>
        <p class="text-sm text-slate-500 leading-relaxed flex-1"><?= $nQ ?> سؤالًا في 9 بنوك، بتصحيح فوري وشرح لكل جواب ومؤقّت اختياري.</p>
        <span class="text-primary font-bold text-sm mt-4 inline-flex items-center gap-1 group-hover:gap-2 transition-all">ابدأ <i class="fas fa-arrow-left text-[10px]"></i></span>
      </a>

      <a href="/tahaddi-yawmi" class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-fire"></i>
        </span>
        <h3 class="text-lg font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">التحدّي اليومي</h3>
        <p class="text-sm text-slate-500 leading-relaxed flex-1">عشرة أسئلة تتغيّر كل يوم. عشر دقائق يوميًا أنفع من ثلاث ساعات مرة في الأسبوع.</p>
        <span class="text-primary font-bold text-sm mt-4 inline-flex items-center gap-1 group-hover:gap-2 transition-all">تحدّي اليوم <i class="fas fa-arrow-left text-[10px]"></i></span>
      </a>

      <a href="/bitaqat/" class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 ring-1 ring-sky-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-layer-group"></i>
        </span>
        <h3 class="text-lg font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">بطاقات المراجعة</h3>
        <p class="text-sm text-slate-500 leading-relaxed flex-1">يظهر المصطلح فتستحضر تعريفه قبل كشفه، وما لم تتقنه يعود في جولة ثانية.</p>
        <span class="text-primary font-bold text-sm mt-4 inline-flex items-center gap-1 group-hover:gap-2 transition-all">افتح البطاقات <i class="fas fa-arrow-left text-[10px]"></i></span>
      </a>

      <a href="/moujam/" class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-book-open"></i>
        </span>
        <h3 class="text-lg font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">معجم المصطلحات</h3>
        <p class="text-sm text-slate-500 leading-relaxed flex-1"><?= $nTerms ?> مصطلحًا، لكل واحد صفحته بتعريف دقيق وشرح عملي يبيّن أثره في الفصل.</p>
        <span class="text-primary font-bold text-sm mt-4 inline-flex items-center gap-1 group-hover:gap-2 transition-all">افتح المعجم <i class="fas fa-arrow-left text-[10px]"></i></span>
      </a>
    </div>
  </div>
</section>

<!-- أدوات الأستاذ -->
<section class="py-16 md:py-24 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-2xl mb-12">
      <span class="text-accent font-black uppercase tracking-widest text-sm">للأستاذ والمتدرّب</span>
      <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-3 mb-4">أدوات تُنجز عملك اليوم</h2>
      <p class="text-slate-500 text-lg leading-relaxed">
        كلها تعمل <b>داخل متصفحك</b>: لا تسجيل، ولا تثبيت، ولا تغادر معطياتك جهازك.
      </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <a href="/molid-jadada" class="group bg-white rounded-2xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-md transition-all">
        <i class="fas fa-file-lines text-2xl text-emerald-700 mb-4 block"></i>
        <h3 class="font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">مولّد الجذاذة</h3>
        <p class="text-sm text-slate-500 leading-relaxed">ثلاثة نماذج: المقاطع · المراحل · مبسّط — جاهزة للطباعة.</p>
      </a>
      <a href="/chabakat-taqwim" class="group bg-white rounded-2xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-md transition-all">
        <i class="fas fa-table-list text-2xl text-amber-700 mb-4 block"></i>
        <h3 class="font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">شبكة التقويم</h3>
        <p class="text-sm text-slate-500 leading-relaxed">معايير ومؤشرات ومستويات تحكّم — تصحيح ثابت لا مزاجي.</p>
      </a>
      <a href="/hisab-mouaddalat" class="group bg-white rounded-2xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-md transition-all">
        <i class="fas fa-calculator text-2xl text-teal-700 mb-4 block"></i>
        <h3 class="font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">حاسبة المعدلات</h3>
        <p class="text-sm text-slate-500 leading-relaxed">معدل مرجّح لكل متعلم وإحصائيات القسم في جدول للطباعة.</p>
      </a>
      <a href="/mokhattat-almourajaa" class="group bg-white rounded-2xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-md transition-all">
        <i class="fas fa-calendar-days text-2xl text-sky-700 mb-4 block"></i>
        <h3 class="font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">مخطّط المراجعة</h3>
        <p class="text-sm text-slate-500 leading-relaxed">جدول أسبوعي حتى يوم المباراة بمراجعة متباعدة.</p>
      </a>
    </div>

    <p class="mt-8">
      <a href="/adawat/" class="inline-flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
        كل الأدوات والحاسبات <i class="fas fa-arrow-left text-xs"></i>
      </a>
    </p>
  </div>
</section>

<!-- أحدث المقالات -->
<?php if ($latest): ?>
<section class="py-16 md:py-24 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
      <div>
        <span class="text-accent font-black uppercase tracking-widest text-sm">جديد الموقع</span>
        <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-3">أحدث المقالات</h2>
      </div>
      <a href="/baht/" class="inline-flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
        تصفّح كل المقالات <i class="fas fa-arrow-left text-xs"></i>
      </a>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($latest as $a) { tb_card($a); } ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- الأدوات -->
<section class="py-16 md:py-24 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="bg-white rounded-[2.5rem] ring-1 ring-slate-200 overflow-hidden">
      <div class="grid md:grid-cols-2 items-center">
        <div class="p-8 md:p-12">
          <span class="inline-flex items-center gap-2 text-xs font-black px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 mb-5">
            <i class="fas fa-calculator text-[10px]"></i> أداة مجانية
          </span>
          <h2 class="text-2xl md:text-4xl font-black text-primary leading-tight mb-4">
            احسب معدل انتقائك للمدارس العليا في ثوانٍ
          </h2>
          <p class="text-slate-500 text-lg leading-relaxed mb-8">
            أدخل نقطة الامتحان الوطني والجهوي واختر تخصصك وشعبتك، فتحصل على معدل الانتقاء التمهيدي
            مع تفصيل كامل لخطوات الحساب وجدول المعاملات الرسمي.
          </p>
          <div class="flex flex-wrap gap-3">
            <a href="/حساب_معدل_الانتقاء" class="bg-primary text-white px-6 py-3.5 rounded-xl font-bold hover:bg-secondary transition-colors shadow-lg shadow-primary/20">
              افتح الحاسبة
            </a>
            <a href="/adawat/" class="bg-slate-100 text-slate-700 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors">
              كل الأدوات
            </a>
          </div>
        </div>
        <div class="bg-slate-900 h-full p-8 md:p-12 flex items-center justify-center">
          <div class="text-center text-white">
            <p class="text-slate-400 font-medium mb-1">معدل الانتقاء التمهيدي</p>
            <p class="text-6xl md:text-7xl font-black text-gold mb-4">21.00</p>
            <p class="text-sm text-slate-400 leading-relaxed max-w-xs mx-auto">
              (14 × 0.75 + 14 × 0.25) × 1.5 — مثال توضيحي لطريقة الاحتساب
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- لماذا تربية -->
<section class="py-16 md:py-24 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl md:text-5xl font-black text-primary mb-6">التعليم حق، وليس سلعة.</h2>
      <p class="text-slate-600 text-lg md:text-xl leading-relaxed mb-12">
        منصة تربية مشروع مستقل غير ربحي، غايته أن يجد كل مترشح ومترشحة محتوى تربويًا جيدًا
        <b>بدون أي قيد مادي</b>. ما تقرأه في الموقع مفتوح للجميع، وما تتدرب عليه في التطبيق مجاني كذلك.
      </p>
      <div class="grid gap-6 md:grid-cols-3 text-right">
        <div class="p-6 bg-slate-50 rounded-2xl ring-1 ring-slate-200/80">
          <i class="fas fa-heart text-accent text-2xl mb-4"></i>
          <h3 class="font-black text-slate-800 mb-2">مستقل وغير ربحي</h3>
          <p class="text-sm text-slate-500 leading-relaxed">مشروع شخصي غايته الوحيدة تجويد العرض التربوي الرقمي بالمغرب.</p>
        </div>
        <div class="p-6 bg-slate-50 rounded-2xl ring-1 ring-slate-200/80">
          <i class="fas fa-globe text-accent text-2xl mb-4"></i>
          <h3 class="font-black text-slate-800 mb-2">متاح للجميع</h3>
          <p class="text-sm text-slate-500 leading-relaxed">لا حدود جغرافية أو مادية تمنعك من الوصول إلى أجود المحتويات.</p>
        </div>
        <div class="p-6 bg-slate-50 rounded-2xl ring-1 ring-slate-200/80">
          <i class="fas fa-shield-heart text-accent text-2xl mb-4"></i>
          <h3 class="font-black text-slate-800 mb-2">محتوى مسؤول</h3>
          <p class="text-sm text-slate-500 leading-relaxed">نحيل دائمًا إلى المصادر الرسمية، ونصحّح فورًا كل ما يُبلَّغ عنه من أخطاء.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/app-cta.php'; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
