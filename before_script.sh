#!/bin/bash

mysql -e 'create database phpback_test;'
git checkout -- install
rm application/config/database.php


export DISPLAY=:99.0
wget http://selenium.googlecode.com/files/selenium-server-standalone-2.31.0.jar
sh -e /etc/init.d/xvfb start
php -S localhost:8080 -t ./ &
