import re

with open('esefa_full.html', 'r', encoding='utf-8') as f:
    html = f.read()

links = re.findall(r'<a[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)</a>', html, re.I | re.DOTALL)
count = 0
for url, text in links:
    if 'pdf' in url.lower() or 'drive' in url.lower() or 'enseignement' in url.lower():
        print(f"FOUND: {url}")
        count += 1
print(f"Total matching links: {count}")
