# -*- coding: utf-8 -*-
# @Author: bymoye
# @Date:   2021-06-02 12:13:43
# @Last Modified by:   bymoye
# @Last Modified time: 2022-03-24 22:33:17
import os,json
from PIL import Image
from concurrent import futures
from hashlib import md5

FORMAT = ('webp','jpeg')
SIZE = ('source','th')
THUMBNAIL = (900,600)
MD = (50,30)
# 过滤尺寸 要开设置
PCSIZE = (1920,1080)
MOBILESIZE = (900,1200)
FILTERTYPE = ('pc','mobile')
class ProcessImage():
    def __init__(self,flush: bool,filter: bool) -> None:
        if not os.path.exists('./jpeg'):
            os.mkdir('./jpeg')
        if not os.path.exists('./webp'):
            os.mkdir('./webp')
        self.pc = dict()
        self.mobile = dict()
        
        self.md5set = set()
        
        self.errorfiles = list()
        self.repeatfiles = list()
        self.failedfiles = list()
        self.status = {
            'Trueid':0,
            'Falseid':0,
            'Repeatid':0,
            'Failedid':0,
        }
        self.flush = flush
        self.filter = filter

    def process_image(self,file):
        try:
            source = open('gallary/' + file,'rb')
            _hash = md5(source.read()).hexdigest()
            if _hash in self.md5set:
                source.close()
                return 1
            image = Image.open(source).convert('RGB')
        except Exception as e:
            print(f'error : {e}')
            return 0
        width , height = image.size
        platform = "mobile" if width < height else "pc"

        if self.filter:
            filterwidth , filterheight = PCSIZE if platform == "pc" else MOBILESIZE
            if width < filterwidth or height < filterheight:
                source.close()
                image.close()
                return 2
            
        for format in FORMAT:
            _image = image.copy()
            for size in SIZE:
                if size == 'th':
                    _image.thumbnail(THUMBNAIL)
                if size == 'md':
                    _image.thumbnail(MD)
                if format == 'jpeg':
                    _image.save(f"{format}/{_hash}.{size}.{format}",format.upper(), quality=90, subsampling=0, progressive = True)
                else:
                    _image.save(f"{format}/{_hash}.{size}.{format}",format.upper(),quality=90, subsampling=0)
        source.close()
        image.close()
        _image.close()
        self.md5set.add(_hash)
        return {platform:{_hash:{'source':file}}}
    
    def try_process(self):
        if not os.path.exists('./gallary'):
            os.mkdir('./gallary')
            print('gallary文件夹不存在,已自动创建,请在该文件夹下放图片,再运行此程序')
            return False
        filenames = [file for file in os.listdir('gallary') if os.path.isfile(os.path.join('gallary', file))]
        if not self.flush:
            if os.path.exists('manifest.json'):
                with open('manifest.json','r') as f:
                    if f.read():
                        manifest = json.load(f)
                        _manifest = [i['source'] for i in list(manifest.values())]
                        self.md5set = self.md5set | {i for i in manifest.keys()}
                        filenames = list(set(filenames) - set(_manifest))
                        self.pc = {**manifest}
            if os.path.exists('manifest_mobile.json'):
                with open('manifest_mobile.json','r') as f:
                    if f.read():
                        manifest = json.load(f)
                        _manifest = [i['source'] for i in manifest.values()]
                        self.md5set = self.md5set | {i for i in manifest.keys()}
                        filenames = list(set(filenames) - set(_manifest))
                        self.mobile = {**manifest}
        fileslen = len(filenames)
        progress = 0
        with futures.ProcessPoolExecutor(max_workers=8) as executor:
            for result in zip(filenames,executor.map(self.process_image, filenames)):
                filename , imgprocess = result
                if imgprocess == 0:
                    self.status['Falseid'] += 1
                    self.errorfiles.append(filename)
                    print(f"\n出现错误文件:{filename}")
                elif imgprocess == 1:
                    self.status['Repeatid'] += 1
                    self.repeatfiles.append(filename)
                    print(f"\n{filename} 已存在")
                elif imgprocess == 2:
                    self.status['Failedid'] += 1
                    self.failedfiles.append(filename)
                    print(f"\n{filename} 尺寸不达标")
                else:
                    self.status['Trueid'] += 1
                    if imgprocess.get('pc'):
                        self.pc.update(imgprocess['pc'])
                    elif imgprocess.get('mobile'):
                        self.mobile.update(imgprocess['mobile'])
                progress += 1
                print(f"\r{filename} 已进行处理，进度 {progress}/{fileslen}",end="",flush=True)
                
        with open('manifest.json','w+') as json_file:
            json.dump(self.pc,json_file)

        with open('manifest_mobile.json','w+') as json_file:
            json.dump(self.mobile,json_file)
        print(f"""
            任务已完成
            总计数量：{fileslen}
            成功数量：{self.status['Trueid']}
            
            失败数量：{self.status['Falseid']}
            失败文件：{self.errorfiles}
            
            重复数量：{self.status['Repeatid']}
            重复文件：{self.repeatfiles}
            
            不合格数量：{self.status['Failedid']}
            不合格文件：{self.failedfiles}
            
            当前总计   pc   图片数量：{len(self.pc)}
            当前总计 mobile 图片数量：{len(self.mobile)}
            """)

if __name__ == '__main__':
    # 如果 flush 为True 将会清空原有的manifest.json文件
    # 如果 flush 为False 将会追加新的manifest.json文件
    # 如果filter 为True 将会过滤掉不合格的图片
    temp = ProcessImage(flush=False,filter=False)
    temp.try_process()