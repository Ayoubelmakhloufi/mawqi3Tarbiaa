<?php
/**
 * مولّد جذاذة درس — أداة تعمل بالكامل داخل المتصفح.
 *
 * ثلاثة نماذج للإخراج، والمعطيات مشتركة بينها فالتبديل لا يُفقد ما كُتب:
 *   maqati3 — نموذج المقاطع: صناديق علوية + جدول (مقاطع · أنشطة · طرق ووسائل · مؤشرات التقويم)
 *   marahil — نموذج المراحل: جدول (المرحلة · المدة · نشاط الأستاذ · نشاط المتعلم)
 *   basit   — نموذج مبسّط: قائمة عمودية بلا جداول، يناسب الطباعة السريعة
 *
 * لا تُرسَل أي بيانات إلى الخادم ولا تُخزَّن.
 * الطباعة تعتمد قواعد @media print في الـCSS المُصرَّف: تُخفي كل ما عليه .no-print.
 */

require __DIR__ . '/partials/lib.php';

$meta = [
    'title'       => 'مولّد جذاذة درس — ثلاثة نماذج جاهزة للطباعة',
    'description' => 'أداة مجانية لإعداد جذاذة درس: اختر نموذج المقاطع أو المراحل أو المبسّط، '
                   . 'املأ الكفايات والأهداف والأنشطة ومؤشرات التقويم، واطبعها جاهزة. داخل متصفحك بلا تسجيل.',
    'canonical'   => '/molid-jadada',
    'image'       => '/assets/img/og-default.jpg',
    'section'     => 'adawat',
    'breadcrumb'  => [
        ['name' => 'الأدوات والحاسبات', 'url' => '/adawat/'],
        ['name' => 'مولّد جذاذة درس', 'url' => '/molid-jadada'],
    ],
    'jsonld'      => [[
        '@context'    => 'https://schema.org',
        '@type'       => 'WebApplication',
        'name'        => 'مولّد جذاذة درس',
        'url'         => tb_abs('/molid-jadada'),
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem'     => 'أي متصفح',
        'inLanguage'  => 'ar',
        'offers'      => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'MAD'],
        'publisher'   => ['@id' => SITE_URL . '/#organization'],
    ]],
];

/** حقل نصّي في النموذج — يختصر تكرار الأصناف */
function tb_field(string $model, string $label, string $ph = ''): void
{
    echo '<label class="block">'
       . '<span class="block text-sm font-bold text-slate-600 mb-2">' . e($label) . '</span>'
       . '<input type="text" x-model="f.' . e($model) . '" placeholder="' . e($ph) . '"'
       . ' class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">'
       . '</label>';
}

require __DIR__ . '/partials/header.php';
?>

<section class="gradient-bg relative overflow-hidden no-print">
  <div class="absolute inset-0 opacity-10" aria-hidden="true">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  </div>
  <div class="container mx-auto px-4 md:px-6 py-10 md:py-14 relative z-10">
    <nav aria-label="مسار التصفح" class="mb-5">
      <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-white/50">
        <li><a href="/" class="hover:text-accent transition-colors">الرئيسية</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li><a href="/adawat/" class="hover:text-accent transition-colors">الأدوات</a></li>
        <li aria-hidden="true"><i class="fas fa-angle-left text-[10px]"></i></li>
        <li class="text-white/80" aria-current="page">مولّد الجذاذة</li>
      </ol>
    </nav>
    <div class="max-w-3xl text-white">
      <h1 class="text-3xl md:text-4xl font-black leading-tight mb-3">مولّد جذاذة درس</h1>
      <p class="text-lg text-slate-300 leading-relaxed mb-5">
        اختر <b>النموذج</b> الذي تعتمده مؤسستك، املأ الحقول، واطبع جذاذتك جاهزة.
        المعطيات مشتركة بين النماذج الثلاثة — بدّل بينها متى شئت دون أن تفقد ما كتبت.
      </p>
      <div class="flex flex-wrap gap-3 text-sm font-bold">
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-table-cells"></i> ثلاثة نماذج</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-print"></i> طباعة مباشرة</span>
        <span class="inline-flex items-center gap-2 bg-white/10 text-accent rounded-xl px-4 py-2.5"><i class="fas fa-lock"></i> لا تُرسَل بياناتك</span>
      </div>
    </div>
  </div>
