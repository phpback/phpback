<?php namespace VisualAppeal;

use \vierbergenlars\SemVer\version;
use \vierbergenlars\SemVer\expression;
use \vierbergenlars\SemVer\SemVerException;

use \Desarrolla2\Cache\Cache;
use \Desarrolla2\Cache\Adapter\NotCache;

use \Monolog\Logger;
use \Monolog\Handler\NullHandler;

/**
 * Auto update class.
 */
class AutoUpdate
{
    /**
     * The latest version.
     *
     * @var vierbergenlars\SemVer\version
     */
    private $_latestVersion = null;

    /**
     * Updates not yet installed.
     *
     * @var array
     */
    private $_updates = null;

    /**
     * Cache for update requests.
     *
     * @var Desarrolla2\Cache\Cache
     */
    private $_cache = null;

    /**
     * Result of simulated install.
     *
     * @var array
     */
    private $_simulationResults = array();

    /**
     * Temporary download directory.
     *
     * @var string
     */
    private $_tempDir = '';

    /**
     * Install directory.
     *
     * @var string
     */
    private $_installDir = '';

    /**
     * Update branch.
     *
     * @var string
     */
    private $_branch = '';

    /**
     * Url to the update folder on the server.
     *
     * @var string
     */
    protected $_updateUrl = 'https://example.com/updates/';

    /**
     * Version filename on the server.
     *
     * @var string
     */
    protected $_updateFile = 'update.json';

    /**
     * Current version.
     *
     * @var vierbergenlars\SemVer\version
     */
    protected $_currentVersion = null;

    /**
     * Create new folders with this privileges.
     *
     * @var int
     */
    public $dirPermissions = 0755;

    /**
     * Update script filename.
     *
     * @var string
     */
    public $updateScriptName = '_upgrade.php';

    /**
     * Username authentication
     *
     * @var string
     */
    private $_username = '';

    /**
     * Password authentication
     *
     * @var string
     */
    private $_password = '';

    /**
     * No update available.
     */
    const NO_UPDATE_AVAILABLE = 0;

    /**
     * Zip file could not be opened.
     */
    const ERROR_INVALID_ZIP = 10;

    /**
     * Could not check for last version.
     */
    const ERROR_VERSION_CHECK = 20;

    /**
     * Temp directory does not exist or is not writable.
     */
    const ERROR_TEMP_DIR = 30;

    /**
     * Install directory does not exist or is not writable.
     */
    const ERROR_INSTALL_DIR = 35;

    /**
     * Could not download update.
     */
    const ERROR_DOWNLOAD_UPDATE = 40;

    /**
     * Could not delete zip update file.
     */
    const ERROR_DELETE_TEMP_UPDATE = 50;

    /**
     * Error while installing the update.
     */
    const ERROR_INSTALL = 60;

    /**
     * Error in simulated install.
     */
    const ERROR_SIMULATE = 70;

