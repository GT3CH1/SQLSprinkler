#!/usr/bin/python
dir = "/var/www/html/data/sys"
import RPi.GPIO as GPIO
import time
a = 0
GPIO.setmode(GPIO.BCM)
pin = [13,18,23,17,27,22,10,9,11,19]
for i in pin:

    GPIO.setup(i, GPIO.OUT)
    GPIO.output(i, False)
    a = a + 1
    print 'running for 15'
    time.sleep(5);

    GPIO.output(i, True)
    print 'sleeping'
    time.sleep(20);
quit()