</section>

<div class="bg-slate-50 py-10 md:py-14" x-data="jadada()">
  <div class="container mx-auto px-4 md:px-6">
    <div class="max-w-4xl mx-auto">

      <div class="no-print">

        <!-- اختيار النموذج -->
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-3">
            <i class="fas fa-table-cells text-accent"></i> اختر النموذج
          </h2>
          <p class="text-sm text-slate-500 leading-relaxed mb-6">
            النماذج تختلف في <b>شكل الإخراج</b> لا في المضمون. اختر ما تعتمده مؤسستك أو مادتك.
          </p>
          <div class="grid gap-3 md:grid-cols-3">
            <button type="button" @click="setLayout('maqati3')"
                    :class="f.layout === 'maqati3' ? 'bg-primary text-white ring-primary shadow-lg shadow-primary/20' : 'bg-white text-slate-700 ring-slate-200 hover:ring-primary/40'"
                    class="p-5 rounded-2xl ring-1 text-right transition-all">
              <span class="block font-black mb-1">نموذج المقاطع</span>
              <span class="block text-xs opacity-70 leading-relaxed">صناديق علوية + جدول: مقاطع · أنشطة ديداكتيكية · طرق ووسائل · مؤشرات التقويم</span>
            </button>
            <button type="button" @click="setLayout('marahil')"
                    :class="f.layout === 'marahil' ? 'bg-primary text-white ring-primary shadow-lg shadow-primary/20' : 'bg-white text-slate-700 ring-slate-200 hover:ring-primary/40'"
                    class="p-5 rounded-2xl ring-1 text-right transition-all">
              <span class="block font-black mb-1">نموذج المراحل</span>
              <span class="block text-xs opacity-70 leading-relaxed">جدول: المرحلة · المدة · نشاط الأستاذ · نشاط المتعلم</span>
            </button>
            <button type="button" @click="setLayout('basit')"
                    :class="f.layout === 'basit' ? 'bg-primary text-white ring-primary shadow-lg shadow-primary/20' : 'bg-white text-slate-700 ring-slate-200 hover:ring-primary/40'"
                    class="p-5 rounded-2xl ring-1 text-right transition-all">
              <span class="block font-black mb-1">نموذج مبسّط</span>
              <span class="block text-xs opacity-70 leading-relaxed">قائمة عمودية بلا جداول — يناسب الطباعة السريعة</span>
            </button>
          </div>
        </div>

        <!-- المعطيات العامة -->
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-6">
            <i class="fas fa-circle-info text-accent"></i> المعطيات العامة
          </h2>
          <div class="grid gap-4 sm:grid-cols-2">
            <?php
            tb_field('year', 'الموسم الدراسي', '2026 / 2027');
            tb_field('school', 'المؤسسة', 'إعدادية…');
            tb_field('teacher', 'الأستاذ(ة)', 'الاسم الكامل');
            tb_field('subject', 'المادة', 'التربية الإسلامية · الرياضيات…');
            tb_field('level', 'الفئة المستهدفة', 'الأولى إعدادي');
            tb_field('unit', 'المدخل / الوحدة', 'التزكية');
            tb_field('title', 'عنوان الدرس', 'المقطع الأول من سورة لقمان');
            tb_field('duration', 'المدة الزمنية', 'ساعتان من الزمن');
            tb_field('tools', 'الوسائل', 'السبورة — كتاب التلميذ — دفتر التمارين');
            tb_field('methods', 'الطرق', 'وضعية مشكلة — الحوارية — الاستنتاجية');
            tb_field('date', 'التاريخ', '2 غشت 2026');
            ?>
          </div>
        </div>

        <!-- الأهداف والكفايات والمراجع -->
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-3">
            <i class="fas fa-bullseye text-accent"></i> الأهداف والكفايات
          </h2>
          <p class="text-sm text-slate-500 leading-relaxed mb-6">
            صِغ الهدف بفعل <b>قابل للملاحظة</b> (يحسب · يصنّف · يحرر) لا بـ«يفهم» أو «يعرف»، فالفهم لا يُرى.
            راجع <a href="/maqalat/tasnif-bloom" class="text-primary font-bold">تصنيف بلوم</a>
            و<a href="/maqalat/kayfa-tabni-jadada" class="text-primary font-bold">كيف تبني جذاذة ناجحة</a>.
          </p>

          <span class="block text-sm font-bold text-slate-600 mb-2">أهداف الدرس</span>
          <template x-for="(o, i) in f.goals" :key="i">
            <div class="flex items-center gap-2 mb-2">
              <input type="text" :value="o" @input="f.goals[i] = $event.target.value"
                     placeholder="التعرف على المعنى الشرعي لـ…"
                     class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
              <button type="button" @click="f.goals.splice(i, 1)" x-show="f.goals.length > 1" aria-label="حذف الهدف"
                      class="w-12 h-12 shrink-0 rounded-xl bg-slate-100 text-red-600 hover:bg-slate-200 transition-colors">
                <i class="fas fa-xmark"></i>
              </button>
            </div>
          </template>
          <button type="button" @click="f.goals.push('')"
                  class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
            <i class="fas fa-plus text-xs"></i> أضف هدفًا
          </button>

          <div x-show="f.layout === 'maqati3'" x-cloak class="mt-6">
            <span class="block text-sm font-bold text-slate-600 mb-2">الكفايات المستهدفة</span>
            <template x-for="(c, i) in f.competencies" :key="i">
              <div class="flex items-start gap-2 mb-2">
                <input type="text" :value="c.name" @input="f.competencies[i].name = $event.target.value"
                       placeholder="النوع"
                       class="w-20 shrink-0 rounded-xl border-2 border-slate-200 bg-white px-3 py-3 font-bold text-slate-800 focus:border-accent outline-none transition">
                <textarea :value="c.text" @input="f.competencies[i].text = $event.target.value" rows="2"
                          placeholder="وصف الكفاية"
                          class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
                <button type="button" @click="f.competencies.splice(i, 1)" x-show="f.competencies.length > 1" aria-label="حذف الكفاية"
                        class="w-12 h-12 shrink-0 rounded-xl bg-slate-100 text-red-600 hover:bg-slate-200 transition-colors">
                  <i class="fas fa-xmark"></i>
                </button>
              </div>
            </template>
            <button type="button" @click="f.competencies.push({ name: '', text: '' })"
                    class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
              <i class="fas fa-plus text-xs"></i> أضف كفاية
            </button>
          </div>

          <label class="block mt-6" x-show="f.layout !== 'maqati3'" x-cloak>
            <span class="block text-sm font-bold text-slate-600 mb-2">الكفاية المستهدفة</span>
            <textarea x-model="f.competence" rows="2" placeholder="أن يكون المتعلم قادرًا على… في وضعية جديدة"
                      class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
          </label>

          <div class="mt-6">
            <span class="block text-sm font-bold text-slate-600 mb-2">المصادر والمراجع المعتمدة</span>
            <template x-for="(r, i) in f.refs" :key="i">
              <div class="flex items-center gap-2 mb-2">
                <input type="text" :value="r" @input="f.refs[i] = $event.target.value"
                       placeholder="كتاب في رحاب التربية الإسلامية — الأولى إعدادي"
                       class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition">
                <button type="button" @click="f.refs.splice(i, 1)" x-show="f.refs.length > 1" aria-label="حذف المرجع"
                        class="w-12 h-12 shrink-0 rounded-xl bg-slate-100 text-red-600 hover:bg-slate-200 transition-colors">
                  <i class="fas fa-xmark"></i>
                </button>
              </div>
            </template>
            <button type="button" @click="f.refs.push('')"
                    class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
              <i class="fas fa-plus text-xs"></i> أضف مرجعًا
            </button>
          </div>

          <label class="block mt-6">
            <span class="block text-sm font-bold text-slate-600 mb-2">المتطلبات القبلية</span>
            <textarea x-model="f.prereq" rows="2" placeholder="ما يجب أن يتقنه المتعلم قبل هذا الدرس"
                      class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
          </label>
        </div>

        <!-- المقاطع / المراحل -->
        <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 mb-6">
          <h2 class="text-xl font-black text-primary flex items-center gap-2.5 mb-3">
            <i class="fas fa-list-ol text-accent"></i>
            <span x-text="f.layout === 'maqati3' ? 'مقاطع الدرس' : 'مراحل الدرس'"></span>
          </h2>
          <p class="text-sm text-slate-500 leading-relaxed mb-6">
            <b>احجز وقتًا للاستثمار والتقويم</b> — هما أول ما يُضحّى به عند ضيق الوقت،
            وهما بالضبط لحظة التحقق من التعلم.
          </p>

          <template x-for="(s, i) in f.stages" :key="i">
            <div class="bg-slate-50 rounded-2xl p-5 mb-3">
              <div class="flex items-center gap-2 mb-3">
                <input type="text" :value="s.name" @input="f.stages[i].name = $event.target.value"
                       placeholder="اسم المقطع"
                       class="flex-1 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 font-bold text-slate-800 focus:border-accent outline-none transition">
                <input type="text" :value="s.time" @input="f.stages[i].time = $event.target.value"
                       placeholder="المدة" x-show="f.layout !== 'maqati3'"
                       class="w-20 shrink-0 rounded-xl border-2 border-slate-200 bg-white px-3 py-2.5 font-medium text-slate-800 focus:border-accent outline-none transition">
                <button type="button" @click="f.stages.splice(i, 1)" x-show="f.stages.length > 1" aria-label="حذف المقطع"
                        class="w-12 h-12 shrink-0 rounded-xl bg-white text-red-600 hover:bg-slate-100 transition-colors">
                  <i class="fas fa-xmark"></i>
                </button>
              </div>

              <!-- نموذج المقاطع -->
              <div x-show="f.layout === 'maqati3'" x-cloak class="grid gap-3">
                <textarea :value="s.activities" @input="f.stages[i].activities = $event.target.value" rows="4"
                          placeholder="الأنشطة الديداكتيكية"
                          class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
                <div class="grid gap-3 sm:grid-cols-2">
                  <textarea :value="s.methods" @input="f.stages[i].methods = $event.target.value" rows="3"
                            placeholder="الطرق والوسائل"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
                  <textarea :value="s.indicators" @input="f.stages[i].indicators = $event.target.value" rows="3"
                            placeholder="مؤشرات التقويم"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
                </div>
              </div>

              <!-- نموذج المراحل والمبسّط -->
              <div x-show="f.layout !== 'maqati3'" x-cloak class="grid gap-3 sm:grid-cols-2">
                <textarea :value="s.teacher" @input="f.stages[i].teacher = $event.target.value" rows="3"
                          placeholder="نشاط الأستاذ"
                          class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
                <textarea :value="s.learner" @input="f.stages[i].learner = $event.target.value" rows="3"
                          placeholder="نشاط المتعلم"
                          class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
              </div>
            </div>
          </template>

          <div class="flex flex-wrap gap-3 mt-4">
            <button type="button" @click="addStage()"
                    class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:gap-3 transition-all">
              <i class="fas fa-plus text-xs"></i> <span x-text="f.layout === 'maqati3' ? 'أضف مقطعًا' : 'أضف مرحلة'"></span>
            </button>
            <button type="button" @click="resetStages()"
                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition-colors">
              <i class="fas fa-rotate-right text-xs"></i> استرجع المقترح لهذا النموذج
            </button>
          </div>

          <div class="grid gap-4 sm:grid-cols-2 mt-6">
            <label class="block">
              <span class="block text-sm font-bold text-slate-600 mb-2">التقويم الإجمالي</span>
              <textarea x-model="f.assess" rows="3" placeholder="كيف تتحقق أن الهدف تحقق فعلًا؟"
                        class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
            </label>
            <label class="block">
              <span class="block text-sm font-bold text-slate-600 mb-2">الامتداد / الواجب</span>
              <textarea x-model="f.homework" rows="3" placeholder="تثبيت لما تُدُرِّب عليه في القسم"
                        class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 font-medium text-slate-800 focus:border-accent outline-none transition"></textarea>
            </label>
          </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-10">
          <button type="button" @click="print()"
                  class="flex-1 bg-primary text-white py-4 rounded-xl font-black text-lg hover:bg-secondary transition-all shadow-lg shadow-primary/20">
            <i class="fas fa-print ml-2"></i> اطبع الجذاذة
          </button>
          <button type="button" @click="clear()"
                  class="bg-slate-100 text-slate-700 px-6 py-4 rounded-xl font-black hover:bg-slate-200 transition-colors">
            <i class="fas fa-eraser ml-2"></i> امسح الكل
          </button>
        </div>

        <h2 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-2.5">
          <i class="fas fa-eye text-accent"></i> المعاينة
        </h2>
      </div>

      <!-- ــــ الجذاذة (هي ما يُطبع) ــــ -->
      <div class="bg-white rounded-3xl ring-1 ring-slate-200 p-5 md:p-8 article-body">

        <!-- ===== نموذج المقاطع ===== -->
        <div x-show="f.layout === 'maqati3'" x-cloak>

          <div class="grid gap-3 md:grid-cols-3 mb-3">
            <div class="ring-1 ring-slate-300 rounded-2xl p-4">
              <ul>
                <li><b>الموسم الدراسي:</b> <span x-text="f.year"></span></li>
                <li><b>المؤسسة:</b> <span x-text="f.school"></span></li>
                <li><b>الفئة المستهدفة:</b> <span x-text="f.level"></span></li>
                <li><b>المدة الزمنية:</b> <span x-text="f.duration"></span></li>
                <li><b>الوسائل:</b> <span x-text="f.tools"></span></li>
                <li><b>الطرق:</b> <span x-text="f.methods"></span></li>
              </ul>
            </div>
            <div class="ring-1 ring-slate-300 rounded-2xl p-4 text-center">
              <p><b>مدخل:</b> <span x-text="f.unit"></span></p>
              <p class="mt-3"><b>عنوان الدرس:</b></p>
              <p><b x-text="f.title"></b></p>
              <p class="mt-3"><span x-text="f.subject"></span></p>
            </div>
            <div class="ring-1 ring-slate-300 rounded-2xl p-4">
              <p><b>أهداف الدرس:</b></p>
              <ul>
                <template x-for="(o, i) in filled(f.goals)" :key="i"><li x-text="o"></li></template>
              </ul>
            </div>
          </div>

          <div class="grid gap-3 md:grid-cols-2 mb-5">
            <div class="ring-1 ring-slate-300 rounded-2xl p-4">
              <p><b>الكفايات المستهدفة:</b></p>
              <ul>
                <template x-for="(c, i) in f.competencies" :key="i">
                  <li x-show="c.name || c.text"><b x-text="c.name ? c.name + ': ' : ''"></b><span x-text="c.text"></span></li>
                </template>
              </ul>
            </div>
            <div class="ring-1 ring-slate-300 rounded-2xl p-4">
              <p><b>المصادر والمراجع المعتمدة:</b></p>
              <ul>
                <template x-for="(r, i) in filled(f.refs)" :key="i"><li x-text="r"></li></template>
              </ul>
            </div>
          </div>

          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>مقاطع الدرس</th>
                  <th>الأنشطة الديداكتيكية</th>
                  <th>الطرق والوسائل</th>
                  <th>مؤشرات التقويم</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="(s, i) in f.stages" :key="i">
                  <tr>
                    <td><b x-text="s.name"></b></td>
                    <td x-text="s.activities"></td>
                    <td x-text="s.methods"></td>
                    <td x-text="s.indicators"></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <template x-if="f.prereq || f.assess || f.homework">
            <div class="table-wrap">
              <table>
                <tbody>
                  <tr x-show="f.prereq"><th>المتطلبات القبلية</th><td x-text="f.prereq"></td></tr>
                  <tr x-show="f.assess"><th>التقويم الإجمالي</th><td x-text="f.assess"></td></tr>
                  <tr x-show="f.homework"><th>الامتداد / الواجب</th><td x-text="f.homework"></td></tr>
                </tbody>
              </table>
            </div>
          </template>
        </div>

        <!-- ===== نموذج المراحل ===== -->
        <div x-show="f.layout === 'marahil'" x-cloak>
          <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
            <div>
              <p class="font-bold text-slate-700" x-text="f.school || 'المؤسسة'"></p>
              <p class="text-sm text-slate-500" x-text="f.teacher ? 'الأستاذ(ة): ' + f.teacher : ''"></p>
            </div>
            <p class="text-sm text-slate-500" x-text="f.date"></p>
          </div>

          <h2 class="text-center" x-text="f.title || 'جذاذة درس'"></h2>

          <div class="table-wrap">
            <table>
              <tbody>
                <tr><th>المادة</th><td x-text="f.subject"></td><th>المستوى</th><td x-text="f.level"></td></tr>
                <tr><th>المدخل / الوحدة</th><td x-text="f.unit"></td><th>المدة</th><td x-text="f.duration"></td></tr>
              </tbody>
            </table>
          </div>

          <h3>الكفاية المستهدفة</h3>
          <p x-text="f.competence || '—'"></p>

          <h3>الأهداف التعلّمية</h3>
          <ul>
            <template x-for="(o, i) in filled(f.goals)" :key="i"><li x-text="o"></li></template>
          </ul>
          <p x-show="filled(f.goals).length === 0">—</p>

          <div class="table-wrap">
            <table>
              <tbody>
                <tr><th>المتطلبات القبلية</th><td x-text="f.prereq"></td></tr>
                <tr><th>الوسائل والدعامات</th><td x-text="f.tools"></td></tr>
                <tr><th>الطرق</th><td x-text="f.methods"></td></tr>
              </tbody>
            </table>
          </div>

          <h3>مراحل الدرس</h3>
          <div class="table-wrap">
            <table>
              <thead><tr><th>المرحلة</th><th>المدة</th><th>نشاط الأستاذ</th><th>نشاط المتعلم</th></tr></thead>
              <tbody>
                <template x-for="(s, i) in f.stages" :key="i">
                  <tr>
                    <td x-text="s.name"></td><td x-text="s.time"></td>
                    <td x-text="s.teacher"></td><td x-text="s.learner"></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <h3>التقويم</h3>
          <p x-text="f.assess || '—'"></p>
          <h3>الامتداد / الواجب</h3>
          <p x-text="f.homework || '—'"></p>
        </div>

        <!-- ===== النموذج المبسّط ===== -->
        <div x-show="f.layout === 'basit'" x-cloak>
          <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
            <p class="font-bold text-slate-700" x-text="f.school"></p>
            <p class="text-sm text-slate-500" x-text="f.date"></p>
          </div>

          <h2 class="text-center" x-text="f.title || 'جذاذة درس'"></h2>
          <p class="text-center text-slate-500">
            <span x-text="f.subject"></span>
            <span x-show="f.level"> · <span x-text="f.level"></span></span>
            <span x-show="f.duration"> · <span x-text="f.duration"></span></span>
          </p>

          <h3>الكفاية والأهداف</h3>
          <p x-show="f.competence" x-text="f.competence"></p>
          <ul>
            <template x-for="(o, i) in filled(f.goals)" :key="i"><li x-text="o"></li></template>
          </ul>

          <h3 x-show="f.prereq">المتطلبات القبلية</h3>
          <p x-show="f.prereq" x-text="f.prereq"></p>

          <h3 x-show="f.tools || f.methods">الوسائل والطرق</h3>
          <p x-show="f.tools || f.methods">
            <span x-text="f.tools"></span><span x-show="f.tools && f.methods"> — </span><span x-text="f.methods"></span>
          </p>

          <h3>سير الدرس</h3>
          <template x-for="(s, i) in f.stages" :key="i">
            <div>
              <h4><span x-text="s.name"></span><span x-show="s.time"> (<span x-text="s.time"></span>)</span></h4>
              <ul>
                <li x-show="s.teacher"><b>الأستاذ:</b> <span x-text="s.teacher"></span></li>
                <li x-show="s.learner"><b>المتعلم:</b> <span x-text="s.learner"></span></li>
              </ul>
            </div>
          </template>

          <h3 x-show="f.assess">التقويم</h3>
          <p x-show="f.assess" x-text="f.assess"></p>
          <h3 x-show="f.homework">الامتداد / الواجب</h3>
          <p x-show="f.homework" x-text="f.homework"></p>
        </div>

      </div>

      <p class="mt-6 text-center text-sm text-slate-400 font-bold no-print">
        <i class="fas fa-lock text-xs"></i>
        كل ما تكتبه يبقى في متصفحك — لا يُرسَل إلى أي خادم ولا يُخزَّن.
      </p>

      <div class="mt-10 bg-white rounded-3xl ring-1 ring-slate-200 p-6 md:p-8 article-body no-print">
        <h2>أي نموذج تختار؟</h2>
        <div class="table-wrap">
          <table>
            <thead><tr><th>النموذج</th><th>يناسب</th></tr></thead>
            <tbody>
              <tr><td><b>المقاطع</b></td><td>الشائع في التربية الإسلامية والاجتماعيات واللغة العربية بالإعدادي والتأهيلي: مقطع تمهيدي ووسيطي وختامي، مع مؤشرات تقويم لكل مقطع.</td></tr>
              <tr><td><b>المراحل</b></td><td>البنية الديداكتيكية العامة: وضعية انطلاق ← بناء ← استثمار ← تقويم، مع فصل نشاط الأستاذ عن نشاط المتعلم.</td></tr>
              <tr><td><b>المبسّط</b></td><td>الابتدائي والتحضير السريع، أو حين تريد ورقة واحدة بلا جداول.</td></tr>
            </tbody>
          </table>
        </div>

        <h2>نصائح تجعل الجذاذة نافعة لا شكلية</h2>
        <ol>
          <li><b>ابدأ من النهاية.</b> حدّد الكفاية ودليل تحققها أولًا، ثم اختر الأنشطة —
              وإلا خرجتَ بأنشطة ممتعة لا تخدم الكفاية.</li>
          <li><b>هدف أو هدفان للحصة.</b> الجذاذة المكتظة تجعلك تسابق الزمن فتقطع الأنشطة قبل استخلاصها.</li>
          <li><b>مؤشر التقويم ملموس.</b> «مدى تمكن المتعلم» عبارة عامة؛ اكتب ما يمكن ملاحظته فعلًا.</li>
          <li><b>اكتب ملاحظاتك بعد الحصة</b> على الهامش: ما نجح، وأين تعثّروا، وما استغرق وقتًا أطول.
              هذا وحده ما يراكم خبرة مهنية عبر السنوات.</li>
        </ol>
        <div class="note-info">
          <b>تحقّق من فهمك النظري:</b> <a href="/ikhtibarat/takhtit/">اختبار التخطيط والجذاذة</a> —
          أسئلة عن المنهاج والأهداف وتصنيف بلوم ومراحل الدرس، بتصحيح فوري.
        </div>
      </div>

    </div>
  </div>
