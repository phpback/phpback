<?php

require(__DIR__ . '/../vendor/autoload.php');

use \VisualAppeal\AutoUpdate;

class AutoUpdateTest extends PHPUnit_Framework_TestCase
{
	/**
	 * AutoUpdate instance.
	 *
	 * @var \VisualAppeal\AutoUpdate
	 */
	private $_update = null;

	protected function setUp()
	{
		$this->_update = new AutoUpdate(__DIR__ . DIRECTORY_SEPARATOR . 'temp', __DIR__ . DIRECTORY_SEPARATOR . 'install');
		$this->_update->setCurrentVersion('0.1.0');
		$this->_update->setUpdateUrl(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures');
	}

	protected function tearDown()
	{
		unset($this->_update);
		$this->_update = null;
	}

	/**
	 * Test creation of class instance.
	 */
	public function testInit()
	{
		$this->assertInstanceOf('\VisualAppeal\AutoUpdate', $this->_update);
	}

	public function testErrorUpdateCheck()
	{
		$this->_update->setUpdateFile('404.json');
		$response = $this->_update->checkUpdate();

		$this->assertEquals(false, $response);
		$this->assertFalse($this->_update->newVersionAvailable());
		$this->assertEquals(0, count($this->_update->getVersionsToUpdate()));
	}

	/**
	 * Test if new update is available with a json file.
	 */
	public function testJsonNewVersion()
	{
		$this->_update->setUpdateFile('updateAvailable.json');
		$response = $this->_update->checkUpdate();

		$this->assertTrue($response);
		$this->assertTrue($this->_update->newVersionAvailable());
		$this->assertEquals('0.2.1', $this->_update->getLatestVersion());

		$newVersions = $this->_update->getVersionsToUpdate();
		$this->assertEquals(2, count($newVersions));
		$this->assertEquals('0.2.0', $newVersions[0]);
		$this->assertEquals('0.2.1', $newVersions[1]);
	}

	/**
	 * Test if NO new update is available with a json file.
	 */
	public function testJsonNoNewVersion()
	{
		$this->_update->setUpdateFile('noUpdateAvailable.json');
		$response = $this->_update->checkUpdate();

		$this->assertEquals(AutoUpdate::NO_UPDATE_AVAILABLE, $response);
		$this->assertFalse($this->_update->newVersionAvailable());
		$this->assertEquals(0, count($this->_update->getVersionsToUpdate()));
	}

	/**
	 * Test if new update is available with a ini file.
	 */
	public function testIniNewVersion()
	{
		$this->_update->setUpdateFile('updateAvailable.ini');
		$response = $this->_update->checkUpdate();

		$this->assertTrue($response);
		$this->assertTrue($this->_update->newVersionAvailable());
		$this->assertEquals('0.2.1', $this->_update->getLatestVersion());

		$newVersions = $this->_update->getVersionsToUpdate();
		$this->assertEquals(2, count($newVersions));
		$this->assertEquals('0.2.0', $newVersions[0]);
		$this->assertEquals('0.2.1', $newVersions[1]);
	}

	/**
	 * Test if NO new update is available with a ini file.
	 */
	public function testIniNoNewVersion()
	{
		$this->_update->setUpdateFile('noUpdateAvailable.ini');
		$response = $this->_update->checkUpdate();

		$this->assertEquals(AutoUpdate::NO_UPDATE_AVAILABLE, $response);
		$this->assertFalse($this->_update->newVersionAvailable());
		$this->assertEquals(0, count($this->_update->getVersionsToUpdate()));
	}

	/**
	 * Ensure that a new dev version is available.
	 */
	public function testBranchDev()
	{
		$this->_update->setUpdateFile('updateAvailable.json');
		$this->_update->setBranch('dev');
		$response = $this->_update->checkUpdate();

		$this->assertTrue($response);
	}

	/**
	 * Ensure that no new master version is available
	 */
	public function testBranchMaster()
	{
		$this->_update->setUpdateFile('noUpdateAvailable.json');
		$this->_update->setBranch('master');
		$response = $this->_update->checkUpdate();

		$this->assertEquals(AutoUpdate::NO_UPDATE_AVAILABLE, $response);
	}

	/**
	 * Test the trailing slash method.
	 */
	public function testTrailingSlashes()
	{
		$dir = DIRECTORY_SEPARATOR . 'test';
		$this->assertEquals(DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR, $this->_update->addTrailingSlash($dir));

		$dir = DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR;
		$this->assertEquals(DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR, $this->_update->addTrailingSlash($dir));
	}
}
