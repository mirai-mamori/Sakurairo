# coding: utf-8
'''
Created on Apr 12, 2018
Update  on 2018-04-13
@author: Mashiro @ https://2heng.xin

Desc: Auto compress & minfy JavaScript codes and CSS style sheet
'''
import os
from os import listdir
from os.path import isfile, join
from jsmin import jsmin
from csscompressor import compress
import time
import codecs

localtime = time.asctime( time.localtime(time.time()) )
print (localtime)

pathJS = 'js/src/'
pathJSroot = 'js/'
pathCSS = 'css/src/'
pathCSSroot = 'css/'

jsfiles = [f for f in listdir(pathJS) if isfile(join(pathJS, f))]
cssfiles = [f for f in listdir(pathCSS) if isfile(join(pathCSS, f))]

strJS = '/*! Generate by Mashiro. ' + localtime + '*/'
strCSS = '/*! Generate by Mashiro. ' + localtime + '*/'

for f in jsfiles:
    with codecs.open(pathJS + f, 'r', encoding='utf-8') as file:
        data = file.read()
    strJS = strJS + data
    print(f)
        
JSminified = jsmin(strJS)
        
with codecs.open(pathJSroot + "lib.js", "w", encoding='utf-8') as text_file:
    print(JSminified, file=text_file)
    
    
print('------------------JS Done------------------')
    
for f in cssfiles:
    with codecs.open(pathCSS + f, 'r', encoding='utf-8') as file:
        data = file.read()
    strCSS = strCSS + data
    print(f)
      
CSSminified = compress(strCSS)
      
with codecs.open(pathCSSroot + "lib.css", "w", encoding='utf-8') as text_file:
    print(CSSminified, file=text_file)
    
print('------------------CSS Done------------------')

key = input('Press any key to quit') 
quit()