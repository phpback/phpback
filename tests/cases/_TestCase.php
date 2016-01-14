<?php
require_once 'tests/vendor/autoload.php';
require_once '_Scripts.php';
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

        Scripts::setInstance($this);
    }

    public function getFields($array) {
        $fields = array();

        foreach ($array as $value) {
            $fields[$value] = $this->byName($value);
        }

        return $fields;
    }

    public function fillFields($array) {
        foreach ($array as $key => $value) {
            $this->byName($key)->clear();
            $this->byName($key)->value($value);
        }
    }

    public function getSettings() {
        $settings = array();
        $result = RedBean::findAll('settings');

        foreach ($result as $setting) {
            $settings[$setting->name] = $setting->value;
        }

        return $settings;
    }
}
