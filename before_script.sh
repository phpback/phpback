#!/bin/bash

mysql -e 'create database phpback_test;'
git checkout -- install
rm application/config/database.php


sh -e /etc/init.d/xvfb start
export DISPLAY=:99.0
wget http://selenium.googlecode.com/files/selenium-server-standalone-2.31.0.jar
php -S localhost:8080 -t ./ &
java -jar selenium-server-standalone-2.15.0.jar &
sleep 30