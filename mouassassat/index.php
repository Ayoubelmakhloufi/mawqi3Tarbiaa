<?php
require __DIR__ . '/../partials/lib.php';

$meta = [
    'title'       => 'موسوعة المؤسسات التعليمية العمومية بالمغرب',
    'description' => 'دليل شامل وقابل للبحث لجميع المدارس الابتدائية والإعدادية والثانويات التأهيلية العمومية عبر جهات ومدن وأقاليم المملكة.',
    'canonical'   => '/mouassassat/',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'دليل المؤسسات', 'url' => '/mouassassat/'],
    ],
];

require __DIR__ . '/../partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden pb-10">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-6">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-accent transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/adawat/" class="hover:text-accent transition-colors">الأدوات والحاسبات</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">موسوعة المؤسسات</li>
      </ol>
    </nav>
    <div class="max-w-4xl text-white">
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">موسوعة المؤسسات التعليمية العمومية بالمغرب</h1>
      <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-10">
        دليل شامل وقابل للبحث لجميع المدارس الابتدائية والإعدادية والثانويات التأهيلية العمومية عبر جهات ومدن وأقاليم المملكة.
      </p>

      <!-- الإحصائيات -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-white mb-1">11,398</div>
          <div class="text-sm font-bold text-slate-300">مؤسسة تعليمية</div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-sky-300 mb-1">7,825</div>
          <div class="text-sm font-bold text-slate-300">مدرسة ابتدائية</div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-emerald-300 mb-1">2,159</div>
          <div class="text-sm font-bold text-slate-300">ثانوية إعدادية</div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-amber-300 mb-1">1,414</div>
          <div class="text-sm font-bold text-slate-300">ثانوية تأهيلية</div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
          <div class="text-2xl md:text-3xl font-black text-white mb-1">12</div>
          <div class="text-sm font-bold text-slate-300">جهة</div>
        </div>
      </div>
    </div>
  </div>
</section>

<div x-data="mouassassat()" class="bg-slate-50 min-h-screen">
  
  <!-- البحث والتصفية -->
  <div class="sticky top-[72px] z-30 bg-slate-50/95 backdrop-blur border-b border-slate-200 shadow-sm">
    <div class="container mx-auto px-4 md:px-6 py-5">
      <div class="max-w-5xl mx-auto space-y-4">
        
        <!-- حقل البحث النصي -->
        <div class="relative">
          <input type="search" x-model="q"
                 placeholder="ابحث باسم المؤسسة، الجماعة، الإقليم أو العنوان…"
                 class="w-full rounded-2xl border-2 border-slate-200 bg-white px-5 py-4 pr-12 font-medium text-slate-800 focus:border-accent focus:ring-4 focus:ring-accent/10 outline-none transition">
          <i class="fas fa-magnifying-glass absolute top-1/2 -translate-y-1/2 right-5 text-slate-400"></i>
        </div>

        <!-- فلاتر التصفية -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          
          <select x-model="region" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-accent transition-colors">
            <option value="">الجهة: كل الجهات</option>
            <template x-for="r in regions" :key="r">
              <option :value="r" x-text="r"></option>
            </template>
          </select>

          <select x-model="province" :disabled="!region" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-accent transition-colors disabled:opacity-50">
            <option value="">الإقليم / العمالة: كل الأقاليم</option>
            <template x-for="p in availableProvinces" :key="p">
              <option :value="p" x-text="p"></option>
            </template>
          </select>

          <select x-model="commune" :disabled="!province" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-accent transition-colors disabled:opacity-50">
            <option value="">الجماعة: كل الجماعات</option>
            <template x-for="c in availableCommunes" :key="c">
              <option :value="c" x-text="c"></option>
            </template>
          </select>

          <select x-model="type" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-accent transition-colors">
            <option value="">السلك: كل الأسلاك</option>
            <option value="ابتدائي">التعليم الابتدائي</option>
            <option value="إعدادي">التعليم الإعدادي</option>
            <option value="تأهيلي">التعليم التأهيلي</option>
          </select>

        </div>
        
        <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
          <p class="text-sm font-bold text-slate-600">
            <template x-if="loading">
              <span class="text-primary"><i class="fas fa-spinner fa-spin ml-1"></i> جاري تحميل البيانات...</span>
            </template>
            <template x-if="!loading">
              <span>
                تم العثور على <span x-text="filteredCount.toLocaleString('en-US')" class="text-primary font-black mx-1"></span> مؤسسة
                <template x-if="filteredCount > limit">
                  <span class="opacity-70 mx-2 text-xs font-medium">(يتم عرض <span x-text="limit"></span> الأولى فقط)</span>
                </template>
              </span>
            </template>
          </p>
          <button x-show="q !== '' || type !== '' || region !== '' || province !== '' || commune !== ''" @click="reset()" type="button" class="text-sm font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-2 px-3 py-1.5 bg-rose-50 rounded-lg transition-colors" x-cloak>
            <i class="fas fa-times"></i>
            إعادة تعيين البحث والفلاتر
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- النتائج -->
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14">
    <div class="max-w-5xl mx-auto">
      
      <div x-show="!loading && filteredCount === 0" x-cloak class="text-center py-16 bg-white rounded-3xl ring-1 ring-slate-200">
        <i class="fas fa-search text-5xl text-slate-200 mb-4 block"></i>
        <h3 class="text-xl font-black text-slate-600 mb-2">لا توجد نتائج!</h3>
        <p class="text-slate-500 font-medium mb-6">لم نعثر على أية مؤسسة تطابق معايير البحث الحالية.</p>
        <button type="button" @click="reset()" class="bg-primary text-white font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition-opacity">إلغاء التصفية</button>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <template x-for="item in visible" :key="item.n + item.c">
          <article class="bg-white rounded-2xl ring-1 ring-slate-200/80 p-5 hover:ring-primary/30 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
            <div class="flex justify-between items-start mb-3 gap-3">
              <h2 class="text-lg font-black text-slate-900 leading-tight" x-text="item.n"></h2>
              <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-lg text-[11px] font-black ring-1"
                    :class="{
                      'bg-sky-50 text-sky-700 ring-sky-200': item.t === 'ابتدائي',
                      'bg-emerald-50 text-emerald-700 ring-emerald-200': item.t === 'إعدادي',
                      'bg-amber-50 text-amber-700 ring-amber-200': item.t === 'تأهيلي'
                    }">
                <span x-text="item.t"></span>
              </span>
            </div>
            
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4" x-text="item.nl"></p>
            
            <div class="mt-auto space-y-2 text-sm">
              <div class="flex items-start gap-2.5 text-slate-600">
                <i class="fas fa-location-dot mt-1 text-slate-400 w-4 text-center shrink-0"></i>
                <span class="font-medium" x-text="item.a || 'العنوان غير متوفر'"></span>
              </div>
              <div class="flex items-start gap-2.5 text-slate-600">
                <i class="fas fa-map mt-1 text-slate-400 w-4 text-center shrink-0"></i>
                <span class="font-medium">
                  <span x-text="item.c"></span> <span class="text-slate-300 mx-1">•</span>
                  <span x-text="item.p"></span> <span class="text-slate-300 mx-1">•</span>
                  <span x-text="item.r"></span>
                </span>
              </div>
            </div>
          </article>
        </template>
      </div>
      
      <div x-show="filteredCount > limit" x-cloak class="mt-10 text-center">
        <button @click="limit += 50" type="button" class="px-8 py-3 rounded-xl bg-white text-primary font-bold ring-2 ring-primary/20 hover:ring-primary hover:bg-primary hover:text-white transition-all">
          عرض المزيد من النتائج
        </button>
      </div>

    </div>
  </div>
