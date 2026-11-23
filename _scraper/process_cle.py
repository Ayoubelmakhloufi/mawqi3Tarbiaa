import json
import re

with open(r'C:\Users\ayoub elmakhloufi\Documents\موقع تربية\_scraper\google_forms_data.json', 'r', encoding='utf-8') as f:
    data = json.load(f)

cle_data = []

def clean_module_name(name):
    # Remove things like "M1 : ", "M 32:", etc.
    name = re.sub(r'^M\s*\d+\s*[\:.-]\s*', '', name, flags=re.I)
    name = re.sub(r'^\d+\s*[\:.-]\s*', '', name)
    name = name.strip()
    return name

for spec_name, spec_info in data.items():
    # Use extracted_from_json if available and has items, else raw_modules
    items = spec_info.get("extracted_from_json", [])
    if not items or len(items) < 10:
        items = spec_info.get("raw_modules", [])
        
    semesters = []
    current_semester = []
    
    for item in items:
        # Sometimes there's garbage or question text like "اختر الوحدات"
        if "اختر" in item or "choisir" in item.lower():
            continue
            
        cleaned = clean_module_name(item)
        
        # Check if it's a semester title
        if re.search(r'^S\d$', cleaned, re.I) or re.search(r'Semestre\s*\d', cleaned, re.I):
            continue
            
        if cleaned.strip():
            current_semester.append(cleaned)
            
        if "action" in cleaned.lower() and "éducative" in cleaned.lower():
            semesters.append(current_semester)
            current_semester = []
            
    if current_semester:
        semesters.append(current_semester)
        
    # We want exactly 6 semesters ideally. If there are more/less, we just keep them.
    # The output format for each spec: { "t": "تخصص الإبتدائي-مزدوج", "s": [ { "n": "S1", "m": ["mod1", "mod2"] } ] }
    s_list = []
    for i, s_modules in enumerate(semesters):
        if s_modules:
            s_list.append({
                "n": f"S{i+1}",
                "m": s_modules
            })
            
    cle_data.append({
        "t": spec_name,
        "s": s_list
    })

with open(r'C:\Users\ayoub elmakhloufi\Documents\موقع تربية\data\cle.json', 'w', encoding='utf-8') as f:
    json.dump(cle_data, f, ensure_ascii=False, indent=2)

print("Created cle.json with", len(cle_data), "specialties.")
for d in cle_data:
    print(f" - {d['t']}: {len(d['s'])} semesters")
    for s in d['s']:
        print(f"   {s['n']}: {len(s['m'])} modules")
