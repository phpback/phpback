<?php
require_once 'tests/vendor/autoload.php';

// REDBEAN CONFIGURATION
use RedBeanPHP\Facade as RedBean;
RedBean::setup('mysql:host=localhost;dbname=phpback_test', 'root', '');

class TestCase extends PHPUnit_Extensions_Selenium2TestCase {
    protected $mysqli;

    public function setUp() {
        $this->setHost('localhost');
        $this->setPort(4444);
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost:8080/');
        $this->mysqli = new mysqli('localhost', 'root', '', 'phpback_test');
    }

    protected function getFields($array) {
        $fields = array();

        foreach ($array as $value) {
            $fields[$value] = $this->byName($value);
        }

        return $fields;
    }

    protected function getSettings() {
        $settings = array();
        $result = RedBean::findAll('settings');

        foreach ($result as $setting) {
            $settings[$setting->name] = $setting->value;
        }

        return $settings;
    }
}
