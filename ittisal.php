<?php
require __DIR__ . '/partials/lib.php';

$meta = [
    'title'       => 'اتصل بنا — منصة تربية',
    'description' => 'راسل منصة تربية: تصحيح معلومة، اقتراح موضوع أو أداة، مشاركة وثيقة، أو أي استفسار.',
    'canonical'   => '/ittisal',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => '',
    'breadcrumb'  => [['name' => 'اتصل بنا', 'url' => '/ittisal']],
    'jsonld'      => [[
        '@context'   => 'https://schema.org',
        '@type'      => 'ContactPage',
        'name'       => 'اتصل بنا — منصة تربية',
        'url'        => tb_abs('/ittisal'),
        'inLanguage' => 'ar',
        'about'      => ['@id' => SITE_URL . '/#organization'],
    ]],
];

/* قنوات التواصل — تُعدَّل من partials/lib.php لا من هنا */
$channels = [
    ['fa-envelope',  'البريد الإلكتروني', MAIL_MAIN, 'mailto:' . MAIL_MAIN, 'emerald', 'للتصحيحات والاقتراحات والاستفسارات — الأنسب للمواضيع المفصّلة.'],
    ['fa-whatsapp',  'قناة واتساب', 'تابع الجديد', WA_URL, 'lime', 'إشعارات بالمقالات والمستجدات فور نشرها.', true],
    ['fa-facebook',  'فيسبوك', 'صفحة المنصة', FB_URL, 'sky', 'أخبار المشروع والتفاعل مع المجتمع التربوي.', true],
    ['fa-instagram', 'إنستغرام', 'حساب المنصة', IG_URL, 'rose', 'ملخصات بصرية ومحتوى مختصر.', true],
];

require __DIR__ . '/partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">اتصل بنا</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed">
        لاحظت خطأً؟ تقترح موضوعًا أو أداة؟ تريد مشاركة وثيقة؟ نقرأ كل الرسائل.
      </p>
    </div>
  </div>
</section>

<section class="py-14 md:py-20 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto">

      <div class="grid gap-5 sm:grid-cols-2 mb-12">
        <?php foreach ($channels as $ch):
            [$icon, $title, $value, $url, $tint, $desc] = $ch;
            $ext = $ch[6] ?? false;
            $t = tb_tint($tint); ?>
        <a href="<?= e($url) ?>" <?= $ext ? 'target="_blank" rel="noopener"' : '' ?>
           class="group flex flex-col bg-white rounded-3xl ring-1 ring-slate-200/80 p-6 hover:ring-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
          <span class="w-12 h-12 rounded-2xl <?= e($t['bg']) ?> <?= e($t['text']) ?> ring-1 <?= e($t['ring']) ?> flex items-center justify-center text-xl mb-4">
            <i class="<?= str_starts_with($icon, 'fa-envelope') ? 'fas' : 'fab' ?> <?= e($icon) ?>"></i>
          </span>
          <h2 class="text-lg font-black text-slate-900 mb-1 group-hover:text-primary transition-colors"><?= e($title) ?></h2>
          <p class="text-sm font-bold text-primary mb-3 break-all"><?= e($value) ?></p>
          <p class="text-sm text-slate-500 leading-relaxed flex-1"><?= e($desc) ?></p>
        </a>
        <?php endforeach; ?>
      </div>

      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-10">
        <div class="article-body">

          <h2>ساعدنا لنساعدك أسرع</h2>
          <p>كلما كانت رسالتك أدق كان جوابنا أسرع وأنفع. اذكر في رسالتك:</p>

          <div class="table-wrap">
            <table>
              <thead><tr><th>نوع الرسالة</th><th>ما تذكره</th></tr></thead>
              <tbody>
                <tr>
                  <td><b>تصحيح معلومة</b></td>
                  <td><b>رابط الصفحة</b> + المعلومة الخاطئة + <b>المصدر الصحيح</b> إن توفر</td>
                </tr>
                <tr>
                  <td><b>اقتراح موضوع</b></td>
                  <td>الموضوع + لمن يفيد (مترشح؟ أستاذ؟ طالب؟) + لماذا تحتاجه</td>
                </tr>
                <tr>
                  <td><b>اقتراح أداة</b></td>
                  <td>ما تحسبه الأداة + المعادلة أو المعايير + مصدرها الرسمي</td>
                </tr>
                <tr>
                  <td><b>مشاركة وثيقة</b></td>
                  <td>الوثيقة + مصدرها + سنتها + هل هي سارية المفعول</td>
                </tr>
                <tr>
                  <td><b>مشكل تقني</b></td>
                  <td>رابط الصفحة + نوع الجهاز والمتصفح + <b>لقطة شاشة</b> إن أمكن</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="note-ok">
            <b>التصحيحات لها الأولوية القصوى.</b> إن لاحظت معطى خاطئًا أو قديمًا — خاصة في
            الآجال أو المعاملات أو العتبات — راسلنا فورًا. دقة هذه المعطيات تخص قرارات
            تمسّ مستقبل أشخاص، ونعالجها في أسرع وقت.
          </div>

          <h2>قبل أن تراسلنا</h2>
          <p>قد تجد جوابك مباشرة هنا:</p>
          <ul>
            <li><b>سؤال عن مفهوم تربوي؟</b> ابحث في <a href="/moujam/">معجم المصطلحات</a> (74 مصطلحًا).</li>
            <li><b>سؤال عن الإجازة في التربية؟</b> <a href="/maqalat/bilagh-ijaza-tarbiya-2026-2027">بلاغ 2026-2027</a> فيه الآجال والمقاعد والشروط.</li>
            <li><b>سؤال عن حساب معدلك؟</b> <a href="/حساب_معدل_الانتقاء">حاسبة الانتقاء</a> أو <a href="/hisab-intiqa-crmef">حاسبة المراكز الجهوية</a>.</li>
            <li><b>تبحث عن وثيقة رسمية؟</b> <a href="/maktaba/wathaiq/">حقيبة الوثائق الرقمية</a>.</li>
            <li><b>تبحث في كل الموقع؟</b> <a href="/baht/">البحث الداخلي</a>.</li>
          </ul>

          <div class="note">
            <b>ما لا نستطيع تقديمه:</b> لا نملك صفة رسمية، فلا يمكننا التدخل في ملف ترشيح،
            ولا تصحيح معطى في منصة رسمية، ولا التنبؤ بعتبة دورة قادمة، ولا إعطاء جواب ملزم
            عن وضعيتك الإدارية. لهذه الحالات <b>راسل المؤسسة أو الأكاديمية المعنية مباشرة</b>.
          </div>

          <h2>الروابط القانونية</h2>
          <ul>
            <li><a href="/privacy">سياسة الخصوصية</a></li>
            <li><a href="/shourout">شروط الخدمة</a></li>
            <li><a href="/delete-account">حذف الحساب والبيانات</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
