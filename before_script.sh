#!/bin/bash

mysql -e 'create database phpback_test;'
git checkout -- install
rm application/config/database.php

sudo apt-get install -y --force-yes apache2 libapache2-mod-php5 php5-curl php5-mysql php5-intl
sudo sed -i -e "s,/var/www,$(pwd)/web,g" /etc/apache2/sites-available/default
sudo /etc/init.d/apache2 restart


SELENIUM_HUB_URL='http://localhost:4444'

sudo apt-get install firefox -y --no-install-recommends
echo "FIREFOX VERSION --------------------------"
firefox --version
sh -e /etc/init.d/xvfb start
export DISPLAY=:99.0
wget http://selenium.googlecode.com/files/selenium-server-standalone-2.31.0.jar
php -S localhost:8080 -t ./ &
sudo xvfb-run java -jar selenium-server-standalone-2.31.0.jar > /dev/null.log &
sleep 30
