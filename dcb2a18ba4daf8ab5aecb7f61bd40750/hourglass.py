# -*- coding: utf-8 -*-
"""
Created on Tue Sep 27 13:22:09 2016

@author: kellyskarritt
"""

values=input("Input: ")
index=values.rfind(" ")
height = values[:index]
percent = values[index:].strip("%")

height = int(height)
percent = (int(percent))/100

if height >1:
    print("Output:",height)
else:
    print("ERROR: Height must be greater than 1")
    
if percent>0:
    if percent<1.01:
        print("Output:",percent)
    else:
        print("ERROR: Percent must be between 0 and 100")
else:
    print("ERROR: Percent must be between 0 and 100")

