/**
 * منصة تربية — سكربت الموقع.
 * القائمة والقوائم المنسدلة تديرها Alpine.js في الهيدر؛ هذا الملف للتحسينات فقط.
 */
(function () {
  'use strict';

  /* ــــ فهرس محاور المقال: يُبنى تلقائيًا من عناوين h2 ــــ */
  function buildToc() {
    var toc = document.getElementById('toc');
    if (!toc) return;

    var body = document.querySelector('.article-body');
    if (!body) return;

    var headings = body.querySelectorAll('h2');
    if (headings.length < 3) return; // فهرس لمقالين محورين لا معنى له

    var list = toc.querySelector('ul');
    var used = Object.create(null);

    headings.forEach(function (h, i) {
      var id = h.id;
      if (!id) {
        id =
          (h.textContent || '')
            .trim()
            .replace(/[^\p{L}\p{N}\s-]/gu, '')
            .replace(/\s+/g, '-')
            .slice(0, 60) || 'mihwar';
        // المعرّف الذي يبدأ برقم صالح في HTML لكنه غير صالح كمحدِّد CSS (#1-...)
        if (/^[0-9-]/.test(id)) id = 'mihwar-' + id;
        if (used[id]) id += '-' + i;
        used[id] = true;
        h.id = id;
      }

      var li = document.createElement('li');
      var a = document.createElement('a');
      a.href = '#' + id;
      a.textContent = h.textContent;
      a.className = 'hover:text-primary transition-colors';
      li.appendChild(a);
      list.appendChild(li);
    });

    toc.hidden = false;
  }

  /* ــــ زر نسخ رابط المقال ــــ */
  function initCopy() {
    document.querySelectorAll('[data-copy]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var url = btn.getAttribute('data-copy');
        var label = btn.querySelector('span');
        var done = function () {
          if (!label) return;
          var original = label.textContent;
          label.textContent = 'تم النسخ ✓';
          setTimeout(function () {
            label.textContent = original;
          }, 2000);
        };

        if (navigator.clipboard && window.isSecureContext) {
          navigator.clipboard.writeText(url).then(done);
        } else {
          var tmp = document.createElement('textarea');
          tmp.value = url;
          tmp.setAttribute('readonly', '');
          tmp.style.position = 'fixed';
          tmp.style.opacity = '0';
          document.body.appendChild(tmp);
          tmp.select();
          try {
            document.execCommand('copy');
            done();
          } catch (e) {
            /* المتصفح لا يدعم النسخ — نتجاهل بصمت */
          }
          document.body.removeChild(tmp);
        }
      });
    });
  }

  /* ــــ البحث الفوري داخل الموقع ــــ */
  function initSearch() {
    var input = document.getElementById('search-input');
    var results = document.getElementById('search-results');
    if (!input || !results) return;

    var cards = Array.prototype.slice.call(results.querySelectorAll('[data-haystack]'));
    var counter = document.getElementById('search-count');
    var empty = document.getElementById('search-empty');

    function normalize(text) {
      return text
        .toLowerCase()
        .replace(/[ً-ْـ]/g, '') // إزالة التشكيل والتطويل
        .replace(/[أإآ]/g, 'ا')
        .replace(/ى/g, 'ي')
        .replace(/ة/g, 'ه')
        .replace(/\s+/g, ' ')
        .trim();
    }

    cards.forEach(function (card) {
      card.dataset.norm = normalize(card.getAttribute('data-haystack') || '');
    });

    /** صياغة العدد وفق قواعد التمييز في العربية */
    function resultsLabel(n) {
      if (n === 0) return 'لا نتائج مطابقة';
      if (n === 1) return 'نتيجة واحدة مطابقة';
      if (n === 2) return 'نتيجتان مطابقتان';
      if (n <= 10) return n + ' نتائج مطابقة';
      return n + ' نتيجة مطابقة';
    }

    function articlesLabel(n) {
      if (n === 1) return 'مقال واحد في الموقع';
      if (n === 2) return 'مقالان في الموقع';
      if (n <= 10) return n + ' مقالات في الموقع';
      return n + ' مقالًا في الموقع';
    }

    function run() {
      var terms = normalize(input.value).split(' ').filter(Boolean);
      var shown = 0;

      cards.forEach(function (card) {
        var hay = card.dataset.norm;
        var match = terms.every(function (t) {
          return hay.indexOf(t) !== -1;
        });
        card.hidden = !match;
        if (match) shown++;
      });

      if (counter) {
        counter.textContent = terms.length ? resultsLabel(shown) : articlesLabel(cards.length);
      }
      if (empty) empty.hidden = shown !== 0;
    }

    input.addEventListener('input', run);

    // ملء الحقل من ?q= في الرابط (يستعمله SearchAction في البيانات المنظمة)
    var q = new URLSearchParams(window.location.search).get('q');
    if (q) {
      input.value = q;
    }
    run();
    input.focus({ preventScroll: true });
  }

  function init() {
    buildToc();
    initCopy();
    initSearch();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
