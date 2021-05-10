#!/usr/bin/python
import sys
import RPi.GPIO as GPIO
import MySQLdb
import time
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
db = MySQLdb.connect(host="localhost",    # your host, usually localhost
                     user="root",         # your username
                     passwd="#FiddleFire",  # your password
                     db="SQLSprinkler")        # name of the data base
# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()
i = int(sys.argv[1])
cur.execute("SELECT Time FROM Systems WHERE gpio=" + str(i))
# print all the first cell of all the rows
for row in list(cur.fetchall()):
    print(row[0])
    GPIO.setup(i, GPIO.OUT)
    GPIO.output(i, False)
    time.sleep(row[0]*60)
    GPIO.output(i, True)
