<?php
require __DIR__ . '/../../partials/lib.php';

$W        = tb_data('wathaiq');
$sections = $W['sections'] ?? [];
$docs     = $W['docs'] ?? [];
$totalKb  = array_sum(array_column($docs, 'kb'));

$meta = [
    'title'       => 'حقيبة الوثائق الرقمية — الوثائق الرسمية للمنظومة التربوية المغربية',
    'description' => 'تحميل ' . count($docs) . ' وثيقة رسمية مرتبة: الميثاق الوطني، الرؤية الاستراتيجية، القانون الإطار 51.17، خارطة الطريق، المناهج، التوجيه والأطر المرجعية.',
    'canonical'   => '/maktaba/wathaiq/',
    'image'       => '/assets/img/og-maktaba.jpg',
    'section'     => 'maktaba',
    'breadcrumb'  => [
        ['name' => 'المكتبة الرقمية', 'url' => '/maktaba/'],
        ['name' => 'حقيبة الوثائق الرقمية', 'url' => '/maktaba/wathaiq/'],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => 'حقيبة الوثائق الرقمية',
        'url'         => tb_abs('/maktaba/wathaiq/'),
        'inLanguage'  => 'ar',
        'isPartOf'    => ['@id' => SITE_URL . '/#website'],
        'mainEntity'  => [
            '@type'           => 'ItemList',
            'numberOfItems'   => count($sections),
            'itemListElement' => array_map(
                static fn (int $i, array $s): array => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'url'      => tb_abs('/maktaba/wathaiq/' . $s['slug'] . '/'),
                    'name'     => $s['title'],
                ],
                array_keys($sections),
                $sections
            ),
        ],
    ]],
];

require __DIR__ . '/../../partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-accent transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/maktaba/" class="hover:text-accent transition-colors">المكتبة الرقمية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">حقيبة الوثائق</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">حقيبة الوثائق الرقمية</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-6">
        الوثائق الرسمية للمنظومة التربوية المغربية، مجموعة ومرتبة في ثمانية ملفات،
        جاهزة للتحميل المباشر — للأستاذ والطالب والباحث والمترشح للمباريات.
      </p>
      <div class="flex flex-wrap gap-3">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-2xl px-5 py-3 font-bold text-sm">
          <i class="fas fa-file-pdf"></i> <?= e(tb_docs_ar(count($docs))) ?>
        </span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-2xl px-5 py-3 font-bold text-sm">
          <i class="fas fa-folder-open"></i> <?= count($sections) ?> ملفات
        </span>
        <span class="inline-flex items-center gap-2 bg-accent/20 text-accent rounded-2xl px-5 py-3 font-bold text-sm">
          <i class="fas fa-download"></i> مجانية بالكامل
        </span>
      </div>
    </div>
  </div>
</section>

<!-- شبكة الملفات -->
<section class="py-14 md:py-20 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($sections as $s):
          $sel = array_filter($docs, static fn (array $d): bool => $d['s'] === $s['slug']);
          if (!$sel) { continue; }
          $t  = tb_tint($s['tint']);
          $kb = array_sum(array_column($sel, 'kb')); ?>
      <a href="/maktaba/wathaiq/<?= e($s['slug']) ?>/"
         class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
        <div class="flex items-start justify-between mb-5">
          <span class="w-12 h-12 rounded-2xl <?= e($t['bg']) ?> <?= e($t['text']) ?> ring-1 <?= e($t['ring']) ?> flex items-center justify-center text-xl">
            <i class="fas <?= e($s['icon']) ?>"></i>
          </span>
          <span class="text-xs font-black text-slate-300">الملف <?= e($s['code']) ?></span>
        </div>
        <h2 class="text-lg font-black text-slate-900 leading-snug mb-2 group-hover:text-primary transition-colors">
          <?= e($s['title']) ?>
        </h2>
        <span class="flex items-center justify-between text-xs font-bold pt-4 mt-auto border-t border-slate-100">
          <span class="text-slate-400"><?= e(tb_docs_ar(count($sel))) ?> · <?= $kb >= 1024 ? number_format($kb / 1024, 1) . ' م.ب' : $kb . ' ك.ب' ?></span>
          <span class="text-primary inline-flex items-center gap-1 group-hover:gap-2 transition-all">
            افتح <i class="fas fa-arrow-left text-[10px]"></i>
          </span>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- شرح -->
<section class="py-14 md:py-20 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl article-body">
      <h2>لماذا هذه الحقيبة؟</h2>
      <p>
        الأستاذ والطالب والباحث في علوم التربية يحتاجون جميعًا إلى العودة المستمرة لوثائق رسمية بعينها:
        في التحضير للمباريات، وفي إعداد البحوث، وفي الممارسة اليومية داخل المؤسسة.
        المشكلة أن هذه الوثائق <b>متفرقة عبر بوابات ومصادر متعددة</b>، وكثير منها يصعب العثور عليه.
      </p>
      <p>جمعناها هنا ورتبناها في ثمانية ملفات، بعناوينها الرسمية الكاملة، وبتحميل مباشر بلا تسجيل ولا إعلانات.</p>

      <h2>كيف تستفيد منها في التحضير للمباراة؟</h2>
      <ol>
        <li>
          <b>ابدأ بالوثائق الأربع الكبرى:</b>
          <a href="/maktaba/wathaiq/mithaq/">الميثاق</a> ←
          <a href="/maktaba/wathaiq/rouya/">الرؤية</a> ←
          <a href="/maktaba/wathaiq/qanoun-51-17/">القانون الإطار</a> ←
          <a href="/maktaba/wathaiq/kharita/">خارطة الطريق</a>.
          أسئلة المستجدات والتشريع تدور حولها أساسًا.
        </li>
        <li><b>ثم <a href="/maktaba/wathaiq/taqwim/">الإطار المرجعي لمادتك</a></b> — يخبرك بما يُقوَّم فعلًا لا بما يُدرَّس فقط.</li>
        <li><b>ثم <a href="/maktaba/wathaiq/manahij/">التوجيهات التربوية لمادتك</a></b> — تعطيك الطرائق المعتمدة رسميًا.</li>
        <li><b>أما المذكرات</b> فاعرف موضوع كل واحدة ورقمها؛ لا تحفظها بل اعرف <b>متى تُستدعى</b>.</li>
      </ol>

      <div class="note">
        <b>تنبيه ضروري:</b> النصوص التنظيمية <b>تُحدَّث وتُنسخ باستمرار</b>، والمذكرات المرتبطة بسنة معينة
        تفقد صلاحيتها بانتهائها. تحقّق دائمًا من <b>سريان المفعول</b> في المصدر الرسمي
        (البوابة الوطنية لوزارة التربية الوطنية والأكاديميات الجهوية والجريدة الرسمية)
        قبل الاعتماد على أي وثيقة في إجراء رسمي.
      </div>

      <div class="note-ok">
        <b>للمزيد:</b>
        <a href="/maqalat/kayfa-tastaid-mobarat-attaalim">خطة الاستعداد لمباراة التعليم</a> ·
        <a href="/maqalat/alwathaiq-almihaniya-lil-oustad">الوثائق المهنية للأستاذ</a> ·
        <a href="/dourous/">دروس علوم التربية</a> ·
        <a href="/maktaba/">المكتبة الرقمية</a>
      </div>
    </div>
  </div>
</section>

<?php
require __DIR__ . '/../../partials/app-cta.php';
require __DIR__ . '/../../partials/footer.php';
