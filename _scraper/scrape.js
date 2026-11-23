const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
    try {
        const browser = await puppeteer.launch();
        const page = await browser.newPage();
        await page.goto('https://esef.uiz.ac.ma/licence-deduction/', { waitUntil: 'networkidle2' });
        
        // Extract all links that contain 'pdf' or 'drive'
        const links = await page.evaluate(() => {
            const anchors = Array.from(document.querySelectorAll('a'));
            return anchors.map(a => ({
                text: a.innerText.trim(),
                href: a.href
            })).filter(a => a.href.includes('.pdf') || a.href.includes('drive'));
        });
        
        // Extract all headings to see the structure
        const headings = await page.evaluate(() => {
            const hs = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6'));
            return hs.map(h => h.innerText.trim()).filter(h => h.includes('Licence') || h.includes('Semestre') || h.includes('M'));
        });
        
        // Extract accordion/tab texts (tables or lists of modules)
        const contentText = await page.evaluate(() => document.body.innerText);

        fs.writeFileSync('result_links.json', JSON.stringify(links, null, 2));
        fs.writeFileSync('result_headings.json', JSON.stringify(headings, null, 2));
        
        // Save raw html just in case
        const html = await page.content();
        fs.writeFileSync('raw_page.html', html);
        
        console.log("Scraping complete. Links found: " + links.length);
        await browser.close();
    } catch (e) {
        console.error("Error:", e);
    }
})();
