<?php
require "database.php";

$mysql = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysql->connect_error) {
    echo('Connection Error (' . $mysql->connect_errno . ') ' . $mysql->connect_error . '<br>');
    exit(2);
}

$mysql->multi_query("INSERT INTO `settings` (`name`, `value`) VALUES
('welcometext-title', 'Welcome to our feedback'),
('welcometext-description', 'Here you can suggest ideas to improve our services or vote on ideas from other people');");
