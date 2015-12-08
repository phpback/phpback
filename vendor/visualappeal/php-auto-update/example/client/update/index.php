<?php

require(__DIR__ . '/../../../vendor/autoload.php');

use \VisualAppeal\AutoUpdate;

$update = new AutoUpdate(__DIR__ . '/temp', __DIR__ . '/../', 60);
$update->setCurrentVersion('0.1.0');
$update->setUpdateUrl('http://php-auto-update.app/server'); //Replace with your server update directory

// Optional:
$update->addLogHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/update.log'));
$update->setCache(new Desarrolla2\Cache\Adapter\File(__DIR__ . '/cache'), 3600);

//Check for a new update
if ($update->checkUpdate() === false)
	die('Could not check for updates! See log file for details.');

if ($update->newVersionAvailable()) {
	//Install new update
	echo 'New Version: ' . $update->getLatestVersion() . '<br>';
	echo 'Installing Updates: <br>';
	echo '<pre>';
	var_dump(array_map(function($version) {
		return (string) $version;
	}, $update->getVersionsToUpdate()));
	echo '</pre>';

	$result = $update->update();
	if ($result === true) {
		echo 'Update successful<br>';
	} else {
		echo 'Update failed: ' . $result . '!<br>';

		if ($result = AutoUpdate::ERROR_SIMULATE) {
			echo '<pre>';
			var_dump($update->getSimulationResults());
			echo '</pre>';
		}
	}
} else {
	echo 'Current Version is up to date<br>';
}

echo 'Log:<br>';
echo nl2br(file_get_contents(__DIR__ . '/update.log'));
