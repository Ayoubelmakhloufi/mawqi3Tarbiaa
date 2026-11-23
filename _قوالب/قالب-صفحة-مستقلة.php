<?php
/* ============================================================
   قالب صفحة مستقلة (ليست مقالًا ولا قسمًا)
   أمثلة: "من نحن"، "اتصل بنا"، أداة جديدة، صفحة تعريفية.

   ضعه في جذر الموقع:      اسم-الصفحة.php     ← الرابط /اسم-الصفحة
   أو داخل مجلد:           مجلد/index.php     ← الرابط /مجلد/

   ⚠️ عدّل عدد ../ في مسارات require حسب عمق الملف:
      في الجذر         → __DIR__ . '/partials/...'
      داخل مجلد واحد   → __DIR__ . '/../partials/...'
   ============================================================ */

require __DIR__ . '/partials/lib.php';

$meta = [
    'title'       => 'عنوان الصفحة',                 // 50–65 حرفًا
    'description' => 'وصف الصفحة كما يظهر في جوجل.',  // 80–160 حرفًا
    'canonical'   => '/اسم-الصفحة',                   // الرابط بالضبط
    'image'       => '/assets/img/og-default.jpg',
    'section'     => '',                              // slug القسم لإبرازه في القائمة، أو ''

    // اختياري: فتات الخبز
    'breadcrumb'  => [
        ['name' => 'عنوان الصفحة', 'url' => '/اسم-الصفحة'],
    ],
];

require __DIR__ . '/partials/header.php';
?>

<!-- ترويسة الصفحة -->
<section class="gradient-bg relative overflow-hidden">
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">عنوان الصفحة</h1>
      <p class="text-lg text-slate-300 leading-relaxed">جملة تعريفية قصيرة تحت العنوان.</p>
    </div>
  </div>
</section>

<!-- محتوى الصفحة -->
<section class="py-12 md:py-16 bg-white">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-3xl article-body">

      <h2>عنوان</h2>
      <p>اكتب هنا بنفس وسوم المقالات: p، h2، h3، ul، ol، table داخل table-wrap.</p>

      <div class="note-info">صندوق معلومة.</div>

    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/app-cta.php';   // بطاقة "حمّل التطبيق" — احذفها إن لم ترغب ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
