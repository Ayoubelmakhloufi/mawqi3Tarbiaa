const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
    try {
        const browser = await puppeteer.launch({
            executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            headless: 'new'
        });
        const page = await browser.newPage();
        console.log("Navigating...");
        await page.goto('https://esef.uiz.ac.ma/licence-deduction/', { waitUntil: 'networkidle2' });
        
        console.log("Waiting a bit...");
        await new Promise(r => setTimeout(r, 5000));
        
        // Dump the fully rendered HTML
        const html = await page.content();
        fs.writeFileSync('esefa_full.html', html);
        
        // Take screenshot
        await page.screenshot({path: 'esefa.png', fullPage: true});
        
        console.log("Done!");
        await browser.close();
    } catch (e) {
        console.error("Error:", e);
    }
})();
