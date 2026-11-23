<?php
require __DIR__ . '/partials/lib.php';

$articles = tb_articles();
$W        = tb_data('wathaiq');
$M        = tb_data('moujam');
$Q        = tb_data('ikhtibarat');

$meta = [
    'title'       => 'من نحن — منصة تربية',
    'description' => 'مشروع تربوي مغربي مستقل وغير ربحي: من يقف وراءه، ولماذا أُنشئ، وكيف نتحقق من المعلومة قبل نشرها.',
    'canonical'   => '/man-nahnu',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => '',
    'breadcrumb'  => [['name' => 'من نحن', 'url' => '/man-nahnu']],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'AboutPage',
        'name'        => 'من نحن — منصة تربية',
        'url'         => tb_abs('/man-nahnu'),
        'inLanguage'  => 'ar',
        'mainEntity'  => [
            '@type'       => 'Organization',
            '@id'         => SITE_URL . '/#organization',
            'name'        => SITE_NAME,
            'url'         => SITE_URL,
            'email'       => MAIL_MAIN,
            'foundingDate'=> '2026',
            'areaServed'  => ['@type' => 'Country', 'name' => 'المغرب'],
            'founder'     => ['@type' => 'Person', 'name' => 'أيوب المخلوفي', 'sameAs' => DEV_FB_URL],
            'sameAs'      => [FB_URL, IG_URL, WA_URL],
        ],
    ]],
];

require __DIR__ . '/partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">من نحن</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed">
        مشروع تربوي مغربي <b>مستقل وغير ربحي</b>، بدأ تطبيقًا للهاتف وصار بوابة تربوية متكاملة.
      </p>
    </div>
  </div>
</section>

