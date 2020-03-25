# coding=utf-8
'''
Created on Apr 23, 2018
Desc: Webp convertor
@author: Mashiro https://2heng.xin
'''
import os
import sys
import json
import hashlib
from PIL import Image

class Single(object):
  def __init__(self, file, manifest):
    self.file = file
    self.mani = manifest

  def hash(self):
    hasher = hashlib.md5()
    with open('gallary/' + self.file, 'rb') as afile:
      buf = afile.read()
      hasher.update(buf)
    self.hash = hasher.hexdigest()
    self.jpeg = 'jpeg/' + self.hash + '.jpeg'
    self.webp = 'webp/' + self.hash + '.webp'
    self.jpeg_th = 'jpeg/' + self.hash + '.th.jpeg'
    self.webp_th = 'webp/' + self.hash + '.th.webp'

  def optimize(self):
    im = Image.open('gallary/' + self.file).convert('RGB')
    im.save(self.jpeg, 'JPEG') # todo: TinyPNG API
    im.save(self.webp, 'WEBP')
    im.thumbnail((450, 300))
    im.save(self.jpeg_th, 'JPEG')  # todo: TinyPNG API
    im.save(self.webp_th, 'WEBP')

  def manifest(self):
    self.mani[self.hash] = {
      'source': self.file,
      'jpeg': [self.jpeg, self.jpeg_th],
      'webp': [self.webp, self.webp_th]
    }

  def main(self):
    self.hash()
    # if os.path.exists(self.jpeg) and os.path.exists(self.webp):
    self.optimize()
    self.manifest()
    return self.mani

def gen_manifest_json():
  onlyfiles = [f for f in os.listdir('gallary') if os.path.isfile(os.path.join('gallary', f))]
  id = 1
  Manifest = {}
  for f in onlyfiles:
    try:
      worker = Single(f, Manifest)
      Manifest = worker.main()
      print(str(id) + '/' + str(len(onlyfiles)))
      id += 1
    except OSError:
      print("Falied to optimize the picture: " + f)
  with open('manifest.json', 'w+') as json_file:
    json.dump(Manifest, json_file)


def main():
  gen_manifest_json()


if __name__ == '__main__':
  main()
  key = input('`manifest.json` saved. Press any key to quit.')
  quit()
