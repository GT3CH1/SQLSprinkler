#!/usr/bin/env python3
import sys
import RPi.GPIO as GPIO
import pymysql as sql
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
db = sql.connect(host=HOST, user=USER, passwd=PASS, db=DB)

cur = db.cursor()
isEnabled = 0
# Use all the SQL you like
cur.execute("SELECT enabled from Enabled");
for row in list(cur.fetchall()):
	isEnabled = row[0]
print("Is enabled -> " + str(isEnabled))
cur.execute("SELECT gpio, Time, id FROM Systems ")
# print all the first cell of all the rows
for row in list(cur.fetchall()):
	if (isEnabled==1):
		GPIO.setmode(GPIO.BCM)
		print("System %s "% row[2])
		GPIO.setup(int(row[0]), GPIO.OUT)
		GPIO.output(int(row[0]), False)
		time.sleep(int(row[1])*60)
		GPIO.output(int(row[0]), True)
	else:
		db.close()
		exit(0)
db.close()
