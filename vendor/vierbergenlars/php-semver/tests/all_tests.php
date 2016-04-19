<?php
namespace vierbergenlars\SemVer\Tests;
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/simpletest/simpletest/autorun.php';
class RemoteCPVersioningTests extends \TestSuite
{
    public function __construct()
    {
        parent::__construct('RemoteCP Versioning System Tests');
        $this->addFile(__DIR__.'/base_test.php');
        $this->addFile(__DIR__.'/semver_test.php');
        $this->addFile(__DIR__.'/regression_test.php');
    }
}
