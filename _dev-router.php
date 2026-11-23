<?php
/**
 * موجّه المعاينة المحلية فقط — يحاكي قواعد .htaccess في خادم PHP المدمج.
 *
 *   php -S localhost:8000 _dev-router.php
 *
 * ⚠️ لا تَرفع هذا الملف إلى الخادم؛ Apache يتكفّل بكل هذا عبر .htaccess.
 * (وقاعدة في .htaccess تمنع الوصول إليه احتياطًا لو رُفع سهوًا.)
 */

$root = __DIR__;
$uri  = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$path = $root . $uri;

/** يقدّم ملف PHP كأنه هو الصفحة المطلوبة */
$serve = function (string $file) use ($root): bool {
    chdir(dirname($file));
    require $file;

    return true;
};

// حجب ملفات النظام كما يفعل .htaccess — يجب أن يسبق تقديم الأصول
if (preg_match('#^/(partials|data|_backup)(/|$)#', $uri) || $uri === '/_dev-router.php') {
    http_response_code(403);
    exit('403 — ممنوع الوصول');
}

// الأصول الحقيقية (صور، CSS، JS) يقدّمها الخادم مباشرة
if ($uri !== '/' && is_file($path) && !str_ends_with($path, '.php')) {
    return false;
}

// المسار اللاتيني التجريبي للحاسبة -> الرابط المعتمد
if (rtrim($uri, '/') === '/adawat/hisab-mouaddal-intiqa') {
    header('Location: /' . rawurlencode('حساب_معدل_الانتقاء'), true, 301);
    exit;
}

// خريطة الموقع
if ($uri === '/sitemap.xml') {
    return $serve($root . '/sitemap.php');
}
if ($uri === '/rss.xml') {
    return $serve($root . '/rss.php');
}

// روابط Google Play القديمة تبقى تعمل (إعادة كتابة داخلية 200)
if ($uri === '/privacy.html')        { return $serve($root . '/privacy.php'); }
if ($uri === '/delete-account.html') { return $serve($root . '/delete-account.php'); }

// الحاسبة: الرابط عربي والملف لاتيني (انظر التعليق في .htaccess)
if (rtrim($uri, '/') === '/حساب_معدل_الانتقاء') {
    return $serve($root . '/hisab-mouaddal-intiqa.php');
}
if ($uri === '/حساب_معدل_الانتقاء.html' || rtrim($uri, '/') === '/hisab-mouaddal-intiqa') {
    header('Location: /' . rawurlencode('حساب_معدل_الانتقاء'), true, 301);
    exit;
}

// صفحة مجال: /moujam/majal/<المعرّف> — يجب أن تسبق قاعدة المصطلح
if (preg_match('~^/moujam/majal/([a-z0-9-]+)/?$~', $uri, $m)) {
    $_GET['c'] = $m[1];

    return $serve($root . '/moujam/majal.php');
}

// صفحة مصطلح مستقلة: /moujam/<المعرّف> -> moujam/term.php?s=<المعرّف>
if (preg_match('~^/moujam/([a-z0-9-]+)/?$~', $uri, $m) && !is_file($path)) {
    $_GET['s'] = $m[1];

    return $serve($root . '/moujam/term.php');
}

// الصفحة الرئيسية
if ($uri === '/' && is_file($root . '/index.php')) {
    return $serve($root . '/index.php');
}

// مجلد فيه index.php
if (is_dir($path) && is_file(rtrim($path, '/') . '/index.php')) {
    return $serve(rtrim($path, '/') . '/index.php');
}

// رابط نظيف -> ملف .php  (مثال: /privacy و /حساب_معدل_الانتقاء)
if (is_file($path . '.php')) {
    return $serve($path . '.php');
}

// ملف .php مطلوب صراحةً -> تحويل إلى الرابط النظيف
if (str_ends_with($uri, '.php') && is_file($path)) {
    header('Location: ' . implode('/', array_map('rawurlencode', explode('/', substr($uri, 0, -4)))), true, 301);
    exit;
}

// أي شيء آخر: صفحة 404
http_response_code(404);
return $serve($root . '/404.php');
