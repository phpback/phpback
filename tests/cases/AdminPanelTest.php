<?php
require_once '_TestCase.php';
require_once '_Scripts.php';

use RedBeanPHP\Facade as RedBean;

class AdminPanelTest extends TestCase {

    public function testLoginAdmin() {
        Scripts::LoginAdmin('admin@phpback.org', 'invalid_password');
        $this->assertContains('Email or password are incorrect', $this->source());

        Scripts::LoginAdmin();
        $this->assertEquals('http://localhost:8080/admin/dashboard', $this->url());

        //Log out
        $this->byLinkText('Log out')->click();
        $this->assertEquals('http://localhost:8080/home/', $this->url());
        $this->url('admin/dashboard');
        $this->assertEquals('http://localhost:8080/admin/', $this->url());
    }

    public function testCategoryCreation() {
        Scripts::LoginAdmin();
        $this->byLinkText('System Settings')->click();
        $this->byLinkText('Categories')->click();

        $this->fillFields(array(
            'name' => 'Redcat',
            'description' => 'Redcat is a category'
        ));
        //TODO: NEED to refactor id's
        //$this->byLinkText('Add Category')->click();
    }
}
