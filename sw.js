/**
 * Service Worker — منصة تربية
 *
 * الاستراتيجية مقصودة ومحافِظة:
 *   • صفحات HTML  → الشبكة أولًا، والذاكرة احتياط عند انقطاع الاتصال.
 *     (هكذا يرى الزائر دائمًا آخر محتوى، ولا يعلق في نسخة قديمة)
 *   • الأصول الثابتة (CSS/JS/صور/خطوط) → الذاكرة أولًا لأنها نادرة التغير.
 *   • لا يُخزَّن إطلاقًا: /data/ و sitemap.xml و rss.xml وأي طلب غير GET.
 *
 * ⚠️ عند تحديث الموقع: غيّر رقم VERSION أدناه ليُمسح المخزون القديم تلقائيًا.
 */

const VERSION = 'tarbiaa-v2';
const STATIC_CACHE = VERSION + '-static';
const PAGES_CACHE = VERSION + '-pages';
const OFFLINE_URL = '/offline.html';

/* أصول تُخزَّن عند التثبيت — قليلة عمدًا حتى لا يفشل التثبيت */
const PRECACHE = [
  OFFLINE_URL,
  '/assets/css/tarbiaa.css',
  '/logo.svg',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then((cache) => cache.addAll(PRECACHE))
      .then(() => self.skipWaiting())
      /* فشل تخزين أصل واحد يجب ألا يمنع تفعيل العامل */
      .catch(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(
        keys.filter((k) => !k.startsWith(VERSION)).map((k) => caches.delete(k))
      ))
      .then(() => self.clients.claim())
  );
});

/** هل يُسمح بتخزين هذا الطلب؟ */
function cacheable(url) {
  if (url.origin !== self.location.origin) { return false; }
  if (url.pathname.startsWith('/data/')) { return false; }
  if (url.pathname === '/sitemap.xml' || url.pathname === '/rss.xml') { return false; }

  return true;
}

function isStaticAsset(url) {
  return /\.(css|js|svg|png|jpe?g|webp|woff2?|ico)$/i.test(url.pathname);
}

self.addEventListener('fetch', (event) => {
  const req = event.request;

  if (req.method !== 'GET') { return; }

  const url = new URL(req.url);
  if (!cacheable(url)) { return; }

  /* الأصول الثابتة: الذاكرة أولًا */
  if (isStaticAsset(url)) {
    event.respondWith(
      caches.match(req).then((hit) => hit || fetch(req).then((res) => {
        if (res.ok) {
          const copy = res.clone();
          caches.open(STATIC_CACHE).then((c) => c.put(req, copy));
        }

        return res;
      }))
    );

    return;
  }

  /* صفحات HTML: الشبكة أولًا حتى يبقى المحتوى محدَّثًا */
  if (req.mode === 'navigate' || (req.headers.get('accept') || '').includes('text/html')) {
    event.respondWith(
      fetch(req)
        .then((res) => {
          if (res.ok) {
            const copy = res.clone();
            caches.open(PAGES_CACHE).then((c) => c.put(req, copy));
          }

          return res;
        })
        .catch(() => caches.match(req).then((hit) => hit || caches.match(OFFLINE_URL)))
    );
  }
});
