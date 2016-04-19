<?php
namespace vierbergenlars\SemVer\Tests;
use vierbergenlars\SemVer;
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/simpletest/simpletest/autorun.php';
class regressionTest extends \UnitTestCase
{
    public function testBug23()
    {
        $this->assertTrue(SemVer\version::lt('3.0.0', '4.0.0-beta.1'), '3.0.0 < 4.0.0-beta.1 (Bug #23)');
    }

    public function testBug24()
    {
        $this->assertFalse(SemVer\version::gt('4.0.0-beta.9', '4.0.0-beta.10'), '4.0.0-beta.9 < 4.0.0-beta.10 (Bug #24)');
    }

}
