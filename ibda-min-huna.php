<?php
/**
 * «ابدأ من هنا» — مسار موجّه للزائر الجديد حسب حاله.
 *
 * الفكرة: من يصل لأول مرة يجد عشرات الصفحات بلا ترتيب، فيغادر.
 * هذه الصفحة تسأله من هو ثم ترسم له مسارًا من محتوى الموقع القائم.
 *
 * ⚠️ لا تُكتب فيها أعداد يدويًا ولا روابط مقالات عمياء:
 * الأعداد تُقرأ من مصادرها، وكل مقال يُتحقق من وجوده قبل عرضه،
 * فإن حُذف مقال يومًا اختفى سطره بدل أن يصير رابطًا مكسورًا.
 */

require __DIR__ . '/partials/lib.php';

$nArticles = count(tb_articles());
$nTerms    = count(tb_data('moujam')['terms'] ?? []);
$nDocs     = count(tb_data('wathaiq')['docs'] ?? []);
$quizzes   = tb_data('ikhtibarat')['quizzes'] ?? [];
$nQ        = array_sum(array_map(static fn (array $z): int => count($z['qs']), $quizzes));

/** سطر مقال في المسار — يُسقَط تلقائيًا إن لم يعد المقال موجودًا */
function tb_step_article(string $slug, string $why): string
{
    $a = tb_article($slug);
    if (!$a || !empty($a['draft'])) {
        return '';
    }

    return '<li><a href="' . e(tb_article_url($a)) . '"><b>' . e($a['title']) . '</b></a> — ' . $why . '</li>';
}

