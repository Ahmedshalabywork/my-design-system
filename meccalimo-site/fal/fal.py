#!/usr/bin/env python3
"""Run any fal.ai model (image or video) and download the results.

Usage:
  python3 fal.py <model-id> '<json-args>' [out-dir]
  python3 fal.py fal-ai/nano-banana-pro '{"prompt":"..."}' out
  python3 fal.py fal-ai/kling-video/v2.1/standard/image-to-video '{"prompt":"...","image_url":"local:refs/x.png"}' out
Any "local:<path>" value is uploaded to fal storage first. Reads the key from FAL_KEY.
"""
import json, os, sys, time, mimetypes, urllib.request

KEY = os.environ.get('FAL_KEY')
if not KEY:
    sys.exit('FAL_KEY is not set in this environment.')
H = {'Authorization': f'Key {KEY}', 'Content-Type': 'application/json'}


def req(method, url, body=None, headers=H, raw=False):
    data = body if raw else (json.dumps(body).encode() if body is not None else None)
    r = urllib.request.urlopen(urllib.request.Request(url, data=data, headers=headers, method=method), timeout=120)
    return json.loads(r.read() or b'{}')


def upload(path):
    ctype = mimetypes.guess_type(path)[0] or 'application/octet-stream'
    init = req('POST', 'https://rest.alpha.fal.ai/storage/upload/initiate',
               {'content_type': ctype, 'file_name': os.path.basename(path)})
    with open(path, 'rb') as f:
        urllib.request.urlopen(urllib.request.Request(init['upload_url'], data=f.read(),
                               headers={'Content-Type': ctype}, method='PUT'), timeout=300)
    return init['file_url']


def resolve(v):
    if isinstance(v, str) and v.startswith('local:'):
        return upload(v[6:])
    if isinstance(v, list):
        return [resolve(x) for x in v]
    if isinstance(v, dict):
        return {k: resolve(x) for k, x in v.items()}
    return v


def urls(o):
    if isinstance(o, dict):
        for k, v in o.items():
            if k == 'url' and isinstance(v, str) and v.startswith('http'):
                yield v
            else:
                yield from urls(v)
    elif isinstance(o, list):
        for x in o:
            yield from urls(x)


def main():
    model, args = sys.argv[1], resolve(json.loads(sys.argv[2]))
    out = sys.argv[3] if len(sys.argv) > 3 else 'out'
    os.makedirs(out, exist_ok=True)
    sub = req('POST', f'https://queue.fal.run/{model}', args)
    status_url, result_url = sub['status_url'], sub['response_url']
    while True:
        s = req('GET', status_url)
        if s.get('status') == 'COMPLETED':
            break
        if s.get('status') not in ('IN_QUEUE', 'IN_PROGRESS'):
            sys.exit(f'fal error: {s}')
        time.sleep(4)
    res = req('GET', result_url)
    json.dump(res, open(os.path.join(out, 'last-result.json'), 'w'), indent=1)
    for i, u in enumerate(dict.fromkeys(urls(res))):
        ext = os.path.splitext(u.split('?')[0])[1] or '.bin'
        p = os.path.join(out, f'{model.replace("/", "_")}_{int(time.time())}_{i}{ext}')
        urllib.request.urlretrieve(u, p)
        print(p)


if __name__ == '__main__':
    main()
