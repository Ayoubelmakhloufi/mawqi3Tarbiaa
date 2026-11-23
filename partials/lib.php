<?php
/**
 * منصة تربية — الدوال المشتركة
 * يُستدعى هذا الملف في أعلى كل صفحة:  require __DIR__ . '/../partials/lib.php';
 */

declare(strict_types=1);

/**
 * ⚠️ لا يمكن حماية هذا الملف من التضمين المزدوج من داخله:
 * تعريفات الدوال في أعلى الملف تُربط عند **التصريف** لا عند التنفيذ،
 * فأي `return` هنا يأتي بعد أن تكون الدوال قد أُعلنت فيقع Cannot redeclare.
 *
 * الحماية تكون في **موضع الاستدعاء** بالنمط المتّبع في `partials/` و`404.php`:
 *     if (!defined('TB_ROOT')) { require __DIR__ . '/lib.php'; }
 */

/**
 * توقيت المغرب صراحةً.
 * ⚠️ ضروري: أغلب الخوادم تعمل بتوقيت UTC، وهو متأخر عن المغرب بساعة.
 * بدون هذا السطر يبقى المقال المنشور بتاريخ اليوم مخفيًا حتى يلحق UTC،
 * لأن tb_articles() تُخفي كل مقال تاريخه في المستقبل.
 */
date_default_timezone_set('Africa/Casablanca');

define('TB_ROOT', dirname(__DIR__));

const SITE_URL   = 'https://tarbiaa.ma';
const SITE_NAME  = 'منصة تربية';
const SITE_TAG   = 'رفيقك للنجاح في علوم التربية';
const PLAY_URL   = 'https://play.google.com/store/apps/details?id=com.ayoub.tarbiaa';
const APP_ID     = 'com.ayoub.tarbiaa';
const MAIL_MAIN  = 'support@tarbiaa.ma';
const MAIL_DEV   = 'tarbiaa.dev@gmail.com';
const FB_URL     = 'https://www.facebook.com/share/1Rd9Z15Gox/';
const IG_URL     = 'https://www.instagram.com/tarbiaa1?igsh=bTA4OXBzcmNsMnRs';
const WA_URL     = 'https://whatsapp.com/channel/0029VbAF81z1CYoTrj5dOI35';
const DEV_FB_URL = 'https://www.facebook.com/ayoub.elmakhloufi.77';

/**
 * رابط حاسبة معدل الانتقاء.
 * ⚠️ لا تغيّره: هذا الرابط سبق أن شورك مع المستخدمين قبل تجديد الموقع،
 * وقرار المشروع هو الحفاظ عليه كما هو وتحديث محتوى الصفحة فقط.
 */
const CALC_URL = '/حساب_معدل_الانتقاء';

/**
 * اسم ملف الحاسبة على القرص — لاتيني عمدًا.
 * أدوات فك ضغط ZIP في cPanel تُشوّه أسماء الملفات العربية فيختفي الملف بعد الرفع،
 * لذلك يُفصل الرابط العربي (CALC_URL) عن اسم الملف، ويربط بينهما .htaccess.
 */
const CALC_FILE = 'hisab-mouaddal-intiqa.php';

/** اختصار للهروب الآمن من HTML */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** تحميل ملف JSON من مجلد data مع تخزين مؤقت في الذاكرة */
function tb_data(string $name): array
{
    static $cache = [];
    if (isset($cache[$name])) {
        return $cache[$name];
    }
    $path = TB_ROOT . '/data/' . $name . '.json';
    if (!is_file($path)) {
        return $cache[$name] = [];
    }
    $decoded = json_decode((string) file_get_contents($path), true);

    return $cache[$name] = is_array($decoded) ? $decoded : [];
}

/** كل الأقسام بالترتيب المعرَّف في sections.json */
function tb_sections(): array
{
    return tb_data('sections');
}

