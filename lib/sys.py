#!/usr/bin/python
import MySQLdb
import time
import RPi.GPIO as GPIO
db = MySQLdb.connect(host="localhost",    # your host, usually localhost
                     user="root",         # your username
                     passwd="#FiddleFire",  # your password
                     db="SQLSprinkler")        # name of the data base
# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()
isEnabled = 0
# Use all the SQL you like
cur.execute("SELECT enabled from Enabled");
for row in list(cur.fetchall()):
	isEnabled = row[0]
print("Is enabled -> " + str(isEnabled))
cur.execute("SELECT gpio, Time, Days, id FROM Systems ")
# print all the first cell of all the rows
for row in list(cur.fetchall()):
	if (isEnabled==1):
		GPIO.setmode(GPIO.BCM)
		print("System %s "% row[3])
		GPIO.setup(int(row[0]), GPIO.OUT)
		GPIO.output(int(row[0]), False)
		time.sleep(int(row[1])*60)
		GPIO.output(int(row[0]), True)
	else:
		db.close()
		exit(0)
db.close()