    /**
     * Create new instance
     *
     * @param string $tempDir
     * @param string $installDir
     * @param int    $maxExecutionTime
     */
    public function __construct($tempDir = null, $installDir = null, $maxExecutionTime = 60)
    {
        // Init logger
        $this->_log = new Logger('auto-update');
        $this->_log->pushHandler(new NullHandler());

        $this->setTempDir(($tempDir !== null) ? $tempDir : __DIR__ . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR);
        $this->setInstallDir(($installDir !== null) ? $installDir : __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

        $this->_latestVersion = new version('0.0.0');
        $this->_currentVersion = new version('0.0.0');

        // Init cache
        $this->_cache = new Cache(new NotCache());

        ini_set('max_execution_time', $maxExecutionTime);
    }

    /**
     * Set the temporary download directory.
     *
     * @param string $dir
     * @return $this|void
     */
    public function setTempDir($dir)
    {
        $dir = $this->addTrailingSlash($dir);

        if (!is_dir($dir)) {
            $this->log(sprintf('Creating new temporary directory "%s"', $dir));

            if (!mkdir($dir, 0755, true)) {
                $this->log(sprintf('Could not create temporary directory "%s"', $dir));

                return;
            }
        }

        $this->_tempDir = $dir;

        return $this;
    }

    /**
     * Set the install directory.
     *
     * @param string $dir
     * @return $this|void
     */
    public function setInstallDir($dir)
    {
        $dir = $this->addTrailingSlash($dir);

        if (!is_dir($dir)) {
            $this->log(sprintf('Creating new install directory "%s"', $dir));

            if (!mkdir($dir, 0755, true)) {
                $this->log(sprintf('Could not create install directory "%s"', $dir));

                return;
            }
        }

        $this->_installDir = $dir;

        return $this;
    }

    /**
     * Set the update filename.
     *
     * @param string $updateFile
     * @return $this
     */
    public function setUpdateFile($updateFile)
    {
        $this->_updateFile = $updateFile;

        return $this;
    }

    /**
     * Set the update filename.
     *
     * @param string $updateUrl
     * @return $this
     */
    public function setUpdateUrl($updateUrl)
    {
        $this->_updateUrl = $updateUrl;

        return $this;
    }

    /**
     * Set the update branch.
     *
     * @param string branch
     * @return $this
     */
    public function setBranch($branch)
    {
        $this->_branch = $branch;

        return $this;
    }

    /**
     * Set the cache component.
     *
     * @param Desarrolla2\Cache\Adapter\AdapterInterface $adapter See https://github.com/desarrolla2/Cache
     * @param int $ttl Time to live in seconds
     * @return $this
     */
    public function setCache($adapter, $ttl = 3600)
    {
        $adapter->setOption('ttl', $ttl);
        $this->_cache = new Cache($adapter);

        return $this;
    }

    /**
     * Set the version of the current installed software.
     *
     * @param string $currentVersion
     *
     * @return bool
     */
    public function setCurrentVersion($currentVersion)
    {
        $version = new version($currentVersion);
        if ($version->valid() === null) {
            $this->log(sprintf('Invalid current version "%s"', $currentVersion));

            return false;
        }

        $this->_currentVersion = $version;

        return $this;
    }

    /**
     * Set authentication
     * @param $username
     * @param $password
     */
    public function setBasicAuth($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Set authentication in update method of users and password exist
     * @return null|resource
     */
    private function _useBasicAuth()
    {
        if ($this->_username && $this->_password) {
            return stream_context_create(array(
                'http' => array(
                    'header' => "Authorization: Basic " . base64_encode("$this->_username:$this->_password")
                )
            ));
        }

        return null;
    }

    /**
     * Add a new logging handler.
     *
     * @param \Monolog\Handler\HandlerInterface|Monolog\Handler\HandlerInterface $handler See https://github.com/Seldaek/monolog
     * @return $this
     */
    public function addLogHandler(\Monolog\Handler\HandlerInterface $handler)
    {
        $this->_log->pushHandler($handler);

        return $this;
    }

    /**
     * Get the name of the latest version.
     *
     * @return vierbergenlars\SemVer\version
     */
    public function getLatestVersion()
    {
        return $this->_latestVersion;
    }

    /**
     * Get an array of versions which will be installed.
     *
     * @return array
     */
    public function getVersionsToUpdate()
    {
        return array_map(function ($update) {
            return $update['version'];
        }, $this->_updates);
    }

    /**
     * Get the results of the last simulation.
     *
     * @return array
     */
    public function getSimulationResults()
    {
        return $this->_simulationResults;
    }

    /**
     * Remove directory recursively.
     *
     * @param string $dir
     *
     * @return void
     */
    private function _removeDir($dir)
    {
        $this->log(sprintf('Remove directory "%s"', $dir));

        if (!is_dir($dir)) {
            $this->_log->addWarning(sprintf('"%s" is not a directory!', $dir));

            return false;
        }

        $objects = array_diff(scandir($dir), array('.', '..'));
        foreach ($objects as $object) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $object))
                $this->_removeDir($dir . DIRECTORY_SEPARATOR . $object);
            else
                unlink($dir . DIRECTORY_SEPARATOR . $object);
        }