/** قسم واحد حسب المعرّف، أو null */
function tb_section(?string $slug): ?array
{
    foreach (tb_sections() as $section) {
        if ($section['slug'] === $slug) {
            return $section;
        }
    }

    return null;
}

/** الأقسام المنتمية لمجموعة قائمة معينة (concours / maktaba / main) */
function tb_sections_in_group(string $group): array
{
    return array_values(array_filter(
        tb_sections(),
        static fn (array $s): bool => $s['group'] === $group
    ));
}

/**
 * كل المقالات المنشورة، مرتبة من الأحدث إلى الأقدم.
 * يتم تجاهل أي مقال عليه "draft": true أو تاريخه في المستقبل.
 */
function tb_articles(): array
{
    static $sorted = null;
    if ($sorted !== null) {
        return $sorted;
    }

    $today = date('Y-m-d');
    $items = array_filter(tb_data('articles'), static function (array $a) use ($today): bool {
        return empty($a['draft']) && !empty($a['slug']) && ($a['date'] ?? '9999') <= $today;
    });

    usort($items, static fn (array $a, array $b): int => strcmp($b['date'] ?? '', $a['date'] ?? ''));

    return $sorted = array_values($items);
}

/** مقالات قسم معيّن */
function tb_articles_in(string $section, ?int $limit = null): array
{
    $items = array_values(array_filter(
        tb_articles(),
        static fn (array $a): bool => ($a['section'] ?? '') === $section
    ));

    return $limit ? array_slice($items, 0, $limit) : $items;
}

/** أحدث المقالات عبر كل الأقسام */
function tb_latest(int $limit = 6): array
{
    return array_slice(tb_articles(), 0, $limit);
}

/** المقالات المميّزة (featured) */
function tb_featured(int $limit = 3): array
{
    $items = array_values(array_filter(tb_articles(), static fn (array $a): bool => !empty($a['featured'])));

    return array_slice($items ?: tb_articles(), 0, $limit);
}

/** مقال واحد حسب المعرّف، أو null */
function tb_article(string $slug): ?array
{
    foreach (tb_data('articles') as $article) {
        if (($article['slug'] ?? '') === $slug) {
            return $article;
        }
    }

    return null;
}

/** مقالات ذات صلة: نفس القسم، باستثناء المقال الحالي */
function tb_related(array $article, int $limit = 3): array
{
    $items = array_values(array_filter(
        tb_articles_in($article['section'] ?? ''),
        static fn (array $a): bool => $a['slug'] !== $article['slug']
    ));

    if (count($items) < $limit) {
        foreach (tb_latest($limit * 3) as $candidate) {
            $known = array_column($items, 'slug');
            if ($candidate['slug'] !== $article['slug'] && !in_array($candidate['slug'], $known, true)) {
                $items[] = $candidate;
            }
        }
    }

    return array_slice($items, 0, $limit);
}

/** رابط صفحة المقال */
function tb_article_url(array $article): string
{
    return '/maqalat/' . $article['slug'];
}

/** رابط صفحة القسم */
function tb_section_url(string $slug): string
{
    return '/' . $slug . '/';
}

/**
 * تحويل رابط نسبي إلى رابط مطلق.
 * تُرمَّز مقاطع المسار بترميز النسبة المئوية (مع الإبقاء على الشرطات المائلة)
 * حتى تكون روابط canonical و og:url وخريطة الموقع صالحة مع المسارات العربية.
 */
function tb_abs(string $path): string
{
    if (str_starts_with($path, 'http')) {
        return $path;
    }

    $path              = '/' . ltrim($path, '/');
    [$route, $query]   = array_pad(explode('?', $path, 2), 2, null);
    $encoded           = implode('/', array_map('rawurlencode', explode('/', $route)));

    return SITE_URL . $encoded . ($query !== null ? '?' . $query : '');
}

