<?php
/**
 * هذا الملف يقوم بتقديم ملف JSON الخاص بالمؤسسات الجامعية.
 * تم استخدام هذه الطريقة لأن مجلد /data/ والامتداد .json محجوبان
 * بالكامل من المتصفح في إعدادات .htaccess و _dev-router.php.
 */

$file = __DIR__ . '/../data/jamiat.json';

if (!file_exists($file)) {
    http_response_code(404);
    exit('[]');
}

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=86400');
readfile($file);
exit;
