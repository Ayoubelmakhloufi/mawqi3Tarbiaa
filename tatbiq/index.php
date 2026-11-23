<?php
require __DIR__ . '/../partials/lib.php';

$shots = [
    ['app-home.webp',       'الشاشة الرئيسية',  'كل أدواتك في واجهة واحدة واضحة'],
    ['app-skill-tree.webp', 'شجرة المهارات',    'تقدّم متدرّج من الأساسيات إلى الإتقان'],
    ['app-library.webp',    'المكتبة الرقمية',  'مراجع ووثائق جاهزة للاطلاع والتحميل'],
    ['app-finder.webp',     'الباحث الذكي',     'اعثر على أي ملف تربوي في ثوانٍ'],
    ['app-features.webp',   'كافة الأدوات',     'اختبارات، ملخصات وأدوات مساعدة'],
    ['app-account.webp',    'إدارة التقدم',     'نقاط الخبرة ونتائجك محفوظة ومتزامنة'],
];

$meta = [
    'title'       => 'تطبيق تربية — التطبيق المغربي المجاني لعلوم التربية',
    'description' => 'تطبيق تربية: شجرة مهارات متدرجة، اختبارات تفاعلية، مكتبة رقمية وباحث ذكي عن الملفات. مجاني بالكامل للمترشحين للمباريات المهنية بالمغرب.',
    'canonical'   => '/tatbiq/',
    'image'       => '/assets/img/og-tatbiq.jpg',
    'section'     => 'tatbiq',
    'breadcrumb'  => [['name' => 'تطبيق تربية', 'url' => '/tatbiq/']],
    'jsonld'      => [[
        '@context'            => 'https://schema.org',
        '@type'               => 'MobileApplication',
        'name'                => 'تربية — Tarbiaa',
        'operatingSystem'     => 'ANDROID',
        'applicationCategory' => 'EducationalApplication',
        'url'                 => tb_abs('/tatbiq/'),
        'installUrl'          => PLAY_URL,
        'downloadUrl'         => PLAY_URL,
        'inLanguage'          => 'ar',
        'description'         => 'تطبيق تعليمي مغربي مجاني في علوم التربية والديداكتيك، يضم شجرة مهارات، اختبارات تفاعلية ومكتبة رقمية.',
        'image'               => tb_abs('/assets/img/og-tatbiq.jpg'),
        'author'              => ['@type' => 'Person', 'name' => 'أيوب المخلوفي'],
        'publisher'           => ['@id' => SITE_URL . '/#organization'],
        'offers'              => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'MAD'],
    ]],
];

require __DIR__ . '/../partials/header.php';
?>

<!-- الواجهة -->
<section class="pt-16 pb-20 md:pt-24 md:pb-28 gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
  </div>

  <div class="container mx-auto px-4 md:px-6 relative z-10 flex flex-col md:flex-row items-center gap-12">
    <div class="md:w-1/2 text-center md:text-right">
      <div class="inline-flex items-center gap-2 bg-white/10 text-accent px-4 py-2 rounded-full text-sm font-bold mb-6">
        <i class="fas fa-certificate text-gold"></i>
        <span>مشروع تعليمي مجاني ومستقل 📚</span>
      </div>
      <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">
        بوابتك الذكية لاحتراف <br>
        <span class="text-accent">علوم التربية والديداكتيك</span>
      </h1>
      <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl leading-relaxed">
        تعلّم، تدرّب وانجح مع «تربية». المنصة الأكثر شمولاً بالمغرب العربي، متاحة للجميع بدون قيود مادية
        وبتقنيات ذكاء اصطناعي متطورة.
      </p>
      <div class="flex flex-wrap justify-center md:justify-start gap-4">
        <a href="<?= e(PLAY_URL) ?>" target="_blank" rel="noopener"
           class="flex items-center gap-3 bg-white text-primary px-8 py-4 rounded-2xl font-black text-lg hover:bg-slate-100 transition-all shadow-xl">
          <i class="fab fa-google-play"></i>
          <span>تحميل من Google Play</span>
        </a>
        <a href="#gallery" class="flex items-center gap-3 border-2 border-white/30 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all">
          <span>جولة في التطبيق</span>
          <i class="fas fa-camera text-sm"></i>
        </a>
      </div>
    </div>

    <div class="md:w-1/2 flex justify-center">
      <div class="relative w-64 sm:w-72 md:w-80 bg-slate-800 rounded-[3rem] border-[8px] border-slate-900 shadow-2xl overflow-hidden animate-float">
        <img src="/assets/img/app-home.webp" alt="واجهة تطبيق تربية الرئيسية" width="1080" height="2400" fetchpriority="high"
             class="w-full h-auto object-cover">
      </div>
    </div>
  </div>