</div>

<script>
window._mouassassat_all = [];
window._mouassassat_filtered = [];

function mouassassat() {
  return {
    regions: [],
    q: '',
    type: '',
    region: '',
    province: '',
    commune: '',
    loading: true,
    limit: 50,
    filteredCount: 0,
    visible: [],

    get availableProvinces() {
      if (!this.region) return [];
      var s = {};
      for (var i = 0; i < window._mouassassat_all.length; i++) {
        if (window._mouassassat_all[i].r === this.region) {
          s[window._mouassassat_all[i].p] = true;
        }
      }
      return Object.keys(s).sort();
    },

    get availableCommunes() {
      if (!this.province) return [];
      var s = {};
      for (var i = 0; i < window._mouassassat_all.length; i++) {
        if (window._mouassassat_all[i].p === this.province) {
          s[window._mouassassat_all[i].c] = true;
        }
      }
      return Object.keys(s).sort();
    },

    reset() {
      this.q = '';
      this.type = '';
      this.region = '';
      this.province = '';
      this.commune = '';
    },

    filterData() {
      var query = this.q.trim().toLowerCase();
      var t = this.type;
      var r = this.region;
      var p = this.province;
      var c = this.commune;
      
      window._mouassassat_filtered = window._mouassassat_all.filter(function(item) {
        if (t && item.t !== t) return false;
        if (r && item.r !== r) return false;
        if (p && item.p !== p) return false;
        if (c && item.c !== c) return false;
        if (query) {
          if (item.n.toLowerCase().indexOf(query) === -1 && 
              item.nl.toLowerCase().indexOf(query) === -1 &&
              item.c.toLowerCase().indexOf(query) === -1 &&
              item.p.toLowerCase().indexOf(query) === -1 &&
              item.a.toLowerCase().indexOf(query) === -1 &&
              item.al.toLowerCase().indexOf(query) === -1) {
            return false;
          }
        }
        return true;
      });
      this.limit = 50; 
      this.filteredCount = window._mouassassat_filtered.length;
      this.updateVisible();
    },

    updateVisible() {
      this.visible = window._mouassassat_filtered.slice(0, this.limit);
    },

    init() {
      var self = this;
      fetch('/mouassassat/data.php')
        .then(function(res) { return res.json(); })
        .then(function(data) {
          window._mouassassat_all = data;
          window._mouassassat_filtered = data;
          
          var rSet = {};
          for (var i = 0; i < data.length; i++) {
            if (data[i].r) { rSet[data[i].r] = true; }
          }
          self.regions = Object.keys(rSet).sort();
          
          self.filteredCount = data.length;
          self.updateVisible();
          self.loading = false;
        })
        .catch(function(err) {
          console.error('Error fetching data', err);
          self.loading = false;
        });

      this.$watch('limit', function() { self.updateVisible(); });

      this.$watch('q', function() { self.filterData(); });
      this.$watch('type', function() { self.filterData(); });
      
      this.$watch('region', function() { 
        self.province = ''; 
        self.commune = ''; 
        self.filterData(); 
      });
      
      this.$watch('province', function() { 
        self.commune = ''; 
        self.filterData(); 
      });
      
      this.$watch('commune', function() { 
        self.filterData(); 
      });
    }
  };
}
</script>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
