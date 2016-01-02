<?php
define( 'APPLICATION_LOADED', true );

/*********************************************************************
PHPBack
Ivan Diaz <ivan@phpback.org>
Copyright (c) 2014 PHPBack
http://www.phpback.org
Released under the GNU General Public License WITHOUT ANY WARRANTY.
See LICENSE.TXT for details.
**********************************************************************/
define('BASEPATH', '');

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
		    exit(1);
}

function exit_error($string){
	$data['error'] = $string;
	redirectpost('index.php', $data);
}

function create_file($hostname, $username, $password, $database){
	@chmod('../application/config', 0777);
	if(($file = fopen('../application/config/database.php', 'w+')) == FALSE){
        exit_error('ERROR #1: Config file could not be created');
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

	if(fwrite($file, $content) == FALSE) {
        fclose($file);
        exit_error('ERROR #1: Config file could not be created');
    }

	fclose($file);
}

function hashPassword($input, $rounds = 7) {
    $salt = "";
    $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));

    for($i=0; $i < 22; $i++) {
        $salt .= $salt_chars[array_rand($salt_chars)];
    }

    return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
}

/* if started from commandline, wrap parameters to $_POST*/
if (!isset($_SERVER["HTTP_HOST"])) 
    parse_str($argv[1], $_POST);

if($_POST['adminpass'] != $_POST['adminrpass'])
	exit_error('Admin passwords do not match');

$server = new mysqli($_POST['hostname'], $_POST['username'], $_POST['password']);

if ($server->connect_error) exit_error('ERROR #2: Server connection error (' . $server->connect_errno . ') ' . $server->connect_error);

if($_POST['database'] != ""){
	if(!file_exists('../application/config/database.php'))
		create_file($_POST['hostname'], $_POST['username'], $_POST['password'], $_POST['database']);
	include '../application/config/database.php';
	if(!($_POST['hostname'] == $db['default']['hostname'] && $_POST['username'] == $db['default']['username']
		&& $_POST['password'] == $db['default']['password'] && $_POST['database'] == $db['default']['database'])) exit_error('Config file does not match with the given information');
	if ($server->select_db($_POST['database']) === FALSE) exit_error("ERROR #3: Couldn't connect to database");
	$query = file_get_contents('database_tables.sql');
	if($server->multi_query($query) === FALSE) exit_error("ERROR #4: Couldn't create the tables");
}else{
	if(!file_exists('../application/config/database.php'))
		create_file($_POST['hostname'], $_POST['username'], $_POST['password'], 'phpback');

    if ($server->select_db('phpback') === TRUE) exit_error("ERROR #5: You already have a phpback database, please create another manually");
    if(!$server->query("CREATE DATABASE IF NOT EXISTS phpback")){
		exit_error("ERROR #6: Could not create database");
	}
	if ($server->select_db('phpback') === FALSE) exit_error("ERROR #5: Generated database connection error");
	$sql = file_get_contents('database_tables.sql');
	if($server->multi_query($sql) === FALSE) exit_error("ERROR #4: Couldn't create the tables");
}
do{
	if($r = $server->store_result()) $r->free();
}while($server->more_results() && $server->next_result());

$result = $server->query("SELECT id FROM settings WHERE name='title'");

if ($result->num_rows == 1) {
    if (!@chmod('../install', 0777)) {
        echo "PLEASE DELETE install/ FOLDER MANUALLY. THEN GO TO yourwebsite.com/feedback/admin/ TO LOG IN.";
        exit;
    }

    unlink('index.php');
    unlink('install1.php');
    unlink('database_tables.sql');
    unlink('index2.php');
    unlink('install2.php');
    header('Location: ../admin');
    exit;

} else {
    $server->query("INSERT INTO users(id,name,email,pass,votes,isadmin,banned) VALUES('','". $_POST['adminname'] ."','". $_POST['adminemail'] ."','". hashPassword($_POST['adminpass']) ."', 20, 3,0)");

    if (!@chmod('../install', 0777)) {
        echo "PLEASE DELETE install/index.php, install/install1.php AND install/database_tables.sql FILES MANUALLY.<br />
            THEN GO TO yourwebsite.com/feedback/install/index2.php TO CONTINUE THE INSTALLATION.";
        exit;
    }

    unlink('index.php');
    unlink('install1.php');
    unlink('database_tables.sql');
    header('Location: index2.php');
}
