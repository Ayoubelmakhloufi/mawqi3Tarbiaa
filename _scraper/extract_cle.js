const puppeteer = require('puppeteer');
const fs = require('fs');

const links = [
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-langue-francaise/",
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-langue-anglaise/",
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-langue-arabe/",
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-mathematiques/",
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-sciences-de-la-vie-et-de-la-terre/",
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-physique-et-chimie/",
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-enseignement-primaire/",
    "https://esef.uiz.ac.ma/licence-deducation-enseignement-secondaire-amazigh/"
];

(async () => {
    try {
        const browser = await puppeteer.launch({
            executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            headless: 'new'
        });
        
        let allData = [];
        
        for (let url of links) {
            console.log("Scraping", url);
            const page = await browser.newPage();
            await page.goto(url, { waitUntil: 'networkidle2' });
            await new Promise(r => setTimeout(r, 2000));
            
            const pageData = await page.evaluate(() => {
                const titleEl = document.querySelector('h2.elementor-heading-title');
                let title = titleEl ? titleEl.innerText.trim() : document.title;
                // clean title
                title = title.replace('Licence d\'Éducation', '').replace('Enseignement Secondaire', '').replace('Enseignement Primaire', '').replace('-', '').trim();
                if (!title) title = document.title;
                
                const text = document.body.innerText;
                const lines = text.split('\n');
                
                let modules = [];
                let inModules = false;
                
                for (let line of lines) {
                    line = line.trim();
                    if (line.toLowerCase() === 'modules') {
                        inModules = true;
                        continue;
                    }
                    if (inModules && (line.toLowerCase().startsWith('module') || line.toLowerCase().includes('action '))) {
                        // strip "Module XX:"
                        let modName = line.replace(/Module\s*\d+\s*:/i, '').trim();
                        if (modName) {
                            modules.push(modName);
                        }
                    }
                }
                
                return {
                    title: title,
                    modules: modules
                };
            });
            
            // Post-process modules into semesters
            let semesters = [];
            let current_semester = [];
            let semIndex = 1;
            
            for (let mod of pageData.modules) {
                current_semester.push({ name: mod, url: "" }); // no URLs found on page
                
                if (mod.toLowerCase().includes('action') && mod.toLowerCase().includes('éducative')) {
                    semesters.push({
                        n: "S" + semIndex,
                        m: current_semester
                    });
                    semIndex++;
                    current_semester = [];
                }
            }
            if (current_semester.length > 0) {
                semesters.push({
                    n: "S" + semIndex,
                    m: current_semester
                });
            }
            
            allData.push({
                t: pageData.title,
                s: semesters
            });
            
            await page.close();
        }
        
        fs.writeFileSync('../data/cle.json', JSON.stringify(allData, null, 2));
        console.log("Finished generating cle.json with", allData.length, "specialties.");
        await browser.close();
        
    } catch(e) {
        console.error(e);
    }
})();
