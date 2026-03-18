import json, os, re
from collections import defaultdict

postman_dir = r'c:\Users\dell\valet\remedis\postman'
files = [f for f in os.listdir(postman_dir) if f.endswith('.json') and not f.startswith('_')]

resources_seen = defaultdict(set)
req_bodies = defaultdict(list)  # resource -> list of sample body keys

def scan_items(items, col_name):
    for item in items:
        if isinstance(item, dict):
            if 'item' in item:
                scan_items(item['item'], col_name)
            elif 'request' in item:
                req = item['request']
                url = ''
                if isinstance(req.get('url'), dict):
                    url = req['url'].get('raw','')
                elif isinstance(req.get('url'), str):
                    url = req['url']
                method = req.get('method', '')
                # find FHIR resource in URL
                m = re.search(r'/([A-Z][a-zA-Z]+)(\?|/|$)', url)
                if m:
                    resource = m.group(1)
                    resources_seen[col_name].add((method, resource))
                    # Capture request body for POST/PUT
                    if method in ('POST','PUT') and resource not in ('Bundle',):
                        body = req.get('body',{})
                        if body and body.get('mode') == 'raw':
                            raw = body.get('raw','')
                            if raw.strip().startswith('{'):
                                try:
                                    bd = json.loads(raw)
                                    keys = list(bd.keys())
                                    req_bodies[resource].append(keys)
                                except:
                                    pass

for fname in sorted(files):
    path = os.path.join(postman_dir, fname)
    col_name = re.sub(r'^\d+\.\s*','', fname.replace('.postman_collection.json',''))
    try:
        with open(path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        scan_items(data.get('item',[]), col_name)
    except Exception as e:
        print(f'ERR {fname}: {e}')

print("="*60)
print("FHIR RESOURCES BY COLLECTION")
print("="*60)
for col, res_set in sorted(resources_seen.items()):
    print(f'\n=== {col} ===')
    for method, res in sorted(res_set, key=lambda x: x[1]):
        print(f'  {method:6} {res}')

print("\n\n" + "="*60)
print("UNIQUE FHIR RESOURCES (ALL COLLECTIONS)")
print("="*60)
all_resources = set()
for res_set in resources_seen.values():
    for method, res in res_set:
        all_resources.add(res)
for r in sorted(all_resources):
    print(f'  {r}')

print("\n\n" + "="*60)
print("REQUEST BODY KEYS PER RESOURCE (for schema hints)")
print("="*60)
for res in sorted(req_bodies.keys()):
    all_keys = set()
    for keys in req_bodies[res]:
        all_keys.update(keys)
    print(f'  {res}: {sorted(all_keys)}')