        return rmdir($dir);
    }

    /**
     * Check for a new version
     *
     * @return int|bool
     *         true: New version is available
     *         false: Error while checking for update
     *         int: Status code (i.e. AutoUpdate::NO_UPDATE_AVAILABLE)
     */
    public function checkUpdate()
    {
        $this->_log->addNotice('Checking for a new update...');

        // Reset previous updates
        $this->_latestVersion = new version('0.0.0');
        $this->_updates = [];

        $versions = $this->_cache->get('update-versions');

        // Check if cache is empty
        if ($versions === false) {
            // Create absolute url to update file
            $updateFile = $this->_updateUrl . DIRECTORY_SEPARATOR . $this->_updateFile;
            if (!empty($this->_branch))
                $updateFile .= '.' . $this->_branch;

            $this->log(sprintf('Get new updates from %s', $updateFile));

            // Read update file from update server
            $update = @file_get_contents($updateFile, $this->_useBasicAuth());
            if ($update === false) {
                $this->log(sprintf('Could not download update file "%s"!', $updateFile));

                return false;
            }

            // Parse update file
            $updateFileExtension = substr(strrchr($this->_updateFile, '.'), 1);
            switch ($updateFileExtension) {
                case 'ini':
                    $versions = @parse_ini_string($update, true);
                    if (!is_array($versions)) {
                        $this->log('Unable to parse ini update file!');

                        return false;
                    }

                    $versions = array_map(function ($block) {
                        return isset($block['url']) ? $block['url'] : false;
                    }, $versions);

                    break;
                case 'json':
                    $versions = (array)@json_decode($update);
                    if (!is_array($versions)) {
                        $this->log('Unable to parse json update file!');

                        return false;
                    }

                    break;
                default:
                    $this->log(sprintf('Unknown file extension "%s"', $updateFileExtension));

                    return false;
            }

            $this->_cache->set('update-versions', $versions);
        } else {
            $this->log('Got updates from cache');
        }

        // Check for latest version
        foreach ($versions as $versionRaw => $updateUrl) {
            $version = new version($versionRaw);
            if ($version->valid() === null) {
                $this->log(sprintf('Could not parse version "%s" from update server "%s"', $versionRaw, $updateFile));
                continue;
            }

            if (version::gt($version, $this->_currentVersion)) {
                if (version::gt($version, $this->_latestVersion))
                    $this->_latestVersion = $version;

                $this->_updates[] = [
                    'version' => $version,
                    'url'     => $updateUrl,
                ];
            }
        }

        // Sort versions to install
        usort($this->_updates, function ($a, $b) {
            return version::compare($a['version'], $b['version']);
        });

        if ($this->newVersionAvailable()) {
            $this->log(sprintf('New version "%s" available', $this->_latestVersion));

            return true;
        } else {
            $this->log('No new version available');

            return self::NO_UPDATE_AVAILABLE;
        }
    }

    /**
     * Check if a new version is available.
     *
     * @return bool
     */
    public function newVersionAvailable()
    {
        return version::gt($this->_latestVersion, $this->_currentVersion);
    }

    /**
     * Download the update
     *
     * @param string $updateUrl Url where to download from
     * @param string $updateFile Path where to save the download
     *
     * @return bool
     */
    protected function _downloadUpdate($updateUrl, $updateFile)
    {
        $this->log(sprintf('Downloading update "%s" to "%s"', $updateUrl, $updateFile));
        $update = @file_get_contents($updateUrl, $this->_useBasicAuth());

        if ($update === false) {
            $this->log(sprintf('Could not download update "%s"!', $updateUrl));

            return false;
        }

        $handle = fopen($updateFile, 'w');

        if (!$handle) {
            $this->log(sprintf('Could not open file handle to save update to "%s"!', $updateFile));

            return false;
        }

        if (!fwrite($handle, $update)) {
            $this->log(sprintf('Could not write update to file "%s"!', $updateFile));
            fclose($handle);

            return false;
        }

        fclose($handle);

        return true;
    }

    /**
     * Simulate update process.
     *
     * @param string $updateFile
     *
     * @return bool
     */
    protected function _simulateInstall($updateFile)
    {
        $this->_log->addNotice('[SIMULATE] Install new version');
        clearstatcache();

        // Check if zip file could be opened
        $zip = zip_open($updateFile);
        if (!is_resource($zip)) {
            $this->log(sprintf('Could not open zip file "%s", error: %d', $updateFile, $zip));

            return false;
        }

        $i = -1;
        $files = [];
        $simulateSuccess = true;

        while ($file = zip_read($zip)) {
            $i++;

            $filename = zip_entry_name($file);
            $foldername = $this->_installDir . dirname($filename);
            $absoluteFilename = $this->_installDir . $filename;

            $files[$i] = [
                'filename'          => $filename,
                'foldername'        => $foldername,
                'absolute_filename' => $absoluteFilename,
            ];

            $this->log(sprintf('[SIMULATE] Updating file "%s"', $filename));

            // Check if parent directory is writable
            if (!is_dir($foldername)) {
                $this->log(sprintf('[SIMULATE] Create directory "%s"', $foldername));
                $files[$i]['parent_folder_exists'] = false;

                $parent = dirname($foldername);
                if (!is_writable($parent)) {
                    $files[$i]['parent_folder_writable'] = false;

                    $simulateSuccess = false;
                    $this->_log->addWarning(sprintf('[SIMULATE] Directory "%s" has to be writeable!', $parent));
                } else {
                    $files[$i]['parent_folder_writable'] = true;
                }
            }

            // Skip if entry is a directory
            if (substr($filename, -1, 1) == DIRECTORY_SEPARATOR)
                continue;

            // Read file contents from archive
            $contents = zip_entry_read($file, zip_entry_filesize($file));
            if ($contents === false) {
                $files[$i]['extractable'] = false;

                $simulateSuccess = false;
                $this->_log->addWarning(sprintf('[SIMULATE] Coud not read contents of file "%s" from zip file!', $filename));
            }

            // Write to file
            if (file_exists($absoluteFilename)) {
                $files[$i]['file_exists'] = true;
                if (!is_writable($absoluteFilename)) {
                    $files[$i]['file_writable'] = false;

                    $simulateSuccess = false;
                    $this->_log->addWarning('[SIMULATE] Could not overwrite "%s"!', $absoluteFilename);
                }
            } else {
                $files[$i]['file_exists'] = false;

                if (is_dir($foldername)) {
                    if (!is_writable($foldername)) {
                        $files[$i]['file_writable'] = false;

                        $simulateSuccess = false;
                        $this->_log->addWarning(sprintf('[SIMULATE] The file "%s" could not be created!', $absoluteFilename));
                    } else {
                        $files[$i]['file_writable'] = true;
                    }
                } else {
                    $files[$i]['file_writable'] = true;

                    $this->log(sprintf('[SIMULATE] The file "%s" could be created', $absoluteFilename));
                }
            }

            if ($filename == $this->updateScriptName) {
                $this->log(sprintf('[SIMULATE] Update script "%s" found', $absoluteFilename));
                $files[$i]['update_script'] = true;
            } else {
                $files[$i]['update_script'] = false;
            }
        }

        $this->_simulationResults = $files;

        return $simulateSuccess;
    }

    /**
     * Install update.
     *
     * @param string $updateFile Path to the update file
     * @param bool   $simulateInstall Check for directory and file permissions before copying files
     *
     * @return bool
     */
    protected function _install($updateFile, $simulateInstall, $version)
    {
        $this->_log->addNotice(sprintf('Trying to install update "%s"', $updateFile));

        // Check if install should be simulated
        if ($simulateInstall && !$this->_simulateInstall($updateFile)) {
            $this->log('Simulation of update process failed!');

            return self::ERROR_SIMULATE;
        }

        clearstatcache();

        // Check if zip file could be opened
        $zip = zip_open($updateFile);
        if (!is_resource($zip)) {
            $this->log(sprintf('Could not open zip file "%s", error: %d', $updateFile, $zip));

            return false;
        }

        // Read every file from archive
        while ($file = zip_read($zip)) {
            $filename = zip_entry_name($file);
            $foldername = $this->_installDir . dirname($filename);
            $absoluteFilename = $this->_installDir . $filename;

            $this->log(sprintf('Updating file "%s"', $filename));

            if (!is_dir($foldername)) {
                if (!mkdir($foldername, $this->dirPermissions, true)) {
                    $this->log(sprintf('Directory "%s" has to be writeable!', $parent));

                    return false;
                }
            }

            // Skip if entry is a directory
            if (substr($filename, -1, 1) == DIRECTORY_SEPARATOR)
                continue;

            // Read file contents from archive
            $contents = zip_entry_read($file, zip_entry_filesize($file));

            if ($contents === false) {
                $this->log(sprintf('Coud not read zip entry "%s"', $file));
                continue;
            }

            // Write to file
            if (file_exists($absoluteFilename)) {
                if (!is_writable($absoluteFilename)) {
                    $this->log('Could not overwrite "%s"!', $absoluteFilename);

                    zip_close($zip);

                    return false;
                }
            } else {
                if (!touch($absoluteFilename)) {
                    $this->log(sprintf('[SIMULATE] The file "%s" could not be created!', $absoluteFilename));
                    zip_close($zip);

                    return false;
                }

                $this->log(sprintf('File "%s" created', $absoluteFilename));
            }

            $updateHandle = @fopen($absoluteFilename, 'w');

            if (!$updateHandle) {
                $this->log(sprintf('Could not open file "%s"!', $absoluteFilename));
                zip_close($zip);

                return false;
            }

            if (!fwrite($updateHandle, $contents)) {
                $this->log(sprintf('Could not write to file "%s"!', $absoluteFilename));
                zip_close($zip);

                return false;
            }

            fclose($updateHandle);

            //If file is a update script, include
            if ($filename == $this->updateScriptName) {
                $this->log(sprintf('Try to include update script "%s"', $absoluteFilename));
                require($absoluteFilename);

                $this->log(sprintf('Update script "%s" included!', $absoluteFilename));
                if (!unlink($absoluteFilename)) {
                    $this->_log->addWarning(sprintf('Could not delete update script "%s"!', $absoluteFilename));
                }
            }
        }

        zip_close($zip);

        // TODO
        $this->_log->addNotice(sprintf('Update "%s" successfully installed', $version));

        return true;
    }

	public function log($text) {
		$this->logList[] = $text;
	}

	public function printLogs () {
		foreach ($this->logList as $log) {
			echo $log . '<br />';
		}
	}

    /**
     * Update to the latest version
     *
     * @param bool $simulateInstall Check for directory and file permissions before copying files (Default: true)
     * @param bool $deleteDownload Delete download after update (Default: true)
     *
     * @return mixed integer|bool
     */
    public function update($simulateInstall = true, $deleteDownload = true)
    {
        $this->log('Trying to perform update');

        // Check for latest version
        if ($this->_latestVersion === null || count($this->_updates) === 0)
            $this->checkUpdate();

        if ($this->_latestVersion === null || count($this->_updates) === 0) {
            $this->log('Could not get latest version from server!');

            return self::ERROR_VERSION_CHECK;
        }

        // Check if current version is up to date
        if (!$this->newVersionAvailable()) {
            $this->_log->addWarning('No update available!');

            return self::NO_UPDATE_AVAILABLE;
        }

        foreach ($this->_updates as $update) {
            $this->log(sprintf('Update to version "%s"', $update['version']));

            // Check for temp directory
            if (empty($this->_tempDir) || !is_dir($this->_tempDir) || !is_writable($this->_tempDir)) {
                $this->log(sprintf('Temporary directory "%s" does not exist or is not writeable!', $this->_tempDir));

                return self::ERROR_TEMP_DIR;
            }

            // Check for install directory
            if (empty($this->_installDir) || !is_dir($this->_installDir) || !is_writable($this->_installDir)) {
                $this->log(sprintf('Install directory "%s" does not exist or is not writeable!', $this->_installDir));

                return self::ERROR_INSTALL_DIR;
            }

            $updateFile = $this->_tempDir . $update['version'] . '.zip';

            // Download update
            if (!is_file($updateFile)) {
                if (!$this->_downloadUpdate($update['url'], $updateFile)) {
                    $this->log(sprintf('Failed to download update from "%s" to "%s"!', $update['url'], $updateFile));

                    return self::ERROR_DOWNLOAD_UPDATE;
                }

                $this->log(sprintf('Latest update downloaded to "%s"', $updateFile));
            } else {
                $this->log(sprintf('Latest update already downloaded to "%s"', $updateFile));
            }

            // Install update
            $result = $this->_install($updateFile, $simulateInstall, $update['version']);
            if ($result === true) {
                if ($deleteDownload) {
                    $this->log(sprintf('Trying to delete update file "%s" after successfull update', $updateFile));
                    if (@unlink($updateFile)) {
                        $this->log(sprintf('Update file "%s" deleted after successfull update', $updateFile));
                    } else {
                        $this->log(sprintf('Could not delete update file "%s" after successfull update!', $updateFile));

                        return self::ERROR_DELETE_TEMP_UPDATE;
                    }
                }
            } else {
                if ($deleteDownload) {
                    $this->log(sprintf('Trying to delete update file "%s" after failed update', $updateFile));
                    if (@unlink($updateFile)) {
                        $this->log(sprintf('Update file "%s" deleted after failed update', $updateFile));
                    } else {
                        $this->log(sprintf('Could not delete update file "%s" after failed update!', $updateFile));
                    }
                }

                return $result;
            }
        }

        return true;
    }

    /**
     * Add slash at the end of the path.
     *
     * @param string $dir
     * @return string
     */
    public function addTrailingSlash($dir)
    {
        if (substr($dir, -1) != DIRECTORY_SEPARATOR)
            $dir = $dir . DIRECTORY_SEPARATOR;

        return $dir;
    }
}
