<?php
/*********************************************************************
PHPBack
Ivan Diaz <ivan@phpback.org>
Copyright (c) 2014 PHPBack
http://www.phpback.org
Released under the GNU General Public License WITHOUT ANY WARRANTY.
See LICENSE.TXT for details.
**********************************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

function redirectpost($url, array $data){
    	
		echo '<html>
		    <head>
		        <script type="text/javascript">
		            function close() {
		                document.forms["redirectpost"].submit();
		            }
		        </script>
		        <title>Please Wait...</title>
		    </head>
		    <body onload="close();">
		    Please Wait...<br>
		    <form name="redirectpost" method="post" action="' . $url .'">';
		    if ( !is_null($data) ) {
		    	foreach ($data as $k => $v) {
		            echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
		        }
		    }
		    echo "</form>";
		    "</body>";
		    "</html>";
		    exit;
}

function exit_error($string){
	$data['error'] = $string;
	redirectpost('index.php', $data);
}

function create_file($hostname, $username, $password, $database){
	chmod('../application/config', 0777);
	if(($file = fopen('../application/config/database.php', 'w')) == FALSE){
		$data['error'] == "Could not create the config file";
		$data['configfile'] == 1;
		redirectpost('index.php', $data);
	}
	$content = '<?php ';
	$content .= '$active_group = \'default\';';
	$content .= '$active_record = TRUE;';
	$content .= '$db[\'default\'][\'hostname\'] = \''. $hostname .'\';';
	$content .= '$db[\'default\'][\'username\'] = \'' . $username .'\';';
	$content .= '$db[\'default\'][\'password\'] = \'' . $password .'\';';
	$content .= '$db[\'default\'][\'database\'] = \'' . $database .'\';';
	$content .= '$db[\'default\'][\'dbdriver\'] = \'mysql\';';
	$content .= '$db[\'default\'][\'dbprefix\'] = \'\';';
	$content .= '$db[\'default\'][\'pconnect\'] = TRUE;';
	$content .= '$db[\'default\'][\'db_debug\'] = TRUE;';
	$content .= '$db[\'default\'][\'cache_on\'] = FALSE;';
	$content .= '$db[\'default\'][\'cachedir\'] = \'\';';
	$content .= '$db[\'default\'][\'char_set\'] = \'utf8\';';
	$content .= '$db[\'default\'][\'dbcollat\'] = \'utf8_general_ci\';';
	$content .= '$db[\'default\'][\'swap_pre\'] = \'\';';
	$content .= '$db[\'default\'][\'autoinit\'] = TRUE;';
	$content .= '$db[\'default\'][\'stricton\'] = FALSE;';

	if(fwrite($file, $content) == FALSE){
		$data['error'] == "Could not create the config file";
		$data['configfile'] == 1;
		redirectpost('index.php', $data);
	}
	fclose($file);
}

if($_POST['adminpass'] != $_POST['adminrpass'])
	exit_error('Admin passwords do not match');

$server = new mysqli($_POST['hostname'], $_POST['username'], $_POST['password']);

if ($server->connect_error) exit_error('Connection Error (' . $server->connect_errno . ') ' . $server->connect_error);

if($_POST['database'] != ""){
	if(!file_exists('../application/config/database.php'))
		create_file($_POST['hostname'], $_POST['username'], $_POST['password'], $_POST['database']);
	include '../application/config/database.php';
	if(!($_POST['hostname'] == $db['default']['hostname'] && $_POST['username'] == $db['default']['username']
		&& $_POST['password'] == $db['default']['password'] && $_POST['database'] == $db['default']['database'])) exit_error('Config file does not match with the given information');
	if ($server->select_db($_POST['database']) === FALSE) exit_error("Couldn't connect to database");
	$query = file_get_contents('database_tables.sql');
	if($server->multi_query($query) === FALSE) exit_error("Couldn't create the tables");
}else{
	if(!file_exists('../application/config/database.php'))
		create_file($_POST['hostname'], $_POST['username'], $_POST['password'], 'phpback');
	if(!$server->query("CREATE DATABASE IF NOT EXISTS phpback")){
		exit_error("Could not create database");
	}
	if ($server->select_db('phpback') === FALSE) exit_error("Couldn't connect to database");
	$sql = file_get_contents('database_tables.sql');
	if($server->multi_query($sql) === FALSE) exit_error("Couldn't create the tables");
}
do{
	if($r = $server->store_result()) $r->free();
}while($server->next_result());

unlink('index.php');
unlink('install1.php');
unlink('database_tables.sql');
$result = $server->query("SELECT id FROM settings WHERE name='title'");
if($result->num_rows == 1){
	unlink('index2.php');
	unlink('install2.php');
	header('Location: ../admin');
	exit;
}
$server->query("INSERT INTO users(id,name,email,pass,votes,isadmin,banned) VALUES('','". $_POST['adminname'] ."','". $_POST['adminemail'] ."','". crypt($_POST['adminpass']) ."', 20, 3,0)");
header('Location: index2.php');
exit;
