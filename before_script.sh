#!/bin/bash

mysql -e 'create database phpback_test;'
git checkout -- install
rm application/config/database.php

SELENIUM_HUB_URL='http://127.0.0.1:4444'
SELENIUM_JAR=/usr/share/selenium/selenium-server-standalone.jar
SELENIUM_DOWNLOAD_URL=http://selenium-release.storage.googleapis.com/2.48/selenium-server-standalone-2.48.2.jar
PHP_VERSION=$(php -v)

ESCAPED_BUILD_DIR=$(echo "$TRAVIS_BUILD_DIR" | sed 's/\//\\\//g')

sudo apt-get update

echo "Installing Firefox"
sudo apt-get install firefox -y --no-install-recommends

if [ ! -f "$SELENIUM_JAR" ]; then
    echo "Downloading Selenium"
    sudo mkdir -p $(dirname "$SELENIUM_JAR")
    sudo wget -nv -O "$SELENIUM_JAR" "$SELENIUM_DOWNLOAD_URL"
fi

sudo killall -9 java
sudo killall -9 Xvfb
sudo rm -f /tmp/.X99-lock

wget --retry-connrefused --tries=120 --waitretry=3 --output-file=/dev/null "$SELENIUM_HUB_URL/wd/hub/status" -O /dev/null
if [ ! $? -eq 0 ]; then
    echo "Selenium Server not started"
else
    echo "Finished setup"
fi