/** تنسيق التاريخ بالعربية: 2026-08-05 → 5 غشت 2026 */
function tb_date_ar(?string $iso): string
{
    if (!$iso) {
        return '';
    }
    $months = [
        1 => 'يناير', 'فبراير', 'مارس', 'أبريل', 'ماي', 'يونيو',
        'يوليوز', 'غشت', 'شتنبر', 'أكتوبر', 'نونبر', 'دجنبر',
    ];
    $time = strtotime($iso);
    if ($time === false) {
        return '';
    }

    return (int) date('j', $time) . ' ' . $months[(int) date('n', $time)] . ' ' . date('Y', $time);
}

/**
 * أصناف Tailwind لكل لون قسم.
 * مكتوبة كسلاسل كاملة عمدًا حتى يلتقطها ماسح Tailwind عند التصريف.
 */
function tb_tint(string $tint): array
{
    $map = [
        'emerald' => ['bg' => 'bg-emerald-50',  'text' => 'text-emerald-700',  'ring' => 'ring-emerald-200',  'dot' => 'bg-emerald-500'],
        'teal'    => ['bg' => 'bg-teal-50',     'text' => 'text-teal-700',     'ring' => 'ring-teal-200',     'dot' => 'bg-teal-500'],
        'lime'    => ['bg' => 'bg-lime-50',     'text' => 'text-lime-700',     'ring' => 'ring-lime-200',     'dot' => 'bg-lime-500'],
        'sky'     => ['bg' => 'bg-sky-50',      'text' => 'text-sky-700',      'ring' => 'ring-sky-200',      'dot' => 'bg-sky-500'],
        'amber'   => ['bg' => 'bg-amber-50',    'text' => 'text-amber-700',    'ring' => 'ring-amber-200',    'dot' => 'bg-amber-500'],
        'indigo'  => ['bg' => 'bg-indigo-50',   'text' => 'text-indigo-700',   'ring' => 'ring-indigo-200',   'dot' => 'bg-indigo-500'],
        'rose'    => ['bg' => 'bg-rose-50',     'text' => 'text-rose-700',     'ring' => 'ring-rose-200',     'dot' => 'bg-rose-500'],
        'orange'  => ['bg' => 'bg-orange-50',   'text' => 'text-orange-700',   'ring' => 'ring-orange-200',   'dot' => 'bg-orange-500'],
    ];

    return $map[$tint] ?? $map['emerald'];
}

/**
 * صياغة عدد المقالات وفق قواعد التمييز في العربية:
 * 0 ← لا مقالات · 1 ← مقال واحد · 2 ← مقالان · 3-10 ← مقالات · 11+ ← مقالًا
 */
function tb_count_ar(int $n): string
{
    return match (true) {
        $n === 0  => 'لا مقالات بعد',
        $n === 1  => 'مقال واحد',
        $n === 2  => 'مقالان',
        $n <= 10  => $n . ' مقالات',
        default   => $n . ' مقالًا',
    };
}

/**
 * صياغة عدد الوثائق وفق قواعد التمييز في العربية:
 * 1 ← وثيقة واحدة · 2 ← وثيقتان · 3-10 ← وثائق · 11+ ← وثيقة
 */
function tb_docs_ar(int $n): string
{
    return match (true) {
        $n === 0  => 'لا وثائق',
        $n === 1  => 'وثيقة واحدة',
        $n === 2  => 'وثيقتان',
        $n <= 10  => $n . ' وثائق',
        default   => $n . ' وثيقة',
    };
}

/**
 * صياغة عدد الأسئلة وفق قواعد التمييز في العربية.
 * 10 ← «10 أسئلة» لا «10 سؤالًا».
 */
function tb_q_ar(int $n): string
{
    return match (true) {
        $n === 0  => 'لا أسئلة',
        $n === 1  => 'سؤال واحد',
        $n === 2  => 'سؤالان',
        $n <= 10  => $n . ' أسئلة',
        default   => $n . ' سؤالًا',
    };
}

