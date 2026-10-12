import re,urllib.request,concurrent.futures as cf,io,json,html
from PIL import Image
def get(u,raw=False):
    try:
        r=urllib.request.urlopen(urllib.request.Request(u,headers={'User-Agent':'Mozilla/5.0 MeccaScan'}),timeout=30)
        b=r.read();return r.status,(b if raw else b.decode('utf-8','ignore'))
    except urllib.error.HTTPError as e: return e.code,(b'' if raw else '')
    except Exception as e: return 0,(b'' if raw else '')
urls=[]
for sm in ['page-sitemap.xml','post-sitemap.xml','category-sitemap.xml']:
    s,t=get('https://www.meccalimo.com/'+sm);urls+=re.findall(r'<loc>([^<]+)</loc>',t)
urls=sorted(set(urls));print('pages',len(urls))
bad_words={'personal number':r'980[\s\).-]*254[\s.-]*9944','alex/ashsales':r'ashsales|alexsofthouse','price $':r'\$\s?\d{2,4}(?!\d)','stretch limo':r'stretch limo','cruise':r'cruise','lorem':r'lorem ipsum'}
pages={};imgs={};links={}
def scan(u):
    s,t=get(u)
    info={'status':s}
    if s==200:
        info['title']=html.unescape((re.search(r'<title>(.*?)</title>',t,re.S) or [None,''])[1]).strip()
        info['desc']=bool(re.search(r'<meta name="description" content="[^"]{50,}',t))
        info['h1']=len(re.findall(r'<h1[\s>]',t))
        info['noindex']=bool(re.search(r'<meta name="robots" content="[^"]*noindex',t))
        body=re.sub(r'<script.*?</script>|<style.*?</style>','',t,flags=re.S)
        txt=re.sub(r'<[^>]+>',' ',body)
        info['flags']=[k for k,p in bad_words.items() if re.search(p,txt,re.I)]
        info['imgs']=list(set(re.findall(r'<img[^>]+src="([^"]+)"',t)))
        info['links']=list(set(l for l in re.findall(r'href="(https://www\.meccalimo\.com/[^"#?]*)"',t) if not re.search(r'\.(css|js|png|jpe?g|webp|xml|ico|svg|woff2?)$',l) and '/wp-' not in l))
    return u,info
with cf.ThreadPoolExecutor(8) as ex:
    for u,i in ex.map(scan,urls): pages[u]=i
allimg=set(x for i in pages.values() for x in i.get('imgs',[]))
def chk(u):
    s,b=get(u,True)
    if s!=200: return u,f'HTTP {s}'
    try: im=Image.open(io.BytesIO(b));im.load();
    except Exception as e: return u,'unreadable'
    # glitch detector: very high color noise
    sm=im.convert('RGB').resize((64,36));px=list(sm.getdata())
    sat=sum((max(p)-min(p)) for p in px)/len(px)
    return u,('ok' if sat<120 else f'suspicious sat{int(sat)}')
with cf.ThreadPoolExecutor(10) as ex: imgres=dict(ex.map(chk,allimg))
alllinks=set(x for i in pages.values() for x in i.get('links',[]))
def lk(u): return u,get(u)[0]
with cf.ThreadPoolExecutor(10) as ex: linkres=dict(ex.map(lk,alllinks))
json.dump({'pages':pages,'imgs':imgres,'links':linkres},open('scan.json','w'))
print('images',len(imgres),'links',len(linkres))
