<?php
require __DIR__ . '/../partials/lib.php';

$meta = [
    'title'       => 'دليل المؤسسات الجامعية العمومية بالمغرب',
    'description' => 'ابحث عن أي مؤسسة جامعية أو كلية عمومية في المغرب. استعرض الكليات والمعاهد العليا وتعرف على عناوينها، وأرقام التواصل معها، وعدد طلابها.',
    'canonical'   => '/jamiat/',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'دليل الجامعات', 'url' => '/jamiat/'],
    ],
];

require __DIR__ . '/../partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden pb-10">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-purple-300 transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/adawat/" class="hover:text-purple-300 transition-colors">الأدوات والحاسبات</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">موسوعة الجامعات</li>
      </ol>
    </nav>
    <div class="max-w-4xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">دليل المؤسسات الجامعية العمومية</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-10">
        محرك بحث شامل يضم الكليات، المدارس العليا، والمعاهد التابعة للجامعات العمومية بالمملكة المغربية.
      </p>

      <!-- الإحصائيات -->
      <div x-data="{
         stats: { u: 0, e: 165, v: 0, s: 0 },
         initStats() {
           fetch('/jamiat/data.php').then(r => r.json()).then(d => {
             this.stats.e = d.length;
             this.stats.u = new Set(d.map(i => i.u)).size;
             this.stats.v = new Set(d.map(i => i.v)).size;
             this.stats.s = d.reduce((a, c) => a + (parseInt(c.eff) || 0), 0);
           });
         }
      }" x-init="initStats()" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-white mb-1" x-text="stats.e || 165"></div>
          <div class="text-sm font-bold text-slate-300">مؤسسة جامعية</div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-purple-300 mb-1" x-text="stats.u || 12"></div>
          <div class="text-sm font-bold text-slate-300">جامعة</div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-fuchsia-300 mb-1" x-text="stats.v || 40"></div>
          <div class="text-sm font-bold text-slate-300">مدينة</div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10" title="عدد الطلبة في تزايد مستمر">
          <div class="text-2xl md:text-3xl font-black text-emerald-300 mb-1" x-text="(stats.s || 1317134).toLocaleString('en-US') + '+'"></div>
          <div class="text-sm font-bold text-slate-300">طالب وطالبة</div>
        </div>
      </div>
    </div>
  </div>
</section>

