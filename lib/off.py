#!/usr/bin/python
import sys
import RPi.GPIO as GPIO
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
cur.execute("SELECT gpio FROM Systems;")
# print all the first cell of all the rows
for row in list(cur.fetchall()[0]):
    GPIO.output(row,True)
