<?php
define('BASEPATH', '');
/*********************************************************************
PHPBack
Ivan Diaz <ivan@phpback.org>
Copyright (c) 2014 PHPBack
http://www.phpback.org
Released under the GNU General Public License WITHOUT ANY WARRANTY.
See LICENSE.TXT for details.
**********************************************************************/

/* if started from commandline, wrap parameters to $_POST */
if (!isset($_SERVER["HTTP_HOST"])) 
    parse_str($argv[1], $_POST);


include "../application/config/database.php";

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
    echo "PLEASE DELETE install/ FOLDER MANUALLY. THEN GO TO yourwebsite.com/feedback/admin/ TO LOG IN";
}
