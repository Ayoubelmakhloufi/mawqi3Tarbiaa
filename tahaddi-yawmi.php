<?php
/**
 * التحدّي اليومي — عشرة أسئلة تتغيّر كل يوم.
 *
 * الأسئلة تُنتقى **حتميًا من تاريخ اليوم** لا عشوائيًا:
 * كل الزوار يرون التحدي نفسه في اليوم نفسه، ويتغيّر تلقائيًا عند منتصف الليل
 * بتوقيت المغرب. لا قاعدة بيانات ولا تخزين ولا أي صيانة.
 *
 * البذرة = رقم اليوم منذ 1970، فيمرّ البنك كله بالتناوب قبل أن يتكرر.
 */

require __DIR__ . '/partials/lib.php';

const TB_TAHADDI_COUNT = 10;

/* بنك موحّد من كل الاختبارات، بترتيب ثابت لا يتغيّر إلا بتغيّر الملف */
$pool = [];
foreach (tb_data('ikhtibarat')['quizzes'] ?? [] as $bank) {
    foreach ($bank['qs'] as $q) {
        $pool[] = $q;
    }
}
$poolSize = count($pool);

/* بذرة اليوم: عدد الأيام منذ 1970 بتوقيت المغرب (المضبوط في lib.php) */
$daySeed = (int) floor(strtotime(date('Y-m-d')) / 86400);

/**
 * مولّد أعداد شبه عشوائي حتمي (خطّي تطابقي).
 * ⚠️ لا يُستعمل mt_rand: بذرته عامة في العملية كلها وقد تتغيّر نتيجته
 * بين إصدارات PHP، فنُثبّت الحساب هنا ليعطي النتيجة نفسها دائمًا.
 */
$lcg = static function (int $state): int {
    return (int) ((($state * 1103515245) + 12345) & 0x7FFFFFFF);
};

/* اختيار بلا تكرار: خلط فيشر-ييتس بمولّد البذرة */
$order = range(0, $poolSize - 1);
$state = $lcg($daySeed + 7919);
for ($i = $poolSize - 1; $i > 0; $i--) {
    $state = $lcg($state);
    $j = $state % ($i + 1);
    [$order[$i], $order[$j]] = [$order[$j], $order[$i]];
}

$picked = [];
foreach (array_slice($order, 0, min(TB_TAHADDI_COUNT, $poolSize)) as $idx) {
    $picked[] = $pool[$idx];
}

/* التحدي يتغيّر عند منتصف الليل — نخبر الزائر بالوقت المتبقي */
$tomorrow = strtotime('tomorrow');
$leftMin  = max(0, (int) round(($tomorrow - time()) / 60));

$quiz = [
    'slug'  => 'tahaddi',
    'title' => 'التحدّي اليومي',
    'desc'  => 'عشرة أسئلة مختارة من كل محاور الموقع، تتغيّر كل يوم. نفس الأسئلة لكل الزوار اليوم.',
    'icon'  => 'fa-fire',
    'tint'  => 'rose',
    'qs'    => $picked,
];

$quizNote = '<b>تحدّي ' . e(tb_date_ar(date('Y-m-d'))) . ':</b> هذه الأسئلة العشرة '
    . 'مختارة من بنك الموقع كاملًا (' . $poolSize . ' سؤالًا) وهي نفسها لكل الزوار اليوم. '
    . 'يتجدّد التحدي بعد <b>' . ($leftMin >= 60 ? intdiv($leftMin, 60) . ' ساعة' : $leftMin . ' دقيقة') . '</b>.';

/* صفحة التحدي لها عنوانها ورابطها الخاص، لا رابط /ikhtibarat/tahaddi/ */
$quizCanonical = '/tahaddi-yawmi';
$quizMetaTitle = 'التحدّي اليومي — 10 أسئلة تتغيّر كل يوم';

require __DIR__ . '/partials/quiz.php';