</div>

<script>
/** مولّد الجذاذة — كل شيء داخل المتصفح، بلا تخزين ولا إرسال. */
function jadada() {
  /** المقاطع/المراحل المقترحة لكل نموذج */
  function defaultStages(layout) {
    if (layout === 'maqati3') {
      return [
        { name: 'مقطع تمهيدي', time: '', teacher: '', learner: '',
          activities: 'مراقبة الإعداد القبلي · تقويم تشخيصي (عرض المدخل الإشكالي) · طرح أسئلة تمهيدية.',
          methods: 'الحوارية — وضعية مشكلة — السبورة',
          indicators: 'مدى تفاعل المتعلم واستعداده للانخراط في بناء الدرس' },
        { name: 'مقطع وسيطي', time: '', teacher: '', learner: '',
          activities: 'شرح المفردات والمفاهيم · استخراج المعاني الجزئية · استخلاص القيم والأحكام.',
          methods: 'الإلقائية — الحوارية — الاستنتاجية — الكتاب المدرسي',
          indicators: 'مدى تمكن المتعلم من تحليل محاور الدرس وربطها بالواقع المعيش' },
        { name: 'مقطع ختامي', time: '', teacher: '', learner: '',
          activities: 'توظيف المستخلص وربطه بقضايا الواقع · تقويم إجمالي (وضعيات تقويمية) · الإعداد القبلي للدرس المقبل.',
          methods: 'الحوارية — الاستنتاجية — دفتر التمارين',
          indicators: 'مدى قدرة المتعلم على توظيف المستفاد في حياته اليومية' }
      ];
    }

    return [
      { name: 'وضعية الانطلاق', time: '10 د', activities: '', methods: '', indicators: '',
        teacher: 'يطرح وضعية تستدعي المكتسب القبلي وتخلق الحاجة إلى الجديد.', learner: 'يستحضر مكتسباته ويصوغ فرضيات.' },
      { name: 'بناء التعلمات', time: '25 د', activities: '', methods: '', indicators: '',
        teacher: 'ينظّم العمل ويلاحظ ويتدخل بقدر عند التعثر.', learner: 'يشتغل فرديًا أو في مجموعات ويبني المفهوم.' },
      { name: 'الاستثمار', time: '10 د', activities: '', methods: '', indicators: '',
        teacher: 'يقترح وضعية جديدة لتشغيل ما بُني.', learner: 'يوظّف ما تعلّمه في سياق مختلف.' },
      { name: 'التقويم', time: '10 د', activities: '', methods: '', indicators: '',
        teacher: 'يقدّم مهمة قصيرة تكشف مدى التحقق.', learner: 'ينجز فرديًا ويصحّح ذاتيًا.' }
    ];
  }

  function blank(layout) {
    return {
      layout: layout,
      year: '', school: '', teacher: '', subject: '', level: '', unit: '',
      title: '', duration: '', date: '', tools: '', methods: '',
      competence: '', goals: [''], prereq: '', refs: [''],
      competencies: [
        { name: 'الاستراتيجية', text: '' },
        { name: 'التواصلية', text: '' },
        { name: 'المنهجية', text: '' },
        { name: 'الثقافية', text: '' }
      ],
      stages: defaultStages(layout),
      assess: '', homework: ''
    };
  }

  return {
    f: blank('maqati3'),

    /** القيم غير الفارغة فقط — حتى لا تظهر أسطر خالية في المعاينة */
    filled(list) {
      return list.filter(function (x) { return x && x.trim() !== ''; });
    },

    /**
     * تبديل النموذج دون فقد ما كُتب.
     * تُستبدل المقاطع فقط إن كانت ما تزال على المقترح الافتراضي،
     * حتى لا يضيع عمل المستخدم عند التبديل بالخطأ.
     */
    setLayout(next) {
      if (this.f.layout === next) { return; }
      var untouched = JSON.stringify(this.f.stages) === JSON.stringify(defaultStages(this.f.layout));
      this.f.layout = next;
      if (untouched) { this.f.stages = defaultStages(next); }
    },

    addStage() {
      this.f.stages.push({ name: '', time: '', teacher: '', learner: '', activities: '', methods: '', indicators: '' });
    },

    resetStages() { this.f.stages = defaultStages(this.f.layout); },

    clear() {
      if (window.confirm('سيُمسح كل ما كتبتَه. متأكد؟')) { this.f = blank(this.f.layout); }
    },

    print() { window.print(); }
  };
}
</script>

<?php
require __DIR__ . '/partials/app-cta.php';
require __DIR__ . '/partials/footer.php';
