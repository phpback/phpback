#!/bin/bash

mysql -e 'create database phpback_test;'
git checkout -- install
rm application/config/database.php

sudo apt-get install supervisor -y --no-install-recommends
sudo cp ./.ci/phpunit-environment.conf /etc/supervisor/conf.d/
sudo sed -i "s/^directory=.*webserver$/directory=${ESCAPED_BUILD_DIR}\\/selenium-1-tests/" /etc/supervisor/conf.d/phpunit-environment.conf
sudo sed -i "s/^autostart=.*selenium$/autostart=true/" /etc/supervisor/conf.d/phpunit-environment.conf
sudo sed -i "s/^autostart=.*python-webserver$/autostart=true/" /etc/supervisor/conf.d/phpunit-environment.conf

sudo killall supervisord
sudo killall -9 java
sudo killall -9 Xvfb
sudo rm -f /tmp/.X99-lock
sudo /etc/init.d/supervisor start


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
