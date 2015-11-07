<?php
require_once '_TestCase.php';

class InstallTest extends TestCase {
    public function testHasLoginForm() {
        $this->url('index.php');

        $username = $this->byName('hostname');

        $this->assertEquals('', $username->value());
    }
}
