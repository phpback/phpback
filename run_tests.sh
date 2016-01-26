#!/bin/bash

# DELETE ALL TABLES
TABLES=$(mysql --user=root --password='' phpback_test -e "SHOW TABLES IN phpback_test;" | awk '{ print $1}' | grep -v '^Tables')

for t in $TABLES
do
 mysql --user=root --password='' phpback_test -e "DROP TABLE $t"
done

# CLEAR DIRECTORY
git checkout -- install
rm application/config/database.php

# MOUNT SERVER
gnome-terminal -e 'php -S localhost:8080';
gnome-terminal -e 'java -jar /usr/local/bin/selenium-server-standalone-2.48.2.jar';

# RUN TESTS
phpunit --colors tests/cases/InstallTest.php
phpunit --colors tests/cases/AdminPanelTest.php
phpunit --colors tests/cases/UserTest.php
