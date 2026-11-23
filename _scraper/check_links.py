import re

with open('esefa_full.html', 'r', encoding='utf-8') as f:
    html = f.read()

links = re.findall(r'<a[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)</a>', html, re.I | re.DOTALL)
for url, text in links:
    clean = re.sub(r'<[^>]+>', ' ', text).strip()
    if 'uiz.ac.ma' in url and ('Licence' in clean or 'Enseign' in clean or 'Langue' in clean or 'Math' in clean):
        print(f"{clean[:40]:<40} -> {url}")
