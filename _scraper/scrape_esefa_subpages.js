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
        
        let allData = {};
        
        for (let url of links) {
            console.log("Scraping", url);
            const page = await browser.newPage();
            await page.goto(url, { waitUntil: 'networkidle2' });
            
            // Wait a bit for Elementor
            await new Promise(r => setTimeout(r, 2000));
            
            // Extract the title
            const title = await page.evaluate(() => {
                const h2 = document.querySelector('h2.elementor-heading-title');
                return h2 ? h2.innerText.trim() : document.title;
            });
            
            // Extract tables (semesters usually)
            const semesters = await page.evaluate(() => {
                const results = [];
                // Find all tables
                const tables = document.querySelectorAll('table');
                let semIndex = 1;
                for (let table of tables) {
                    const rows = Array.from(table.querySelectorAll('tr'));
                    let modules = [];
                    for (let row of rows) {
                        const cols = row.querySelectorAll('td, th');
                        // Find a link if exists
                        const a = row.querySelector('a');
                        let link = a ? a.href : '';
                        
                        // Extract text from columns
                        let textParts = Array.from(cols).map(c => c.innerText.trim()).filter(t => t.length > 0);
                        let name = textParts.join(' - ');
                        if (name.length > 3 && !name.includes('Semestre') && !name.includes('Module')) {
                            modules.push({
                                name: name,
                                url: link
                            });
                        }
                    }
                    if (modules.length > 0) {
                        results.push({
                            n: "S" + semIndex,
                            m: modules
                        });
                        semIndex++;
                    }
                }
                return results;
            });
            
            allData[title] = semesters;
            await page.close();
        }
        
        fs.writeFileSync('esefa_data.json', JSON.stringify(allData, null, 2));
        console.log("Finished!");
        await browser.close();
        
    } catch(e) {
        console.error(e);
    }
})();