/* المسارات الأربعة. كل خطوة: عنوان + سطور HTML. */
$paths = [
    [
        'key'   => 'mobarat',
        'label' => 'مترشّح لمباراة التعليم',
        'icon'  => 'fa-chalkboard-user',
        'tint'  => 'emerald',
        'intro' => 'هدفك الولوج إلى المراكز الجهوية لمهن التربية والتكوين. المسار أدناه يرتّب لك '
                 . 'ما تقرأه وما تتدرّب عليه، من الفهم إلى المحاكاة.',
        'steps' => [
            ['t' => 'افهم المسار قبل أن تراجع', 'html' => '<ul>'
                . tb_step_article('kayfa-tastaid-mobarat-attaalim', 'خطة استعداد واقعية في ستة محاور، ابدأ بها.')
                . tb_step_article('masalik-attadris-attalata', 'الابتدائي أم الإعدادي أم التأهيلي؟ اختر قبل أن تراجع.')
                . tb_step_article('madha-baad-annajah', 'ما الذي ينتظرك بعد النجاح — يعينك على تقدير الالتزام.')
                . '</ul>'],
            ['t' => 'ابنِ الأساس النظري', 'html' => '<ul>'
                . tb_step_article('nadariyat-attaallom', 'ابدأ بالنظريات: هي التي تفسّر البيداغوجيات بعدها.')
                . tb_step_article('bidaghojia-alkafayat', 'المقاربة المعتمدة رسميًا في المنهاج المغربي.')
                . tb_step_article('didaktik-3ama-wa-khassa', 'الفرق بين البيداغوجيا والديداكتيك — سؤال متكرر.')
                . tb_step_article('anwa3-attaqwim', 'التقويم بأنواعه الثلاثة ومتى يُستعمل كل واحد.')
                . '</ul>'],
            ['t' => 'احفظ المصطلحات بالاسترجاع لا بالقراءة', 'html' =>
                '<p>امسح <a href="/bitaqat/">بطاقات المراجعة</a> مجالًا مجالًا: البطاقة تعرض المصطلح '
                . 'وتطلب منك التعريف من ذاكرتك قبل كشفه، وتعيد عليك ما لم تتقنه. '
                . 'وللتعريف الكامل عد إلى <a href="/moujam/">المعجم</a>.</p>'],
            ['t' => 'اختبر نفسك، ثم أعد ما أخطأت فيه', 'html' =>
                '<p>ابدأ بالمحاور المفردة في <a href="/ikhtibarat/">الاختبارات التفاعلية</a>، '
                . 'واستعمل بعد كل نتيجة زر <b>«أعد ما أخطأت فيه فقط»</b> — هو أسرع مردود في المراجعة كلها.</p>'],
            ['t' => 'أتقن سؤال الوضعية المهنية', 'html' => '<ul>'
                . tb_step_article('manhajiyat-alwadiya-almihaniya', 'منهجية الإجابة خطوة بخطوة — وهي ما يفرّق المترشحين.')
                . '<li><a href="/ikhtibarat/wadiyat/"><b>اختبار الوضعيات المهنية</b></a> — حالات واقعية بمنهجية التحليل.</li>'
                . '</ul>'],
            ['t' => 'احسب نقطتك وحاكِ المباراة', 'html' =>
                '<p><a href="/hisab-intiqa-crmef">حاسبة الانتقاء الأولي للمراكز الجهوية</a> تعطيك نقطتك على 80. '
                . 'ثم شغّل <b>المؤقّت</b> في <a href="/ikhtibarat/achwai/">الاختبار العشوائي من كل المحاور</a> '
                . 'لتتعوّد ضغط الزمن قبل يوم الامتحان.</p>'],
        ],
    ],
    [
        'key'   => 'ijaza',
        'label' => 'طالب الإجازة في التربية',
        'icon'  => 'fa-graduation-cap',
        'tint'  => 'sky',
        'intro' => 'سواء كنت مقبلًا على الترشح أو داخل المسلك فعلًا، هذا ما يهمّك بالترتيب.',
        'steps' => [
            ['t' => 'افهم المسلك قبل الترشح', 'html' => '<ul>'
                . tb_step_article('masar-ijaza-tarbiya', 'المسار كاملًا: من الباكالوريا إلى القسم.')
                . tb_step_article('bilagh-ijaza-tarbiya-2026-2027', 'البلاغ بالمقاعد والآجال والشروط.')
                . '</ul>'],
            ['t' => 'احسب معدل انتقائك', 'html' =>
                '<p><a href="' . e(CALC_URL) . '">حاسبة معدل الانتقاء</a> للمدارس العليا للأساتذة '
                . '(ENS · ESEF · FSE): اختر التخصص والشعبة وأدخل نقطتَي الوطني والجهوي، '
                . 'وستحصل على المعدل مع خطوات الحساب وجدول المعاملات.</p>'],
            ['t' => 'استعدّ للمقابلة الشفوية', 'html' => '<ul>'
                . tb_step_article('almoqabala-achafawiya-ens', 'شبكة التنقيط الرسمية وما تبحث عنه اللجنة فعلًا.')
                . '</ul>'],
            ['t' => 'ابدأ الأساس النظري مبكرًا', 'html' =>
                '<p>ما ستدرسه في المسلك هو نفسه ما في <a href="/dourous/">قسم الدروس</a>. '
                . 'البدء مبكرًا يجعل الفصول الأولى مراجعةً لا اكتشافًا. '
                . 'وابدأ بالمصطلحات في <a href="/moujam/">المعجم</a> فهي لغة المجال كله.</p>'],
            ['t' => 'اجمع ملفاتك الدراسية', 'html' => '<ul>'
                . tb_step_article('machrou3-jam3-milaffat-ijaza-tarbiya', 'مشروع تجميع ملفات الطلبة — شارك واستفد.')
                . '</ul>'],
        ],
    ],
    [
        'key'   => 'motadarrib',
        'label' => 'أستاذ متدرّب بالمركز الجهوي',
        'icon'  => 'fa-school',
        'tint'  => 'indigo',
        'intro' => 'سنة التكوين قصيرة ومكثّفة. ركّز على ما يُطلب منك فعلًا في الوضعيات المهنية والتقويم.',
        'steps' => [
            ['t' => 'ابدأ بدليل المتدرّب', 'html' => '<ul>'
                . tb_step_article('dalil-attakwin-crmef', 'ما ينتظرك في المركز: التكوين والتدريب والتقويم.')
                . '</ul>'],
            ['t' => 'أتقن التخطيط والجذاذة', 'html' => '<ul>'
                . tb_step_article('kayfa-tabni-jadada', 'بناء جذاذة درس ناجحة خطوة بخطوة.')
                . tb_step_article('tasnif-bloom', 'تصنيف بلوم لتنويع أسئلتك وأهدافك.')
                . '<li><a href="/molid-jadada"><b>مولّد الجذاذة</b></a> — املأ الحقول واطبع جذاذتك جاهزة.</li>'
                . '<li><a href="/ikhtibarat/takhtit/"><b>اختبار التخطيط والجذاذة</b></a> — تحقّق من فهمك.</li>'
                . '</ul>'],
            ['t' => 'تعلّم تدبير الفصل', 'html' => '<ul>'
                . tb_step_article('bidaghojia-alfariqiya', 'كيف تدرّس قسمًا غير متجانس.')
                . tb_step_article('bidaghojia-alkhataa', 'حوّل خطأ المتعلم إلى نقطة انطلاق.')
                . '<li><a href="/ikhtibarat/tadbir/"><b>اختبار تدبير الفصل والحياة المدرسية</b></a></li>'
                . '</ul>'],
            ['t' => 'ارجع إلى النصوص الرسمية', 'html' =>
                '<p><a href="/maktaba/wathaiq/">حقيبة الوثائق</a> فيها ' . $nDocs . ' وثيقة رسمية: '
                . 'المناهج والتوجيهات التربوية لمادتك، والأطر المرجعية للامتحانات، والمذكرات المنظِّمة. '
                . 'ابدأ بملف <a href="/maktaba/wathaiq/manahij/">المناهج والبرامج</a> وابحث عن مادتك وسلكك بالتحديد.</p>'],
        ],
    ],
    [
        'key'   => 'oustad',
        'label' => 'أستاذ ممارس',
        'icon'  => 'fa-user-tie',
        'tint'  => 'amber',
        'intro' => 'لستَ في حاجة إلى مسار من الصفر، بل إلى ما يخدم قسمك غدًا وما يجدّد معارفك.',
        'steps' => [
            ['t' => 'أدوات تستعملها في عملك', 'html' =>
                '<ul>'
                . '<li><a href="/molid-jadada"><b>مولّد الجذاذة</b></a> — جذاذة منسقة جاهزة للطباعة في دقائق.</li>'
                . '<li><a href="/maktaba/wathaiq/">حقيبة الوثائق</a> — التوجيهات التربوية لمادتك والأطر المرجعية.</li>'
                . '<li><a href="/moujam/">المعجم</a> — ' . $nTerms . ' مصطلحًا بشرح عملي لا بتعريف نظري فقط.</li>'
                . '</ul>'],
            ['t' => 'جدّد ممارستك', 'html' => '<ul>'
                . tb_step_article('bidaghojia-alfariqiya', 'التعامل مع قسم غير متجانس.')
                . tb_step_article('dhakaat-mota3adida', 'الذكاءات المتعددة: استعمال صحيح لا وسم للمتعلمين.')
                . tb_step_article('bidaghojia-almachrou3', 'كيف تجعل التعلم ذا معنى.')
                . '</ul>'],
            ['t' => 'الرقمي في قسمك', 'html' => '<ul>'
                . tb_step_article('adawat-raqamiya-lil-oustad', 'ماذا تحتاج فعلًا من الأدوات الرقمية.')
                . tb_step_article('dhaka-istinaai-fi-attadris', 'الذكاء الاصطناعي بحدود مهنية واضحة.')
                . tb_step_article('salama-raqamiya', 'ما يجب أن يعرفه الأستاذ والمتعلم.')
                . '</ul>'],
            ['t' => 'استعدّ لامتحان مهني', 'html' =>
                '<p>إن كنت مقبلًا على امتحان الكفاءة المهنية أو تغيير الإطار، '
                . '<a href="/ikhtibarat/">الاختبارات التفاعلية</a> (' . $nQ . ' سؤالًا) '
                . 'و<a href="/ikhtibarat/wadiyat/">بنك الوضعيات المهنية</a> أقرب ما يكون إلى صيغة الأسئلة المهنية.</p>'],
        ],
    ],
];

