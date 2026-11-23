<?php
require __DIR__ . '/../partials/lib.php';

$Q    = tb_data('ikhtibarat');
$list = $Q['quizzes'] ?? [];
$tot  = array_sum(array_map(static fn (array $z): int => count($z['qs']), $list));

$meta = [
    'title'       => 'اختبارات تفاعلية في علوم التربية — ' . $tot . ' سؤالًا بتصحيح فوري',
    'description' => 'اختبر معارفك في بيداغوجيا الكفايات ونظريات التعلم والديداكتيك والتقويم: ' . $tot . ' سؤالًا بتصحيح فوري وشرح لكل جواب، مجانًا وبدون تسجيل.',
    'canonical'   => '/ikhtibarat/',
    'image'       => '/assets/img/og-dourous.jpg',
    'section'     => 'dourous',
    'breadcrumb'  => [
        ['name' => 'دروس علوم التربية', 'url' => '/dourous/'],
        ['name' => 'الاختبارات التفاعلية', 'url' => '/ikhtibarat/'],
    ],
    'jsonld'      => [[
        '@context'   => 'https://schema.org',
        '@type'      => 'CollectionPage',
        'name'       => 'الاختبارات التفاعلية في علوم التربية',
        'url'        => tb_abs('/ikhtibarat/'),
        'inLanguage' => 'ar',
        'isPartOf'   => ['@id' => SITE_URL . '/#website'],
        'mainEntity' => [
            '@type'           => 'ItemList',
            'numberOfItems'   => count($list),
            'itemListElement' => array_map(
                static fn (int $i, array $z): array => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'url'      => tb_abs('/ikhtibarat/' . $z['slug'] . '/'),
                    'name'     => 'اختبار ' . $z['title'],
                ],
                array_keys($list),
                $list
            ),
        ],
    ]],
];

require __DIR__ . '/../partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-gold rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-accent transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/dourous/" class="hover:text-accent transition-colors">دروس علوم التربية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">الاختبارات</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">الاختبارات التفاعلية</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-6">
        <b><?= $tot ?> سؤالًا</b> في علوم التربية والديداكتيك والتقويم.
        <b>اختر عدد الأسئلة</b> وستُنتقى عشوائيًا من البنك، فتحصل على اختبار مختلف في كل محاولة —
        بتصحيح فوري وشرح مفصّل لكل جواب.
      </p>
      <div class="flex flex-wrap gap-3 text-sm font-bold">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-sliders"></i> اختر عدد الأسئلة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-shuffle"></i> عشوائية كل مرة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-bolt"></i> تصحيح فوري</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-stopwatch"></i> مؤقّت اختياري</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-list-check"></i> مراجعة كاملة بعد النتيجة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-lock"></i> بلا تسجيل</span>
      </div>
    </div>
  </div>
</section>

