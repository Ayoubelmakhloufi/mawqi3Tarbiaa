<?php
$pageTitle = "تخصصات ووحدات الإجازة في التربية (CLE) | موقع تربية";
$pageDesc = "اكتشف تخصصات الإجازة في التربية (CLE) وتعرف على فصول ووحدات كل تخصص بشكل مفصل ودقيق.";
require __DIR__ . '/../partials/header.php';
?>

<div class="bg-slate-50 min-h-screen pb-20" x-data="cleData()" x-init="init()">
  
  <!-- الهيدر -->
  <div class="bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-900 pt-32 pb-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('/assets/images/pattern.svg')] opacity-10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3"></div>
    
    <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
      <span class="inline-block py-1.5 px-4 rounded-full bg-white/10 text-purple-200 text-sm font-bold backdrop-blur-sm border border-white/20 mb-6">
        <i class="fas fa-graduation-cap ml-2"></i> دليل الإجازة في التربية
      </span>
      <h1 class="text-3xl md:text-5xl font-black text-white mb-6 leading-tight">تخصصات ووحدات <span class="text-transparent bg-clip-text bg-gradient-to-l from-purple-300 to-fuchsia-300">CLE</span></h1>
      <p class="text-lg text-indigo-100 max-w-2xl mx-auto font-medium leading-relaxed mb-10">
        تصفح جميع التخصصات المتاحة في سلك الإجازة في التربية، واطلع على محاور ووحدات كل فصل دراسي.
      </p>

      <!-- اختيار التخصص -->
      <div class="max-w-2xl mx-auto">
        <div class="relative group">
          <div class="absolute -inset-1 bg-gradient-to-r from-purple-500 to-fuchsia-500 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
          <div class="relative bg-white rounded-2xl p-2 flex items-center ring-1 ring-slate-900/5 shadow-xl">
            <div class="w-12 flex justify-center items-center text-purple-500 shrink-0">
              <i class="fas fa-list-ul text-xl"></i>
            </div>
            <select x-model="selectedTitle" class="w-full bg-transparent border-none outline-none py-3 pr-2 pl-6 font-bold text-slate-800 text-lg cursor-pointer appearance-none">
              <option value="" disabled>-- اختر التخصص لعرض وحداته --</option>
              <template x-for="item in data" :key="item.t">
                <option :value="item.t" x-text="item.t"></option>
              </template>
            </select>
            <div class="absolute left-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
              <i class="fas fa-chevron-down"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- عرض المحتوى -->
  <div class="container mx-auto px-4 md:px-6 py-12 md:py-16 -mt-8 relative z-20">
    <div class="max-w-6xl mx-auto">
      
      <div x-show="loading" class="text-center py-20 bg-white rounded-3xl shadow-sm ring-1 ring-slate-200">
        <i class="fas fa-circle-notch fa-spin text-4xl text-purple-600 mb-4 block"></i>
        <p class="font-bold text-slate-500">جاري تحميل البيانات...</p>
      </div>

      <div x-show="!loading && !selectedData" x-cloak class="text-center py-20 bg-white rounded-3xl shadow-sm ring-1 ring-slate-200">
        <div class="w-24 h-24 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-hand-pointer text-4xl text-purple-300"></i>
        </div>
        <h3 class="text-2xl font-black text-slate-700 mb-3">اختر تخصصاً من القائمة أعلاه</h3>
        <p class="text-slate-500 font-medium">لعرض الفصول والوحدات الخاصة به بشكل مفصل.</p>
      </div>

      <div x-show="selectedData" x-cloak>
        <div class="mb-10 text-center">
          <h2 class="text-3xl font-black text-slate-800" x-text="selectedData?.t"></h2>
          <div class="h-1 w-20 bg-purple-500 rounded-full mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <template x-for="semester in selectedData?.s" :key="semester.n">
            
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl ring-1 ring-slate-200/80 transition-all duration-300 overflow-hidden flex flex-col group">
              <!-- رأس الفصل -->
              <div class="bg-gradient-to-r from-slate-100 to-white border-b border-slate-100 p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-black text-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <span x-text="semester.n.replace('S', '')"></span>
                  </div>
                  <h3 class="font-black text-slate-800 text-lg">الفصل <span x-text="semester.n.replace('S', '')"></span></h3>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-md" x-text="semester.m.length + ' وحدات'"></span>
              </div>
              
              <!-- الوحدات -->
              <div class="p-5 flex-1">
                <ul class="space-y-3">
                  <template x-for="(module, index) in semester.m" :key="index">
                    <li class="flex items-start gap-3 group/item">
                      <div class="mt-0.5 text-purple-400 text-[10px]">
                        <i class="fas fa-circle"></i>
                      </div>
                      <div class="flex-1">
                        <span class="text-sm font-bold text-slate-700 group-hover/item:text-purple-700 transition-colors block leading-relaxed" x-text="module.name"></span>
                        <template x-if="module.url">
                          <a :href="module.url" target="_blank" class="text-xs font-bold text-purple-600 hover:text-purple-800 mt-1 inline-flex items-center gap-1">
                            <i class="fas fa-file-pdf"></i>
                            تحميل المحاور
                          </a>
                        </template>
                      </div>
                    </li>
                  </template>
                </ul>
              </div>
            </div>

          </template>
        </div>
        
        <!-- تنويه عدم وجود ملفات لبعض التخصصات -->
        <div class="mt-12 bg-amber-50 rounded-2xl p-6 ring-1 ring-amber-200/50 flex flex-col md:flex-row gap-5 items-center md:items-start text-center md:text-right">
          <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center shrink-0">
            <i class="fas fa-info text-xl"></i>
          </div>
          <div>
            <h4 class="font-black text-amber-800 mb-2">تنويه بخصوص الملفات والتخصصات</h4>
            <p class="text-sm font-medium text-amber-700 leading-relaxed">
              هذه البيانات مستخرجة من المصادر المفتوحة المتوفرة للمدرسة العليا للتربية والتكوين. روابط تحميل محاور الوحدات متاحة فقط في حال تم نشرها للعموم. بعض التخصصات (مثل الدراسات الإسلامية، الفلسفة...) غير مدرجة هنا لأنها غير متوفرة في الموقع المرجعي المفتوح للجامعة.
            </p>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<script>
function cleData() {
  return {
    data: [],
    loading: true,
    selectedTitle: '',
    
    get selectedData() {
      if (!this.selectedTitle) return null;
      return this.data.find(d => d.t === this.selectedTitle);
    },

    init() {
      fetch('/cle/data.php')
        .then(res => res.json())
        .then(data => {
          this.data = data;
          this.loading = false;
          // Auto select first if exists
          if(this.data.length > 0) {
              this.selectedTitle = this.data[0].t;
          }
        })
        .catch(err => {
          console.error(err);
          this.loading = false;
        });
    }
  }
}
</script>

<?php
require __DIR__ . '/../partials/app-cta.php';
require __DIR__ . '/../partials/footer.php';
