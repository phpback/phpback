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
        Scripts::CreateCategory();
        $category=RedBean::load('categories',1);
        $this->assertEquals($category->name, 'Amsterdam');
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
        $category = RedBean::load('categories',1);
        $this->assertEquals('New Name', $category->name);

    }

    public function testCategoryDeletion() {
        Scripts::CreateCategory('Berlin');
        $this->byLinkText('System Settings')->click();
        $this->byLinkText('Categories')->click();
        $this->byName('delete-ideas')->click();
        $this->byName('delete-category')->click();
        $numberOfCategories=RedBean::count('categories');
        $this->assertEquals($numberOfCategories,1);
    }

    public function testGeneralSettings() {
        Scripts::LoginAdmin();
        $this->byLinkText('System Settings')->click();
        $this->fillFields(array(
                'setting-1' => 'new title',
                'setting-2' => 'new welcome title',
                'setting-3' => 'new welcome description',
                //'setting-4' => 'newrecaptchapublic',
                //'setting-5' => 'newrecaptchaprivate',
                //'setting-6' => 'spanish',
                'setting-7' => '50',
                //'setting-8' => 'admin@phpback.org',
                'setting-9' => '50',
                //'setting-10' => 'newsmtp-host',
                'setting-11' => '50',
                'setting-12' => 'newsmtp-user',
                'setting-13' => 'newsmtp-pass'
        ));
        $this->byName('submit-changes')->click();
/*
        $recaptchapublic = RedBean::load('settings',1);
        $recaptchaprivate = RedBean::load('settings',2);
        $maxvotes = RedBean::load('settings',3);
        $recaptchapublic = RedBean::load('settings',4);
        $recaptchaprivate = RedBean::load('settings',5);
        $max_results = RedBean::load('settings',6);
        $language = RedBean::load('settings',7);
        $smtphost = RedBean::load('settings',8);
        $smtpport = RedBean::load('settings',9);
        $smtpuser = RedBean::load('settings',10);
        $smtppass = RedBean::load('settings',11);
        $smtppass = RedBean::load('settings',12);
        $smtppass = RedBean::load('settings',13);

        //$this->assertEquals($recaptchapublic->value,'newrecaptchapublic');
        //$this->assertEquals($recaptchaprivate->value,'newrecaptchaprivate');
        $this->assertEquals($maxvotes->value,'50');
        $this->assertEquals($mainmail->value,'newmail@phpback.org');
        $this->assertEquals($title->value,'new tittle');
        $this->assertEquals($max_results->value,'50');
        //$this->assertEquals($language->value,'spanish');
        $this->assertEquals($smtphost->value,'50');
        $this->assertEquals($smtpport->value,'50');
        $this->assertEquals($smtpuser->value,'newsmtp-user');
        $this->assertEquals($smtppass->value,'newsmtp-pass');
*/
    }
    public function testCreateAdmin(){
            Scripts::CreateUser();
            Scripts::LoginAdmin();
            $this->byLinkText('System Settings')->click();
            $this->byLinkText('Create Admin')->click();
            $this->fillFields(array(
              'id' => '2',
            ));
            $this->select($this->byName('level'))->selectOptionByValue('2');
            $this->byName('submit-create-admin')->click();
            $userCreated = RedBean::load('users',2);
            $this->assertEquals($userCreated->isadmin,'2');
    }
    public function testBanUser() {
        Scripts::CreateUser('turing@phpback.org','Alan turing','turing123');
        Scripts::LoginAdmin();
        $this->byLinkText('Users Management')->click();
        $this->byLinkText('Ban User')->click();
        $this->byName('id')->click();
        $this->fillFields(array(
            'id' => '3',
            'days' => '10'
        ));
        $this->byName('banuser')->click();
        date_default_timezone_set('America/Los_Angeles');
        $userbanned = RedBean::load('users',3);
        $this->assertEquals($userbanned->banned,date('Ymd', strtotime('+10 days')));
    }
    public function testDisableBanUser() {
        Scripts::LoginAdmin();
        $this->byLinkText('Users Management')->click();
        $this->byLinkText('Banned List')->click();
        $this->byLinkText('Disable ban')->click();
        $passdisbann= RedBean::load('users',3);
        $this->assertEquals($passdisbann->banned,'0');
    }
}
