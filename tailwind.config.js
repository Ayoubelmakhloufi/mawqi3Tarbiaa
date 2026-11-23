/**
 * إعداد Tailwind لموقع منصة تربية.
 * يُستعمل مرة واحدة عند إضافة تصاميم جديدة فقط:
 *   npx tailwindcss@3 -i assets/css/src.css -o assets/css/tarbiaa.css --minify
 * كتابة المقالات لا تتطلب إعادة التصريف لأن جسم المقال يستعمل وسوم HTML عادية.
 */
module.exports = {
  content: [
    './*.php',
    './*.html',
    './partials/**/*.php',
    './maqalat/**/*.php',
    './*/index.php',
    './*/*/index.php',
    './assets/js/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1F4529',
        secondary: '#2D6A4F',
        accent: '#50C878',
        gold: '#D4AF37',
      },
      fontFamily: {
        tajawal: ['Tajawal', 'system-ui', 'sans-serif'],
      },
      container: {
        center: true,
        screens: { '2xl': '1280px' },
      },
    },
  },
  plugins: [],
};
