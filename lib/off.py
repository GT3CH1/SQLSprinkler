#!/usr/bin/python
import sys
import RPi.GPIO as GPIO
import time
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
i = 1
while i < 41:
    GPIO.setup(i, GPIO.OUT)
    print(i)
    GPIO.output(i, True)
    i=i+1
quit()
