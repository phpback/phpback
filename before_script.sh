#!/bin/bash

mysql -e 'create database phpback_test;'
git checkout -- install
rm application/config/database.php


SELENIUM_HUB_URL='http://localhost:4444'

sudo apt-get install firefox -y --no-install-recommends
echo "FIREFOX VERSION --------------------------"
firefox --version
export DISPLAY=:99.0
sh -e /etc/init.d/xvfb start
wget http://selenium.googlecode.com/files/selenium-server-standalone-2.31.0.jar
php -S localhost:8080 -t ./ &
sudo xvfb-run -a java -jar selenium-server-standalone-2.31.0.jar > /tmp/selenium.log &
wget --retry-connrefused --tries=120 --waitretry=3 --output-file=/dev/null "$SELENIUM_HUB_URL/wd/hub/status" -O /dev/null &
sleep 30

