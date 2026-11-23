<?php
/**
 * اختبار مركَّب: يُسحب في كل زيارة اختيار عشوائي جديد من كل بنوك الموقع.
 *
 * لا يوجد هذا الاختبار في data/ikhtibarat.json، فتُبنى مصفوفته هنا
 * وتُمرَّر مباشرةً إلى partials/quiz.php عبر المتغير $quiz.
 *
 * تُسحب عيّنة محدودة لا البنك كله، لأن إدراج 350 سؤالًا في الصفحة
 * يرفع وزنها إلى ما يقارب 200 ك.ب — وهو ثقيل على اتصال الهاتف.
 */

require __DIR__ . '/../../partials/lib.php';

const TB_ACHWAI_SAMPLE = 80;

$pool = [];
foreach (tb_data('ikhtibarat')['quizzes'] ?? [] as $bankQuiz) {
    foreach ($bankQuiz['qs'] as $bankQuestion) {
        $pool[] = $bankQuestion;
    }
}

$poolSize = count($pool);
shuffle($pool);

$quiz = [
    'slug'  => 'achwai',
    'title' => 'عشوائي من كل المحاور',
    'desc'  => 'أسئلة مختلطة من محاور الموقع كلها: البيداغوجيا والديداكتيك والتقويم وعلم النفس والتدبير والتخطيط والوضعيات المهنية.',
    'icon'  => 'fa-shuffle',
    'tint'  => 'indigo',
    'qs'    => array_slice($pool, 0, TB_ACHWAI_SAMPLE),
];

$quizNote = '<b>سحب جديد في كل زيارة:</b> تُنتقى هذه الأسئلة عشوائيًا من بنك الموقع الكامل ('
    . $poolSize . ' سؤالًا) ثم تختار منها العدد الذي تريد. حدّث الصفحة لتحصل على سحب مختلف تمامًا.';

require __DIR__ . '/../../partials/quiz.php';