$meta = [
    'title'       => 'ابدأ من هنا — مسارك في منصة تربية حسب حالك',
    'description' => 'لا تعرف من أين تبدأ؟ اختر حالك — مترشح لمباراة التعليم أو طالب إجازة في التربية '
                   . 'أو أستاذ متدرّب أو ممارس — وسنرسم لك مسار القراءة والتدرّب بالترتيب.',
    'canonical'   => '/ibda-min-huna',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'ibda',
    'breadcrumb'  => [
        ['name' => 'ابدأ من هنا', 'url' => '/ibda-min-huna'],
    ],
];

require __DIR__ . '/partials/header.php';
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
        <li class="text-white/80" aria-current="page">ابدأ من هنا</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">ابدأ من هنا</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-6">
        الموقع فيه <b><?= $nArticles ?> مقالًا</b> و<b><?= $nTerms ?> مصطلحًا</b>
        و<b><?= $nQ ?> سؤالًا</b> و<b><?= $nDocs ?> وثيقة</b>.
        كثير إن بدأتَ عشوائيًا. <b>اختر حالك</b> وسنرتّب لك الطريق.
      </p>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14" x-data="{ who: 'mobarat' }">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl mx-auto">

      <!-- اختيار الحال -->
      <h2 class="text-xl md:text-2xl font-black text-primary flex items-center gap-2.5 mb-5">
        <i class="fas fa-user-check text-accent"></i> من أنت؟
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-10">
        <?php foreach ($paths as $p): ?>
        <button type="button" @click="who = '<?= e($p['key']) ?>'"
                :class="who === '<?= e($p['key']) ?>' ? 'bg-primary text-white ring-primary shadow-lg shadow-primary/20' : 'bg-white text-slate-700 ring-slate-200 hover:ring-primary/40'"
                class="flex items-center gap-3 p-5 rounded-2xl ring-1 font-black text-right transition-all">
          <i class="fas <?= e($p['icon']) ?> text-xl"></i>
          <span><?= e($p['label']) ?></span>
        </button>
        <?php endforeach; ?>
      </div>

      <!-- المسارات -->
      <?php foreach ($paths as $p): $t = tb_tint($p['tint']); ?>
      <div x-show="who === '<?= e($p['key']) ?>'" x-cloak class="faq-panel">
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl md:text-2xl font-black text-slate-900 mb-3">مسار <?= e($p['label']) ?></h2>
          <p class="text-slate-500 leading-relaxed"><?= e($p['intro']) ?></p>
        </div>

        <div class="space-y-4">
          <?php foreach ($p['steps'] as $i => $step): ?>
          <div class="bg-white rounded-2xl ring-1 ring-slate-200/80 p-6 md:p-7">
            <div class="flex items-start gap-4 mb-3">
              <span class="w-10 h-10 shrink-0 rounded-xl <?= e($t['bg']) ?> <?= e($t['text']) ?> ring-1 <?= e($t['ring']) ?> flex items-center justify-center font-black">
                <?= $i + 1 ?>
              </span>
              <h3 class="text-lg font-black text-slate-900 leading-snug mt-1"><?= e($step['t']) ?></h3>
            </div>
            <div class="article-body"><?= $step['html'] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- مشترك بين الجميع -->
      <div class="mt-10 bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body">
        <h2>مهما كان حالك</h2>
        <ul>
          <li><b><a href="/baht/">البحث في الموقع</a></b> — إن كنت تبحث عن موضوع بعينه.</li>
          <li><b><a href="/mokhattat-almourajaa">مخطّط المراجعة</a></b> — أدخل تاريخ امتحانك وساعاتك المتاحة واحصل على جدول أسبوعي.</li>
          <li><b><a href="/adawat/">الأدوات والحاسبات</a></b> — كلها تعمل داخل متصفحك بلا تسجيل.</li>
          <li><b><a href="/mostajaddat/">المستجدات</a></b> — للبلاغات والأخبار.</li>
        </ul>

        <div class="note-ok">
          <b>قاعدة واحدة تختصر عليك شهورًا:</b> لا تُعِد قراءة الملخص.
          <b>استرجع من ذاكرتك</b> ثم تحقّق. إعادة القراءة تمنح إحساسًا كاذبًا بالإتقان —
          أنت تعرف النص لأنك رأيته لا لأنك تملكه. والاختبار هو ما يكشف الفرق.
        </div>

        <div class="note-info">
          <b>على هاتفك:</b> <a href="/tatbiq/">تطبيق تربية</a> يوفّر شجرة مهارات متدرجة
          مع تتبّع لتقدّمك — مجانًا.
        </div>
      </div>

    </div>
  </div>
</div>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
