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

include "../application/config/database.php";

$mysql = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($server->connect_error) echo('Connection Error (' . $server->connect_errno . ') ' . $server->connect_error . '<br>');

$mysql->multi_query("INSERT INTO `settings` (`id`, `name`, `value`) VALUES
('', 'recaptchapublic', '". $_POST['rpublic'] ."'),
('', 'recaptchaprivate', '". $_POST['rprivate'] ."'),
('', 'maxvotes', '20'),
('', 'mainmail', '". $_POST['mainmail'] ."'),
('', 'title', '". $_POST['title'] ."'),
('', 'max_results', '10'),
('', 'language', 'english'),
('', 'smtp-host', '". $_POST['smtp-host'] ."'),
('', 'smtp-port', '". $_POST['smtp-port'] ."'),
('', 'smtp-user', '". $_POST['smtp-user'] ."'),
('', 'smtp-pass', '". $_POST['smtp-password'] ."');");

if(unlink('index2.php') && unlink('install2.php')) {
    header('Location: ../admin');
} else {
    echo "PLEASE DELETE install/ FOLDER MANUALLY. THEN GO TO yourwebsite.com/feedback/admin/ TO LOG IN";
}