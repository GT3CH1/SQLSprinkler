#!/usr/bin/env python3
import sys
import RPi.GPIO as GPIO
import MySQLdb
import time
from dotenv import load_dotenv
import os
from os.path import join, dirname
dotenv_path = join(dirname(__file__), '../.env')
load_dotenv(dotenv_path)
HOST=os.getenv('SQLSPRINKLER_SQL_HOST')
USER=os.getenv('SQLSPRINKLER_USER')
PASS=os.getenv('SQLSPRINKLER_PASS')
DB=os.getenv('SQLSPRINKLER_DB')

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
db = MySQLdb.connect(host=HOST, user=USER, passwd=PASS, db=DB)
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
