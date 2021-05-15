#!/bin/bash
source .env
mysql -u $SQLSPRINKLER_USER --password=$SQLSPRINKLER_PASS -h $SQLSPRINKLER_SQL_HOST -e "
CREATE DATABASE $SQLSPRINKLER_DB;
USE $SQLSPRINKLER_DB;
CREATE TABLE Systems(
Name TEXT NOT NULL,
GPIO INT NOT NULL,
Time INT NOT NULL,
id INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (id)
);
CREATE TABLE Enabled(enabled BOOL);
"