<section class="py-14 md:py-20 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">

    <!-- التحدّي اليومي -->
    <a href="/tahaddi-yawmi"
       class="group flex flex-col md:flex-row items-start gap-5 bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 md:p-8 mb-5 hover:ring-primary/30 hover:shadow-xl transition-all">
      <span class="w-12 h-12 shrink-0 rounded-2xl bg-rose-50 text-rose-600 ring-1 ring-rose-200 flex items-center justify-center text-xl">
        <i class="fas fa-fire"></i>
      </span>
      <span class="flex-1 min-w-0">
        <span class="block text-xl md:text-2xl font-black text-slate-900 mb-2 group-hover:text-primary transition-colors">التحدّي اليومي</span>
        <span class="block text-slate-500 leading-relaxed">
          عشرة أسئلة تتغيّر كل يوم، <b>هي نفسها لكل الزوار</b>. عشر دقائق يوميًا
          أنفع من ثلاث ساعات مرة في الأسبوع — والتباعد هو ما يثبّت.
        </span>
      </span>
      <span class="shrink-0 inline-flex items-center gap-2 bg-slate-100 text-slate-700 font-black rounded-xl px-5 py-3 group-hover:gap-3 transition-all">
        تحدّي اليوم <i class="fas fa-arrow-left text-xs"></i>
      </span>
    </a>

    <!-- الاختبار العشوائي المركَّب -->
    <a href="/ikhtibarat/achwai/"
       class="group flex flex-col md:flex-row items-start gap-5 bg-primary rounded-3xl p-6 md:p-8 mb-8 hover:bg-secondary transition-colors">
      <span class="w-12 h-12 shrink-0 rounded-2xl bg-white/10 text-gold flex items-center justify-center text-xl">
        <i class="fas fa-shuffle"></i>
      </span>
      <span class="flex-1 min-w-0">
        <span class="block text-xl md:text-2xl font-black text-white mb-2">اختبار عشوائي من كل المحاور</span>
        <span class="block text-slate-300 leading-relaxed">
          سحب جديد في كل زيارة من بنك الموقع الكامل — <b class="text-gold"><?= $tot ?> سؤالًا</b>
          تختلط فيها المحاور كلها، وهو أقرب ما يكون إلى ظروف المباراة.
        </span>
      </span>
      <span class="shrink-0 inline-flex items-center gap-2 bg-white/10 text-white font-black rounded-xl px-5 py-3 group-hover:gap-3 transition-all">
        ابدأ <i class="fas fa-arrow-left text-xs"></i>
      </span>
    </a>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($list as $z):
          $t = tb_tint($z['tint']);
          $n = count($z['qs']); ?>
      <a href="/ikhtibarat/<?= e($z['slug']) ?>/"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <span class="w-12 h-12 rounded-2xl <?= e($t['bg']) ?> <?= e($t['text']) ?> ring-1 <?= e($t['ring']) ?> flex items-center justify-center text-xl mb-5">
          <i class="fas <?= e($z['icon']) ?>"></i>
        </span>
        <h2 class="text-lg font-black text-slate-900 leading-snug mb-2 group-hover:text-primary transition-colors"><?= e($z['title']) ?></h2>
        <p class="text-sm text-slate-500 leading-relaxed mb-5 flex-1"><?= e($z['desc']) ?></p>
        <span class="flex items-center justify-between text-xs font-bold pt-4 border-t border-slate-100">
          <span class="text-slate-400">بنك من <?= $n ?> سؤالًا</span>
          <span class="text-primary inline-flex items-center gap-1 group-hover:gap-2 transition-all">ابدأ <i class="fas fa-arrow-left text-[10px]"></i></span>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-14 md:py-20 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl article-body">
      <h2>لماذا الاختبار أنفع من إعادة القراءة؟</h2>
      <p>
        هذا من أكثر ما يُهدر فيه المترشحون وقتهم: يعيدون قراءة الملخص مرات فيشعرون أنهم يتقنونه،
        ثم يفاجَؤون في الامتحان. السبب أن إعادة القراءة تمنح <b>إحساسًا كاذبًا بالإتقان</b> —
        أنت تعرف النص لأنك رأيته، لا لأنك تملكه.
      </p>
      <div class="note-ok">
        <b>الاسترجاع من الذاكرة</b> — أي محاولة استخراج المعلومة دون النظر إليها — يثبّتها
        أقوى بكثير. وهذا بالضبط ما يفعله الاختبار: يجبرك على البحث في ذاكرتك، فيكشف
        <b>ما تظن أنك تعرفه ولا تعرفه فعلًا</b>.
      </div>

      <h2>كيف تستفيد من هذه الاختبارات؟</h2>
      <ol>
        <li><b>لا تخمّن عشوائيًا.</b> إن ترددت، فكّر أولًا ثم اختر — الخطأ المفكَّر فيه يعلّم أكثر من التخمين.</li>
        <li><b>اقرأ الشرح دائمًا</b>، حتى حين تصيب. كثير من الشروح تضيف تفصيلًا لم يكن في السؤال.</li>
        <li><b>سجّل أسئلتك الخاطئة</b> وعد إلى مقالها في <a href="/dourous/">قسم الدروس</a>.</li>
        <li><b>أعد الاختبار بعد يومين</b> لا بعد ساعة — التباعد هو ما يثبّت المعلومة.</li>
        <li><b>استعمل زر «أعد ما أخطأت فيه فقط»</b> بعد النتيجة — هو أسرع مردود في المراجعة كلها.</li>
        <li><b>ابدأ بالمحاور</b> ثم انتقل إلى <a href="/ikhtibarat/chamil/">الاختبار الشامل</a>
            و<a href="/ikhtibarat/achwai/">العشوائي من كل المحاور</a> كمحاكاة.</li>
        <li><b>شغّل المؤقّت</b> في الجولات الأخيرة فقط — دقيقة لكل سؤال — لتتعوّد ضغط الزمن.</li>
      </ol>

      <div class="note-info">
        <b>خصوصيتك محفوظة:</b> كل الاختبارات تعمل داخل متصفحك. لا تُرسَل إجاباتك إلى أي خادم،
        ولا تُخزَّن نتائجك، ولا نطلب منك أي تسجيل.
      </div>

      <h2>للمراجعة النظرية</h2>
      <div class="table-wrap">
        <table>
          <thead><tr><th>الاختبار</th><th>راجع قبله</th></tr></thead>
          <tbody>
            <tr><td><a href="/ikhtibarat/kafayat/">بيداغوجيا الكفايات</a></td><td><a href="/maqalat/bidaghojia-alkafayat">مقال بيداغوجيا الكفايات</a></td></tr>
            <tr><td><a href="/ikhtibarat/nadariyat/">نظريات التعلم</a></td><td><a href="/maqalat/nadariyat-attaallom">مقال نظريات التعلم الأربع</a></td></tr>
            <tr><td><a href="/ikhtibarat/didaktik/">الديداكتيك</a></td><td><a href="/maqalat/didaktik-3ama-wa-khassa">مقال الديداكتيك العامة والخاصة</a></td></tr>
            <tr><td><a href="/ikhtibarat/taqwim/">التقويم</a></td><td><a href="/maqalat/anwa3-attaqwim">مقال أنواع التقويم الثلاثة</a></td></tr>
            <tr><td><a href="/ikhtibarat/nafs/">علم النفس التربوي</a></td><td><a href="/maqalat/dhakaat-mota3adida">الذكاءات المتعددة</a> · <a href="/maqalat/bidaghojia-alkhataa">بيداغوجيا الخطأ</a></td></tr>
            <tr><td><a href="/ikhtibarat/tadbir/">تدبير الفصل والحياة المدرسية</a></td><td><a href="/maqalat/bidaghojia-alfariqiya">البيداغوجيا الفارقية</a> · <a href="/dourous/">قسم الدروس</a></td></tr>
            <tr><td><a href="/ikhtibarat/takhtit/">التخطيط والجذاذة</a></td><td><a href="/maqalat/kayfa-tabni-jadada">كيف تبني جذاذة درس ناجحة</a> · <a href="/maqalat/tasnif-bloom">تصنيف بلوم</a></td></tr>
            <tr><td><a href="/ikhtibarat/wadiyat/">الوضعيات المهنية</a></td><td><a href="/maqalat/manhajiyat-alwadiya-almihaniya">منهجية الإجابة عن سؤال الوضعية المهنية</a></td></tr>
            <tr><td><a href="/ikhtibarat/chamil/">الاختبار الشامل</a></td><td><a href="/moujam/">معجم المصطلحات</a> · <a href="/maqalat/kayfa-tastaid-mobarat-attaalim">خطة الاستعداد</a></td></tr>
            <tr><td><a href="/ikhtibarat/achwai/">عشوائي من كل المحاور</a></td><td><a href="/bitaqat/">بطاقات المراجعة</a> — امسح المصطلحات كلها أولًا</td></tr>
          </tbody>
        </table>
      </div>

      <div class="note-ok">
        <b>🗂️ قبل الاختبار، امسح المصطلحات ببطاقات المراجعة.</b>
        <a href="/bitaqat/">بطاقات المراجعة</a> تعرض عليك المصطلح وتطلب منك استحضار تعريفه
        قبل كشفه، وتعيد عليك ما لم تتقنه. هي أسرع طريقة لتغطية المعجم كاملًا قبل أن تختبر نفسك هنا.
      </div>
    </div>
  </div>
</section>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
