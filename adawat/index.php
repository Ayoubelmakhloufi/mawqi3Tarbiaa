<?php
require __DIR__ . '/../partials/lib.php';

$sectionSlug = 'adawat';

$sectionIntro = <<<'HTML'
<p>
  أدوات مجانية تعمل مباشرة من المتصفح، بدون تسجيل ولا تثبيت، ودون أن تغادر معطياتك جهازك.
  صُممت لتوفّر عليك الحسابات اليدوية والأخطاء التي ترافقها، وتمنحك في المقابل نتيجة واضحة
  مع <b>تفصيل كامل لكل خطوة</b> حتى تفهم كيف تكوّنت.
</p>
HTML;

/* بطاقات الأدوات — تُعرض قبل قائمة المقالات */
$sectionExtra = <<<'HTML'
<section class="pb-14 md:pb-20 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-8">الأدوات المتاحة</h2>
    <div class="grid gap-6 md:grid-cols-2">

      <a href="/حساب_معدل_الانتقاء"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 ring-1 ring-rose-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-calculator"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">حاسبة معدل الانتقاء</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          للمدارس العليا للأساتذة (ENS · ESEF · FSE): اختر التخصص والشعبة، أدخل نقطتَي الوطني والجهوي،
          واحصل على معدل الانتقاء التمهيدي مع خطوات الحساب وجدول المعاملات الرسمي.
        </p>
        <span class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-3 transition-all">
          افتح الحاسبة <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/hisab-intiqa-crmef"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-chalkboard-user"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">حاسبة الانتقاء الأولي — المراكز الجهوية</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          لمباراة الولوج إلى CRMEF: احسب نقطتك على 80 من ميزة الباكالوريا والإجازة والسن،
          وقارنها بعتبات دورة نونبر 2025 حسب تخصصك.
        </p>
        <span class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-3 transition-all">
          افتح الحاسبة <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/molid-jadada"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-file-lines"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">مولّد جذاذة درس</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          للأستاذ والأستاذ المتدرّب: املأ الكفاية والأهداف والمتطلبات القبلية ومراحل الدرس،
          واحصل على جذاذة منسّقة جاهزة للطباعة أو الحفظ PDF. المراحل الأربع مقترحة سلفًا.
        </p>
        <span class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-3 transition-all">
          افتح المولّد <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/mokhattat-almourajaa"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 ring-1 ring-sky-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-calendar-days"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">مخطّط المراجعة</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          أدخل تاريخ امتحانك وساعاتك المتاحة، واحصل على جدول أسبوعي يوزّع المحاور
          ويُدرج المراجعة المتباعدة والمحاكاة بالمؤقّت في الأسابيع الأخيرة.
        </p>
        <span class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-3 transition-all">
          افتح المخطّط <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/chabakat-taqwim"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 ring-1 ring-amber-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-table-list"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">مولّد شبكة التقويم بالمعايير</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          شبكة تصحيح جاهزة للطباعة: معايير ومؤشرات ومستويات تحكّم. تجعل تصحيحك ثابتًا
          لا مزاجيًا، وتحمي من أثر الهالة وأثر الترتيب.
        </p>
        <span class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-3 transition-all">
          افتح المولّد <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/hisab-mouaddalat"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 ring-1 ring-teal-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-calculator"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">حاسبة معدلات المتعلمين</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          للأستاذ: أدخل فروضك ومعاملاتها ونقط متعلميك، واحصل على المعدل المرجّح لكل واحد
          ومعدل القسم ونسبة النجاح، في جدول جاهز للطباعة.
        </p>
        <span class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-3 transition-all">
          افتح الحاسبة <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/mouassassat/"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-700 ring-1 ring-orange-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-school"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">دليل المؤسسات التعليمية</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          قاعدة بيانات تضم أكثر من 11 ألف مؤسسة تعليمية عمومية بالمغرب.
          ابحث باسم المؤسسة، أو صنف النتائج حسب السلك (ابتدائي، إعدادي، تأهيلي) أو الجهة.
        </p>
        <span class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-3 transition-all">
          افتح الدليل <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/jamiat/"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-purple-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 ring-1 ring-purple-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-university"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-purple-600 transition-colors">دليل المؤسسات الجامعية</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          محرك بحث شامل يضم الكليات، المدارس العليا، والمعاهد التابعة للجامعات العمومية بالمغرب.
          تعرف على العناوين، أرقام التواصل، وعدد الطلبة.
        </p>
        <span class="inline-flex items-center gap-2 text-purple-600 font-bold group-hover:gap-3 transition-all">
          افتح الدليل <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <a href="/cle/"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-7 hover:ring-fuchsia-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl bg-fuchsia-50 text-fuchsia-700 ring-1 ring-fuchsia-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-graduation-cap"></i>
        </span>
        <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-fuchsia-600 transition-colors">دليل الإجازة في التربية</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          اكتشف التخصصات المتاحة في سلك الإجازة في التربية (CLE)، وتعرف على فصول ومحاور ووحدات كل تخصص بشكل مفصل ومرتب.
        </p>
        <span class="inline-flex items-center gap-2 text-fuchsia-600 font-bold group-hover:gap-3 transition-all">
          تصفح الوحدات <i class="fas fa-arrow-left text-xs"></i>
        </span>
      </a>

      <!-- ring-dashed لا وجود لها في Tailwind (الحلقة ظلّ لا إطار) فأُزيلت -->
      <div class="flex flex-col bg-slate-50 rounded-3xl ring-1 ring-slate-300 p-7">
        <span class="w-12 h-12 rounded-2xl bg-white text-slate-400 ring-1 ring-slate-200 flex items-center justify-center text-xl mb-5">
          <i class="fas fa-screwdriver-wrench"></i>
        </span>
        <h3 class="text-xl font-black text-slate-700 mb-2">أدوات قادمة</h3>
        <p class="text-slate-500 leading-relaxed mb-6 flex-1">
          نعمل على حاسبات إضافية تخدم المترشح والأستاذ. إن كانت لديك فكرة أداة تحتاجها فعلًا،
          راسلنا ولنبنِها معًا.
        </p>
        <a href="mailto:support@tarbiaa.ma?subject=اقتراح%20أداة%20جديدة"
           class="inline-flex items-center gap-2 text-slate-600 font-bold hover:text-primary transition-colors">
          اقترح أداة <i class="fas fa-arrow-left text-xs"></i>
        </a>
      </div>

    </div>
  </div>
</section>
HTML;

$sectionFaq = [
    [
        'q' => 'هل نتيجة الحاسبة رسمية؟',
        'a' => '<p>لا. الحاسبة <b>أداة مساعدة تقريبية</b> تطبّق الصيغة المعتمدة، لكن معاملات الترجيح والعتبات قد تتغيّر من سنة إلى أخرى. طابق دائمًا المعامل مع الجدول الرسمي ومع بلاغ المباراة قبل اعتماد أي نتيجة.</p>',
    ],
    [
        'q' => 'هل تُحفظ معطياتي عند استعمال الحاسبة؟',
        'a' => '<p>لا. الحساب يتم بالكامل <b>داخل متصفحك</b>، ولا تُرسل نقطك ولا اختياراتك إلى أي خادم، ولا تُخزَّن بعد إغلاق الصفحة.</p>',
    ],
    [
        'q' => 'هل تعمل الأدوات على الهاتف؟',
        'a' => '<p>نعم، جميع الأدوات مصممة لتعمل على الهاتف واللوح والحاسوب دون فرق. ولا تحتاج إلى تثبيت أي برنامج.</p>',
    ],
];

require __DIR__ . '/../partials/section-page.php';