<!-- أرقام -->
<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl">
      <?php
      $stats = [
          ['fa-newspaper', count($articles), 'مقالًا'],
          ['fa-file-pdf', count($W['docs'] ?? []), 'وثيقة'],
          ['fa-book', count($M['terms'] ?? []), 'مصطلحًا'],
          ['fa-circle-question', array_sum(array_map(static fn (array $z): int => count($z['qs']), $Q['quizzes'] ?? [])), 'سؤالًا'],
      ];
      foreach ($stats as [$icon, $num, $label]): ?>
      <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200 p-5 text-center">
        <i class="fas <?= e($icon) ?> text-accent text-xl mb-2"></i>
        <p class="text-2xl md:text-3xl font-black text-primary"><?= number_format($num) ?></p>
        <p class="text-sm font-bold text-slate-500"><?= e($label) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-14 md:py-20 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl article-body">

      <h2>لماذا أُنشئت منصة تربية؟</h2>
      <p>
        المترشح لمباريات التعليم بالمغرب، والطالب في مسلك الإجازة في التربية، والأستاذ الممارس —
        ثلاثتهم يواجهون المشكل نفسه: <b>المعلومة موجودة لكنها مبعثرة</b>.
        بلاغ في موقع، مذكرة في آخر، شرح في مجموعة، ووثيقة في أرشيف لا يُعثر عليه.
      </p>
      <p>
        بدأ المشروع <a href="/tatbiq/">تطبيقًا للهاتف</a> يجمع دروس علوم التربية في شجرة مهارات
        متدرجة، ثم اتضح أن الحاجة أوسع: <b>بوابة تجمع وترتّب وتشرح</b>.
        فكان هذا الموقع.
      </p>

      <h2>مبادئنا الأربعة</h2>
      <div class="table-wrap">
        <table>
          <thead><tr><th>المبدأ</th><th>ماذا يعني عمليًا</th></tr></thead>
          <tbody>
            <tr><td><b>المجانية</b></td><td>كل ما في الموقع مجاني بلا اشتراك ولا تسجيل ولا إعلانات مزعجة.</td></tr>
            <tr><td><b>الاستقلالية</b></td><td>مشروع شخصي غير ربحي، لا يمثّل أي جهة رسمية ولا يتحدث باسمها.</td></tr>
            <tr><td><b>الدقة</b></td><td>لا ننشر معطى رسميًا دون مصدر، ولا نخترع أرقامًا أو آجالًا.</td></tr>
            <tr><td><b>الخصوصية</b></td><td>الحاسبات والاختبارات تعمل داخل متصفحك — لا تُرسَل معطياتك إلى أي خادم.</td></tr>
          </tbody>
        </table>
      </div>

      <h2>كيف نتحقق من المعلومة؟</h2>
      <p>هذه أهم فقرة في الصفحة، ونلتزم بها حرفيًا:</p>
      <ol>
        <li>
          <b>المعطيات الرسمية</b> (الآجال، المقاعد، المعاملات، العتبات) لا تُنشر إلا من
          <b>وثيقة رسمية</b> نذكرها صراحة مع سنتها.
        </li>
        <li>
          <b>المفاهيم التربوية</b> تُشرح بالرجوع إلى المراجع المعتمدة في علوم التربية،
          مع تسمية روّاد كل نظرية.
        </li>
        <li>
          <b>ما لا نتحقق منه لا ننشره.</b> نفضّل ترك فراغ على ملئه بمعلومة قد تضلّل مترشحًا
          في قرار يخص مستقبله.
        </li>
        <li>
          <b>نصحّح أخطاءنا علنًا.</b> إن اكتشفنا خطأ نصححه ونضيف تاريخ التحديث في الصفحة.
        </li>
      </ol>

      <div class="note">
        <b>تنبيه دائم:</b> منصة تربية <b>ليست جهة رسمية</b> ولا تنوب عن وزارة التربية الوطنية
        ولا عن الأكاديميات الجهوية ولا عن المؤسسات الجامعية.
        <b>المصدر الملزم الوحيد هو البلاغ الرسمي</b> للمؤسسة والدورة المعنية.
        ما تجده هنا تأطير للفهم وتيسير للوصول.
      </div>

      <h2>ماذا تجد في الموقع؟</h2>
      <ul>
        <li><a href="/mobarat-attaalim/">مباراة التعليم</a> — الولوج إلى المراكز الجهوية لمهن التربية والتكوين.</li>
        <li><a href="/ijaza-tarbiya/">الإجازة في التربية</a> — المدارس العليا للأساتذة: الانتقاء والمسالك والمقاعد.</li>
        <li><a href="/takwin-crmef/">التكوين بالمراكز الجهوية</a> — ما بعد النجاح.</li>
        <li><a href="/dourous/">دروس علوم التربية</a> و<a href="/moujam/">معجم المصطلحات</a> و<a href="/ikhtibarat/">اختبارات تفاعلية</a>.</li>
        <li><a href="/maktaba/wathaiq/">حقيبة الوثائق الرقمية</a> — الوثائق الرسمية مرتبة وجاهزة للتحميل.</li>
        <li><a href="/adawat/">الأدوات والحاسبات</a> — بحسابات مفصّلة خطوة بخطوة.</li>
        <li><a href="/taalim-raqami/">التعليم الرقمي</a> و<a href="/mostajaddat/">المستجدات</a>.</li>
      </ul>

      <h2>من وراء المشروع؟</h2>
      <p>
        <b>أيوب المخلوفي</b> — مهتم بعلوم التربية والتعليم الرقمي، صاحب فكرة المشروع ومطوّره.
        المشروع <b>فردي وغير ربحي</b>، يُموَّل ذاتيًا، ويستمر بدعم اختياري من المستفيدين
        لتغطية تكاليف التشغيل.
      </p>
      <p>
        <a href="<?= e(DEV_FB_URL) ?>" target="_blank" rel="noopener">صفحة المطوّر على فيسبوك</a>
      </p>

      <h2>كيف تساهم؟</h2>
      <ul>
        <li><b>صحّح لنا:</b> لاحظت خطأً أو معطى قديمًا؟ راسلنا وسنصححه بسرعة.</li>
        <li><b>شارك وثائقك:</b> عبر <a href="/maqalat/machrou3-jam3-milaffat-ijaza-tarbiya">مشروع جمع الملفات</a>.</li>
        <li><b>انشر الرابط</b> لمن يحتاجه — هذا أنفع دعم.</li>
        <li><b>ادعم التطبيق</b> عبر <a href="/tatbiq/">صفحة التطبيق</a> إن أردت.</li>
      </ul>

      <div class="note-ok">
        <b>تواصل معنا:</b> <a href="/ittisal">صفحة الاتصال</a> ·
        <a href="mailto:<?= e(MAIL_MAIN) ?>"><?= e(MAIL_MAIN) ?></a>
      </div>
    </div>
  </div>
</section>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