</section>

<!-- الفلسفة -->
<section id="philosophy" class="py-20 md:py-24 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl md:text-5xl font-black mb-8 text-primary">التعليم حق، وليس سلعة.</h2>
      <p class="text-slate-600 text-lg md:text-xl leading-relaxed mb-12">
        نؤمن في منصة «تربية» أن التكوين التربوي يجب أن يكون متاحًا لكل مترشح ومترشحة بكرامة ومجانية تامة.
        <b>جميع الدروس والامتحانات والمصادر مفتوحة بالمجان</b>؛ حيث يمكنك التقدم تدريجيًا في شجرة المهارات
        دون أي اشتراك إلزامي.
      </p>
      <div class="grid gap-6 md:grid-cols-3">
        <div class="p-6 bg-slate-50 rounded-2xl ring-1 ring-slate-200/80">
          <i class="fas fa-heart text-accent text-3xl mb-4"></i>
          <h3 class="font-bold mb-2">مستقل وغير ربحي</h3>
          <p class="text-sm text-slate-500">مشروع شخصي غايته الوحيدة تجويد العرض التربوي الرقمي.</p>
        </div>
        <div class="p-6 bg-slate-50 rounded-2xl ring-1 ring-slate-200/80">
          <i class="fas fa-globe text-accent text-3xl mb-4"></i>
          <h3 class="font-bold mb-2">متاح للجميع</h3>
          <p class="text-sm text-slate-500">لا حدود جغرافية أو مادية تمنعك من الوصول لأجود المحتويات.</p>
        </div>
        <div class="p-6 bg-slate-50 rounded-2xl ring-1 ring-slate-200/80">
          <i class="fas fa-shield-heart text-accent text-3xl mb-4"></i>
          <h3 class="font-bold mb-2">مدعوم بالتطوع</h3>
          <p class="text-sm text-slate-500">نقدّر دعمكم الاختياري لضمان بقاء السيرفرات قيد العمل.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- جولة في التطبيق -->
<section id="gallery" class="py-20 md:py-24 bg-slate-900 overflow-hidden">
  <div class="container mx-auto px-4 md:px-6">
    <div class="text-center mb-14">
      <span class="text-accent font-black uppercase tracking-widest text-sm">شاهد التطبيق من الداخل</span>
      <h2 class="text-3xl md:text-5xl font-black text-white mt-4">تجربة تعلّم عصرية في جيبك</h2>
    </div>

    <div class="flex gap-6 overflow-x-auto pb-10 pt-4 hide-scrollbar snap-x snap-mandatory">
      <?php foreach ($shots as [$file, $title, $caption]): ?>
      <figure class="snap-center flex-shrink-0 w-56 sm:w-64 md:w-72">
        <div class="screenshot-card rounded-[2.5rem] overflow-hidden ring-4 ring-slate-800 shadow-2xl bg-slate-800">
          <img src="/assets/img/<?= e($file) ?>" alt="<?= e($title) ?> في تطبيق تربية" loading="lazy"
               width="1080" height="2400" class="w-full h-auto">
        </div>
        <figcaption class="text-center mt-5">
          <span class="block text-white font-bold"><?= e($title) ?></span>
          <span class="block text-slate-400 text-sm mt-1"><?= e($caption) ?></span>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- دعم المشروع -->
