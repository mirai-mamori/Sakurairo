# coding=utf-8
'''
Created on Jun 6, 2021
Desc: image process
@author: Bymoye
'''
import os
from PIL import Image
from concurrent import futures
import hashlib
import json
from time import *

def hash(file):
    hasher = hashlib.md5()
    with open('gallary/' + file, 'rb') as afile:
        buf = afile.read()
        hasher.update(buf)
    hash = hasher.hexdigest()
    return {
        'hash':hash,
        "jpeg": 'jpeg/' + hash + '.jpeg',
        "webp": 'webp/' + hash + '.webp',
        "jpeg_th": 'jpeg/' + hash + '.th.jpeg',
        "webp_th": 'webp/' + hash + '.th.webp'
    }

def process_image(file):
    try:
        im = Image.open('gallary/' + file).convert('RGB')
    except Exception as e:
        print(f'error ： {e}')
        return False
    width, height = im.size
    lista = hash(file)
    ua = "pc" if width > height else "mobile"
    im.save(lista['jpeg'], 'JPEG', progressive=True)
    im.save(lista['webp'], 'WEBP')
    im.thumbnail((450, 300))
    im.save(lista['jpeg_th'], 'JPEG', progressive=True)
    im.save(lista['webp_th'], 'WEBP')
    if ua == "mobile":
        mobile[lista['hash']] = {
            'source': file,
            'jpeg': [lista['jpeg'], lista['jpeg_th']],
            'webp': [lista['webp'], lista['webp_th']]
        }
    else:
        pc[lista['hash']] = {
            'source': file,
            'jpeg': [lista['jpeg'], lista['jpeg_th']],
            'webp': [lista['webp'], lista['webp_th']]
        }
    return [pc,mobile]

pc = {}
mobile = {}
def try_process():
    filenames = [f for f in os.listdir('gallary') if os.path.isfile(os.path.join('gallary', f))]
    Trueid = 0
    Falseid = 0
    errorfile = []
    with futures.ProcessPoolExecutor(max_workers=8) as executor:
        for result in zip(filenames, executor.map(process_image, filenames)):
            if result[1] != False:
                listPC=result[1][0]
                listMobile=result[1][1]
                pc.update(listPC)
                mobile.update(listMobile)
                Trueid += 1
                print(f"已完成{Trueid}/{len(filenames)}")
            else:
                Falseid += 1
                print(f"出现错误文件:{result[0]}")
                errorfile.append(str(result[0]))
    with open('manifest.json', 'w+') as json_file:
        json.dump(pc, json_file)
    with open('manifest_mobile.json', 'w+') as json_file:
        json.dump(mobile, json_file)
    print(f"""
        任务已完成
        总计数量：{len(filenames)}
        成功处理数量：{Trueid}
        失败数量：{Falseid}
        失败文件：{errorfile}
        """)


if __name__ == '__main__':
    try_process()
