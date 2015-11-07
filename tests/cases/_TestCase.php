<?php
require_once 'tests/vendor/autoload.php';

class TestCase extends PHPUnit_Extensions_Selenium2TestCase {
    public function setUp() {
        $this->setHost('localhost');
        $this->setPort(4444);
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost:8080/');
    }
}
