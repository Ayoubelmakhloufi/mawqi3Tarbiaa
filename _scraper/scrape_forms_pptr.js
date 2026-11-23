const puppeteer = require('puppeteer');
const fs = require('fs');

const forms = {
    "الإبتدائي-مزدوج": "https://forms.gle/v2WqUyMqmt61rqGq9",
    "الإبتدائي-اللغة الأمازيغية": "https://forms.gle/HqmCr9dQeQtkim52A",
    "الدراسات الإسلامية": "https://forms.gle/LGefsmVBC9duz74KA",
    "الفلسفة": "https://forms.gle/iNErQhP6EKyGYkge6",
    "اللغة العربية": "https://forms.gle/4Wgna24VyH1oDneG8",
    "اللغة الإنجليزية": "https://forms.gle/kQHydKbyZGfiHGtJA",
    "اللغة الفرنسية": "https://forms.gle/ffocuaiAKA7Yodz38",
    "التاريخ والجغرافيا": "https://forms.gle/YZpJB4cCNCY6fktSA",
    "الرياضيات": "https://forms.gle/xC7JXBPSzY67vn438",
    "الفيزياء والكيمياء": "https://forms.gle/68TwKFc2SiP65f3s6",
    "علوم الحياة والأرض": "https://forms.gle/G2R1CCysqn51eTaj9",
    "المعلوميات": "https://forms.gle/sgcoTPJ6y3VgVv69A",
    "التربية البدنية والرياضية": "https://forms.gle/7sSkRtNyx7UCnjF2A",
    "العلوم الصناعية": "https://forms.gle/tsGaNSFYc6rydhb18"
};

(async () => {
    try {
        const browser = await puppeteer.launch({
            executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe'
        });
        const page = await browser.newPage();
        
        let allData = {};
        
        for (const [name, url] of Object.entries(forms)) {
            console.log("Scraping " + name + "...");
            await page.goto(url, { waitUntil: 'networkidle2' });
            
            // Extract the text from the form. In Google Forms, the titles of questions/sections are usually in divs with role="heading" or similar.
            // A simpler way: extract all spans that have a substantial length and look like module names.
            const modules = await page.evaluate(() => {
                const elements = Array.from(document.querySelectorAll('.M7eMe')); // M7eMe is often used for question titles
                if(elements.length > 0) {
                    return elements.map(e => e.innerText.trim()).filter(t => t.length > 0);
                }
                
                // fallback
                return Array.from(document.querySelectorAll('span, div'))
                    .map(e => e.innerText ? e.innerText.trim() : '')
                    .filter(t => t.length > 3 && t.length < 100);
            });
            
            // Also grab the raw html just in case we need a fallback
            const html = await page.content();
            
            // Look for FB_PUBLIC_LOAD_DATA_
            let extracted = [];
            const match = html.match(/var FB_PUBLIC_LOAD_DATA_ = (\[.*?\]);\n/);
            if (match) {
                try {
                    const data = JSON.parse(match[1]);
                    const questions = data[1][1];
                    for (let q of questions) {
                        if (q && q[1]) {
                            extracted.push(q[1]);
                        }
                    }
                } catch(e) {}
            }
            
            allData[name] = {
                extracted_from_json: extracted,
                raw_modules: modules
            };
        }
        
        fs.writeFileSync('google_forms_data.json', JSON.stringify(allData, null, 2));
        console.log("Finished scraping Google forms!");
        
        await browser.close();
    } catch (e) {
        console.error("Error:", e);
    }
})();
