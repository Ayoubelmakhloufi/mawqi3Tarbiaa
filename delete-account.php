<?php
require __DIR__ . '/partials/lib.php';

$meta = [
    'title'       => 'طلب حذف الحساب والبيانات',
    'description' => 'كيفية طلب حذف حسابك في تطبيق تربية وكافة البيانات المرتبطة به نهائيًا من خوادمنا خلال 48 ساعة.',
    'canonical'   => '/delete-account',
    'breadcrumb'  => [['name' => 'حذف الحساب', 'url' => '/delete-account']],
];

require __DIR__ . '/partials/header.php';
?>

<section class="bg-white border-b border-slate-200">
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 text-center">
    <img src="/assets/img/logo.svg" alt="" width="72" height="72" class="w-16 h-16 mx-auto mb-6">
    <h1 class="text-3xl md:text-4xl font-black text-primary">طلب حذف الحساب والبيانات</h1>
    <p class="text-slate-500 mt-3 font-medium">نحن نحترم حقك الكامل في التحكم ببياناتك</p>
  </div>
</section>

<div class="container mx-auto px-4 md:px-6 py-12 md:py-16">
  <div class="max-w-2xl mx-auto">
    <div class="article-body">
      <p>
        بموجب سياساتنا، يمكنك طلب حذف حسابك وكافة البيانات المرتبطة به من خوادمنا نهائيًا.
        يرجى العلم أن هذه العملية <b>لا يمكن التراجع عنها</b>، وبمجرد التنفيذ سيتم حذف تقدمك الدراسي،
        نقاط الخبرة (XP)، ونتائج الاختبارات.
      </p>

      <div class="note">
        <p><b>سيتم حذف البيانات التالية:</b></p>
        <ul>
          <li>البريد الإلكتروني والملف الشخصي.</li>
          <li>سجل التقدم في شجرة المهارات.</li>
          <li>سجل نتائج الاختبارات والامتحانات.</li>
          <li>كافة نقاط الخبرة (XP) والأوسمة المحصّل عليها.</li>
        </ul>
      </div>

      <h2>طريقة تقديم الطلب</h2>
      <p>
        يرجى مراسلتنا من <b>البريد الإلكتروني المرتبط بحسابك</b> لضمان التحقق من هويتك، مع ذكر
        «طلب حذف الحساب» في موضوع الرسالة.
      </p>
    </div>

    <a href="mailto:<?= e(MAIL_MAIN) ?>?subject=<?= rawurlencode('طلب حذف الحساب') ?>"
       class="flex items-center justify-center gap-3 w-full bg-red-600 text-white text-center py-4 rounded-2xl font-black hover:bg-red-700 transition-all mt-8">
      <i class="fas fa-trash-can"></i>
      <span>إرسال طلب حذف الحساب</span>
    </a>

    <p class="text-center text-slate-400 text-sm mt-6">
      تتم معالجة طلبات الحذف يدويًا خلال 48 ساعة كحد أقصى.
      اطّلع أيضًا على <a href="/privacy" class="text-secondary font-bold underline decoration-accent/40 underline-offset-4">سياسة الخصوصية</a>.
    </p>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