/**
 * صياغة عدد المصطلحات وفق قواعد التمييز في العربية.
 * 3 ← «3 مصطلحات» لا «3 مصطلحًا».
 */
function tb_terms_ar(int $n): string
{
    return match (true) {
        $n === 0  => 'لا مصطلحات',
        $n === 1  => 'مصطلح واحد',
        $n === 2  => 'مصطلحان',
        $n <= 10  => $n . ' مصطلحات',
        default   => $n . ' مصطلحًا',
    };
}

/**
 * قصّ أول $bytes بايت مع الوقوف عند حدّ حرف كامل.
 * ⚠️ بلا دوال mb_*: يُتراجَع عن بايتات الاستمرار في UTF-8 (النمط 10xxxxxx)
 * ثم عن بايت البداية الناقص (11xxxxxx) حتى لا يُقطع حرف عربي في منتصفه.
 */
function tb_utf8_head(string $s, int $bytes): string
{
    if (strlen($s) <= $bytes) {
        return $s;
    }
    $out = substr($s, 0, $bytes);
    while ($out !== '' && (ord($out[strlen($out) - 1]) & 0xC0) === 0x80) {
        $out = substr($out, 0, -1);
    }
    if ($out !== '' && (ord($out[strlen($out) - 1]) & 0xC0) === 0xC0) {
        $out = substr($out, 0, -1);
    }

    return $out;
}

/** قصّ آخر $bytes بايت مع البدء من حدّ حرف كامل */
function tb_utf8_tail(string $s, int $bytes): string
{
    if (strlen($s) <= $bytes) {
        return $s;
    }
    $out = substr($s, -$bytes);
    while ($out !== '' && (ord($out[0]) & 0xC0) === 0x80) {
        $out = substr($out, 1);
    }

    return $out;
}

/**
 * اسم ملف صالح للتحميل، مشتقّ من عنوان الوثيقة.
 *
 * $prefix رقم الوثيقة في اللائحة الرسمية. وهو **ضروري لا تجميلي**:
 * عشرات العناوين تتطابق في أولها وآخرها ولا تختلف إلا في وسطها
 * («…الخاصة بمادة الرياضيات بسلك…» مقابل «…الخاصة بمادة الفلسفة بسلك…»)،
 * فأي قصّ يُنتج أسماء متكررة. البادئة تضمن التفرّد وتحفظ ترتيب اللائحة.
 *
 * الحدّ 200 بايت: الحرف العربي بايتان وأنظمة الملفات تقف عند 255،
 * فيبقى هامش للبادئة وللاحقة التي يضيفها المتصفح عند تكرار الاسم.
 */
function tb_safe_filename(string $title, string $prefix = '', int $maxBytes = 200): string
{
    /* محارف ممنوعة في أسماء الملفات على ويندوز وماك ولينكس */
    $illegal = ['/', '\\', ':', '*', '?', '"', '<', '>', '|', "\r", "\n", "\t"];
    $name    = trim(preg_replace('/\s+/', ' ', str_replace($illegal, '-', $title)));

    if (strlen($name) > $maxBytes) {
        /* يُحذف الوسط لا الآخر: ذيل العنوان يحمل غالبًا الشعبة والخيار اللغوي */
        $keepTail = (int) ($maxBytes * 0.45);
        $keepHead = $maxBytes - $keepTail - 5;   // 5 = طول ' … ' بالبايت
        $name     = rtrim(tb_utf8_head($name, $keepHead))
                    . ' … '
                    . ltrim(tb_utf8_tail($name, $keepTail));
    }

    $prefix = trim(str_replace($illegal, '-', $prefix));

    return ($prefix !== '' ? $prefix . ' - ' : '')
           . ($name !== '' ? $name : 'wathiqa')
           . '.pdf';
}

/** صورة المشاركة الافتراضية للمقال أو القسم */
function tb_image(?string $image): string
{
    return tb_abs($image ?: '/assets/img/og-default.jpg');
}
