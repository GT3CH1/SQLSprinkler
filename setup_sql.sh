#!/bin/bash
echo "Please enter username"
read username
echo "Please enter password"
read password
mysql -u $username --password=$password -e "
CREATE DATABASE SQLSprinkler_clone;USE SQLSprinkler_clone;CREATE TABLE Systems(
Name TEXT NOT NULL,
GPIO INT NOT NULL,
Time INT NOT NULL,
id INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (id)a
);
CREATE TABLE Enabled(enabled BOOL);
"
