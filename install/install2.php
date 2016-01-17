<?php
/**
 * First step of setup: database creation (action of index.php)
 * @copyright  Copyright (c) 2014 PHPBack
 * @author       Ivan Diaz <ivan@phpback.org>
 * @license      http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link            https://github.com/ivandiazwm/phpback
 * @since         1.0
 */

define('BASEPATH', '.');    //Make this script work with nginx

/* if started from commandline, wrap parameters to $_POST */
if (!isset($_SERVER["HTTP_HOST"])) 
    parse_str($argv[1], $_POST);

include "../application/config/database.php";
include "pretty_message.php";

$mysql = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysql->connect_error) {
    echo('Connection Error (' . $mysql->connect_errno . ') ' . $mysql->connect_error . '<br>');
    exit(2);
}

$mysql->multi_query("INSERT INTO `settings` (`id`, `name`, `value`) VALUES
('', 'recaptchapublic', '". $_POST['rpublic'] ."'),
('', 'recaptchaprivate', '". $_POST['rprivate'] ."'),
('', 'maxvotes', '". ((isset($_POST['maxvotes']) && $_POST['maxvotes'] != '')? $_POST['maxvotes'] : 20)."'),
('', 'mainmail', '". $_POST['mainmail'] ."'),
('', 'title', '". $_POST['title'] ."'),
('', 'max_results', '".((isset($_POST['max_results']) && $_POST['max_results'] != '')? $_POST['max_results'] : 10)."'),
('', 'language', '".(isset($_POST['language'])? $_POST['language'] : 'english')."'),
('', 'smtp-host', '". $_POST['smtp-host'] ."'),
('', 'smtp-port', '". $_POST['smtp-port'] ."'),
('', 'smtp-user', '". $_POST['smtp-user'] ."'),
('', 'smtp-pass', '". $_POST['smtp-password'] ."');");

if(unlink('index2.php') && unlink('install2.php')) {
    header('Location: ../admin');
} else {
    $url = getBaseUrl();
    displayMessage("PLEASE DELETE install/ FOLDER MANUALLY. THEN GO TO <a href='" . $url . "/admin/'>yourwebsite.com/feedback/admin/</a> TO LOG IN");
}
