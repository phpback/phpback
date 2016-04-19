With this library your users can automatically update their instance of your application to the newest version. I created it as a proof of concept and don't know if it is used somewhere. So please use this library with caution because it can potentially make your users software nonfunctional if something goes wrong.

## Installation

* Install the library via composer [visualappeal/php-auto-update](https://packagist.org/packages/visualappeal/php-auto-update)
* Create a update file/method in your application with your update routine (see `example/client/update/index.php`)
* Create a `update.json` or `update.ini` on your server (where the client should get the updates, see `example/server/update.json` or `example/server/update.ini`)

**Important: Please notice that PHP needs write permissions to update the files on the webserver**

## Example

### Client

#### update.php/some method

This file will install the update. For an example see `example/client/update/index.php`

#### Check for new versions

You can always check for new versions, e.g. in the footer. This can look like this:

```php
<?php

require(__DIR__ . '/../../../vendor/autoload.php');

use \VisualAppeal\AutoUpdate;

// Download the zip update files to `__DIR__ . '/temp'`
// Copy the contents of the zip file to the current directory `__DIR__`
// The update process should last 60 seconds
$update = new AutoUpdate(__DIR__ . '/temp', __DIR__, 60);
$update->setCurrentVersion('0.1.0'); // Current version of your application. This value should be from a database or another file which will be updated with the installation of a new version
$update->setUpdateUrl('http://php-auto-update.app/update/'); //Replace the url with your server update url

// The following two lines are optional
$update->addLogHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/update.log'));
$update->setCache(new Desarrolla2\Cache\Adapter\File(__DIR__ . '/cache'), 3600);

//Check for a new update
if ($update->checkUpdate() === false)
	die('Could not check for updates! See log file for details.');

// Check if new update is available
if ($update->newVersionAvailable()) {
	//Install new update
	echo 'New Version: ' . $update->getLatestVersion();
} else {
	// No new update
	echo 'Your application is up to date';
}
```

The library supports the `desarrolla2/cache` component and you should use it! Otherwise the client will download the update ini/json file on every request.

### Server

Your server needs at least one file which will be downloaded from the client to check for updates. This can be a json or an ini file. See `example/server/` for examples. The ini section key respectively the json key is the version. This library uses semantic versioning to compare the versions. See [semver.org](http://semver.org/) for details. The ini/json value is the absolute url to the update zip file. Since the library supports incremental updates, the zip file only need to contain the changes since the last version. The zip files do not need to be placed on the same server, they can be uploaded to S3 or another cloud storage, too.

## Documentation

For the documentation see the comments in `src/AutoUpdate.php` or the example in the `example` directory.
