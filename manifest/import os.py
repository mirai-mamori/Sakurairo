import os
import hashlib
import time
import glob
import os
from PIL import Image
import concurrent.futures
def func(msg):
#   for i in range(3):
    print(msg)
    return "done " + msg
def process_image(filename):
    # do sth here
    return filename

with concurrent.futures.ProcessPoolExecutor() as executor:
    filenames= glob.glob("*.jpg")
    for processedimg in zip(filenames, executor.map(process_image, filenames)):
        print('processed success')