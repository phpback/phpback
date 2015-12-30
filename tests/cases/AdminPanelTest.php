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
            'name' => 'Value Name',
            'description' => 'Value Category Description'
        ));
        $this->byName('add-category')->click();
        $category=RedBean::load('categories',1);
        $this->assertEquals($category->name, 'Value Name');
        $this->assertEquals($category->description, 'Value Category Description');
    }
    public function testCategoryChangeName() {
        Scripts::LoginAdmin();
        $this->byLinkText('System Settings')->click();
        $this->byLinkText('Categories')->click();
        $this->fillFields(array(
              'category-1' => 'New Name'
        ));
        $this->byName('update-names')->click();
        $category=RedBean::load('categories',1);
        $this->assertEquals($category->name,'Value NameNew Name');

    }
    public function testCategoryDeletion() {
        Scripts::LoginAdmin();
        $this->byLinkText('System Settings')->click();
        $this->byLinkText('Categories')->click();
        $this->byName('delete-ideas')->click();
        $this->byName('delete-category')->click();
        $numberOfCategories=RedBean::count('categories');
        $this->assertEquals($numberOfCategories,0);
    }
}
