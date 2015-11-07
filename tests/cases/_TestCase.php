<?php
require_once 'tests/vendor/autoload.php';

class TestCase extends PHPUnit_Extensions_Selenium2TestCase {
    protected $mysql;

    public function setUp() {
        $this->setHost('localhost');
        $this->setPort(4444);
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost:8080/');
        $this->connectDatabase();
    }

    protected function getFields($array) {
        $fields = array();

        foreach ($array as $value) {
            $fields[$value] = $this->byName($value);
        }

        return $fields;
    }

    private function connectDatabase() {

    }
}
