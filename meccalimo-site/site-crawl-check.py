import re,urllib.request,urllib.parse,html,json,concurrent.futures as cf
S='https://www.meccalimo.com'
def get(u,head=False):
    r=urllib.request.Request(u,method='HEAD' if head else 'GET',headers={'User-Agent':'Mozilla/5.0 SF-audit'})
    class NR(urllib.request.HTTPRedirectHandler):
        def redirect_request(*a,**k): return None
    op=urllib.request.build_opener(NR)
    try:
        resp=op.open(r,timeout=30); return resp.status,resp.headers,(b'' if head else resp.read())
    except urllib.error.HTTPError as e: return e.code,e.headers,b''
    except Exception as e: return 0,{},b''
sm=[l for l in re.findall(r'<loc>([^<]+)',get(S+'/sitemap_index.xml')[2].decode())]
pages=[]
for s in sm: pages+= [l for l in re.findall(r'<loc>([^<]+)',get(s)[2].decode()) if not l.endswith('.xml')]
pages=sorted(set(pages))
data={}
def fetch(u):
    c,h,b=get(u); t=b.decode('utf8','ignore'); return u,c,t
with cf.ThreadPoolExecutor(8) as ex:
    for u,c,t in ex.map(fetch,pages): data[u]=(c,t)
links={}; imgs=set()
for u,(c,t) in data.items():
    for a in re.findall(r'<a\s[^>]*href="([^"#]+)"',t):
        a=html.unescape(a); full=urllib.parse.urljoin(u,a)
        if 'meccalimo.com' in urllib.parse.urlparse(full).netloc: links.setdefault(full,set()).add(u)
    for i in re.findall(r'<img[^>]+src="([^"]+)"',t):
        full=urllib.parse.urljoin(u,html.unescape(i))
        if 'meccalimo.com' in full: imgs.add(full)
# check internal links status
status={}
def chk(l): c,h,_=get(l,head=True); return l,c,(h.get('Location') if h else None)
with cf.ThreadPoolExecutor(10) as ex:
    for l,c,loc in ex.map(chk,[l for l in links if not re.search(r'\.(webp|jpg|png|pdf)(\?|$)',l)]): status[l]=(c,loc)
out={'pages':len(pages),'bad':[],'redir':[],'odd':[],'canon':[],'title':[],'desc':[],'h1':{},'h1long':[],'imgs':[]}
for l,(c,loc) in status.items():
    if c>=400 or c==0: out['bad'].append((l,c,sorted(links[l])[:5]))
    elif 300<=c<400: out['redir'].append((l,c,loc,sorted(links[l])[:3]))
    if ' ' in l or '%20' in l or re.search(r'[A-Z]',urllib.parse.urlparse(l).path) or '_' in urllib.parse.urlparse(l).path or 'utm_' in l or len(l)>115: out['odd'].append((l,sorted(links[l])[:3]))
for u,(c,t) in data.items():
    can=re.search(r'rel="canonical" href="([^"]+)"',t)
    if can and can.group(1).rstrip('/')!=u.rstrip('/'): out['canon'].append((u,can.group(1)))
    ti=re.search(r'<title>(.*?)</title>',t,re.S); ti=html.unescape(ti.group(1)).strip() if ti else ''
    if len(ti)>60: out['title'].append((len(ti),u,ti))
    d=re.search(r'name="description" content="([^"]*)"',t); d=html.unescape(d.group(1)) if d else ''
    if len(d)<70 or len(d)>155: out['desc'].append((len(d),u,d[:80]))
    for h in re.findall(r'<h1[^>]*>(.*?)</h1>',t,re.S):
        h=re.sub('<[^>]+>','',html.unescape(h)).strip(); out['h1'].setdefault(h,[]).append(u)
        if len(h)>70: out['h1long'].append((len(h),u,h))
# image sizes
def isz(i):
    c,h,_=get(i,head=True); return i,int(h.get('Content-Length',0)) if h else 0
with cf.ThreadPoolExecutor(10) as ex:
    for i,sz in ex.map(isz,imgs):
        if sz>100*1024: out['imgs'].append((sz//1024,i))
out['h1']={k:v for k,v in out['h1'].items() if len(v)>1}
json.dump(out,open('crawl.json','w'),indent=1,default=list)
print('pages',len(pages),'internal links',len(status),'imgs',len(imgs))
for k in ['bad','redir','odd','canon','title','desc','h1long','imgs']: print(k,len(out[k]))
print('dup h1',len(out['h1']))