<div x-data="jamiat()" class="bg-slate-50 min-h-screen">
  
  <!-- البحث والتصفية -->
  <div class="sticky top-[72px] z-30 bg-slate-50/95 backdrop-blur border-b border-slate-200 shadow-sm">
    <div class="container mx-auto px-4 md:px-6 py-5">
      <div class="max-w-4xl mx-auto space-y-4">
        
        <!-- حقل البحث النصي -->
        <div class="relative">
          <input type="search" x-model="q"
                 placeholder="ابحث باسم الكلية، الاختصار (FST)، المدينة، أو الجامعة…"
                 class="w-full rounded-2xl border-2 border-slate-200 bg-white px-5 py-4 pr-12 font-medium text-slate-800 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition">
          <i class="fas fa-magnifying-glass absolute top-1/2 -translate-y-1/2 right-5 text-slate-400"></i>
        </div>

        <!-- فلاتر التصفية -->
        <div class="flex flex-col md:flex-row gap-3">
          
          <select x-model="univ" class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-purple-500 transition-colors">
            <option value="">كل الجامعات</option>
            <template x-for="u in univs" :key="u">
              <option :value="u" x-text="u"></option>
            </template>
          </select>

          <select x-model="ville" class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-purple-500 transition-colors">
            <option value="">كل المدن</option>
            <template x-for="v in villes" :key="v">
              <option :value="v" x-text="v"></option>
            </template>
          </select>

        </div>
        
        <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
          <p class="text-sm font-bold text-slate-600">
            <template x-if="loading">
              <span class="text-purple-600"><i class="fas fa-spinner fa-spin ml-1"></i> جاري تحميل البيانات...</span>
            </template>
            <template x-if="!loading">
              <span>تم العثور على <span x-text="filtered.length" class="text-purple-600 font-black mx-1"></span> مؤسسة جامعية</span>
            </template>
          </p>
          <button x-show="q !== '' || univ !== '' || ville !== ''" @click="reset()" type="button" class="text-sm font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-2 px-3 py-1.5 bg-rose-50 rounded-lg transition-colors" x-cloak>
            <i class="fas fa-times"></i>
            إعادة تعيين
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- النتائج -->
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <div class="max-w-5xl mx-auto">
      
      <div x-show="!loading && filtered.length === 0" x-cloak class="text-center py-16 bg-white rounded-3xl ring-1 ring-slate-200">
        <i class="fas fa-university text-5xl text-slate-200 mb-4 block"></i>
        <h3 class="text-xl font-black text-slate-600 mb-2">لا توجد نتائج!</h3>
        <p class="text-slate-500 font-medium mb-6">لم نعثر على أية مؤسسة تطابق بحثك.</p>
        <button type="button" @click="reset()" class="bg-purple-600 text-white font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition-opacity">إلغاء التصفية</button>
      </div>

      <div class="grid gap-5 md:grid-cols-2">
        <template x-for="item in filtered" :key="item.el + item.v">
          <article class="bg-white rounded-2xl ring-1 ring-slate-200/80 p-6 hover:ring-purple-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
            <div class="flex justify-between items-start mb-2 gap-3">
              <h2 class="text-lg font-black text-slate-900 leading-tight" x-text="item.e"></h2>
              <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-lg text-xs font-black ring-1 bg-purple-50 text-purple-700 ring-purple-200" x-text="item.abr" x-show="item.abr !== ''"></span>
            </div>
            
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-5" x-text="item.el"></p>
            
            <div class="space-y-3 text-sm flex-1">
              <div class="flex items-start gap-3 text-slate-600">
                <i class="fas fa-university mt-1 text-purple-400 w-4 text-center shrink-0"></i>
                <span class="font-bold text-purple-900" x-text="item.u"></span>
              </div>
              
              <div class="flex items-start gap-3 text-slate-600" x-show="item.a !== ''">
                <i class="fas fa-location-dot mt-1 text-slate-400 w-4 text-center shrink-0"></i>
                <a :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(item.e + ' ' + item.v + ' ' + item.a)" target="_blank" rel="noopener noreferrer" class="font-medium hover:text-purple-600 hover:underline transition-colors block" title="عرض على خرائط جوجل">
                  <span x-text="item.v"></span> <span class="text-slate-300 mx-1">•</span>
                  <span x-text="item.a"></span>
                </a>
              </div>
              
              <div class="flex items-center gap-3 text-slate-600" x-show="item.t !== ''">
                <i class="fas fa-phone text-slate-400 w-4 text-center shrink-0"></i>
                <span class="font-medium" style="direction: ltr;" x-text="item.t"></span>
              </div>
            </div>
            
            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
              <span class="flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg" title="هذا الرقم في تزايد مستمر خلال المواسم الحالية (2025-2026)">
                <i class="fas fa-users"></i>
                <span x-text="Number(item.eff).toLocaleString('en-US')"></span> طالب(ة)
              </span>
              <span class="text-[10px] opacity-60">* إحصائيات 23/24 - الرقم في تزايد (25/26)</span>
            </div>
          </article>
        </template>
      </div>

    </div>
  </div>
</div>

<script>
function jamiat() {
  return {
    all: [],
    filtered: [],
    univs: [],
    villes: [],
    q: '',
    univ: '',
    ville: '',
    loading: true,

    reset() {
      this.q = '';
      this.univ = '';
      this.ville = '';
    },

    filterData() {
      var query = this.q.trim().toLowerCase();
      var u = this.univ;
      var v = this.ville;
      
      this.filtered = this.all.filter(function(item) {
        if (u && item.u !== u) return false;
        if (v && item.v !== v) return false;
        if (query) {
          if (item.e.toLowerCase().indexOf(query) === -1 && 
              item.el.toLowerCase().indexOf(query) === -1 &&
              item.u.toLowerCase().indexOf(query) === -1 &&
              item.ul.toLowerCase().indexOf(query) === -1 &&
              item.v.toLowerCase().indexOf(query) === -1 &&
              item.abr.toLowerCase().indexOf(query) === -1) {
            return false;
          }
        }
        return true;
      });
    },

    init() {
      var self = this;
      fetch('/jamiat/data.php')
        .then(function(res) { return res.json(); })
        .then(function(data) {
          self.all = data;
          
          var uSet = {};
          var vSet = {};
          for (var i = 0; i < data.length; i++) {
            if (data[i].u) { uSet[data[i].u] = true; }
            if (data[i].v) { vSet[data[i].v] = true; }
          }
          self.univs = Object.keys(uSet).sort();
          self.villes = Object.keys(vSet).sort();
          
          self.filtered = data;
          self.loading = false;
        })
        .catch(function(err) {
          console.error('Error fetching data', err);
          self.loading = false;
        });

      this.$watch('q', function() { self.filterData(); });
      this.$watch('univ', function() { self.filterData(); });
      this.$watch('ville', function() { self.filterData(); });
    }
  };
}
</script>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