<section id="premium" class="py-20 md:py-24 bg-primary relative overflow-hidden">
  <div class="absolute right-0 top-0 w-1/3 h-full bg-secondary/30 -skew-x-12 translate-x-1/2" aria-hidden="true"></div>
  <div class="container mx-auto px-4 md:px-6 relative z-10">
    <div class="flex flex-col md:flex-row items-center gap-12 md:gap-16">
      <div class="md:w-1/2 text-white">
        <div class="inline-block bg-accent/20 text-accent px-4 py-1 rounded-full text-sm font-black mb-6">خيار دعم الاستمرارية</div>
        <h2 class="text-3xl md:text-5xl font-black mb-8 leading-tight">
          ادعم مشروعك المفضل <br>
          <span class="text-accent underline decoration-accent/30 underline-offset-8">واختصر طريق التعلم</span>
        </h2>
        <p class="text-slate-200 text-lg md:text-xl mb-10 leading-relaxed">
          نحن ملتزمون بمجانية التطبيق للجميع. لكن إذا رغبت في تقدير مجهود المطور والمساهمة في تكاليف
          الذكاء الاصطناعي مع فتح كافة الدروس دفعة واحدة وبدون انتظار، يمكنك تفعيل النسخة الكاملة بمبلغ رمزي.
        </p>
        <ul class="space-y-4">
          <li class="flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i><span>فتح فوري لكافة دروس شجرة المهارات للأبد.</span></li>
          <li class="flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i><span>دعم تطوير أدوات ذكاء اصطناعي جديدة.</span></li>
        </ul>
      </div>

      <div class="md:w-1/2 w-full">
        <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl relative text-center">
          <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gold text-white px-7 py-2 rounded-full font-black text-sm whitespace-nowrap">تفعيل مدى الحياة</div>
          <div class="mb-9 mt-4">
            <span class="text-6xl font-black text-primary">49</span>
            <span class="text-2xl font-bold text-slate-400">درهم</span>
            <p class="text-slate-500 mt-2 font-medium italic">«مساهمة بسيطة، أثر كبير»</p>
          </div>
          <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="p-4 bg-slate-50 rounded-2xl flex flex-col items-center">
              <i class="fab fa-paypal text-blue-600 text-2xl mb-1"></i>
              <span class="text-[9px] font-bold text-slate-400 uppercase">PayPal Support</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl flex flex-col items-center ring-2 ring-primary/10">
              <i class="fas fa-university text-primary text-2xl mb-1"></i>
              <span class="text-[9px] font-bold text-slate-400 uppercase">Bank Transfer (RIB)</span>
            </div>
          </div>
          <a href="<?= e(PLAY_URL) ?>" target="_blank" rel="noopener"
             class="block w-full bg-primary text-white text-center py-5 rounded-2xl font-black text-lg hover:bg-secondary transition-all shadow-xl shadow-primary/30">
            حمّل التطبيق واطلب التفعيل
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- شبكات التواصل -->
<section class="py-16 md:py-20 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="flex flex-col items-center text-center">
      <h2 class="text-2xl md:text-3xl font-black text-primary mb-10">تابع منصة تربية على شبكات التواصل</h2>
      <div class="flex flex-wrap justify-center gap-8 md:gap-10">
        <a href="<?= e(FB_URL) ?>" target="_blank" rel="noopener" class="flex flex-col items-center gap-3 group">
          <span class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all shadow-lg"><i class="fab fa-facebook-f"></i></span>
          <span class="font-bold text-slate-600">فيسبوك</span>
        </a>
        <a href="<?= e(IG_URL) ?>" target="_blank" rel="noopener" class="flex flex-col items-center gap-3 group">
          <span class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center text-pink-600 text-2xl group-hover:bg-pink-600 group-hover:text-white transition-all shadow-lg"><i class="fab fa-instagram"></i></span>
          <span class="font-bold text-slate-600">إنستغرام</span>
        </a>
        <a href="<?= e(WA_URL) ?>" target="_blank" rel="noopener" class="flex flex-col items-center gap-3 group">
          <span class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-2xl group-hover:bg-green-600 group-hover:text-white transition-all shadow-lg"><i class="fab fa-whatsapp"></i></span>
          <span class="font-bold text-slate-600">قناة واتساب</span>
        </a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
