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
        $category=RedBean::load('categories',1);
        $this->assertEquals($category->name,'New Name');

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
                //  //'setting-1' => 'newrecaptchapublic',
                //'setting-2' => 'newrecaptchaprivate',
                'setting-3' => '50',
                'setting-4' => 'newmail@phpback.org',
                'setting-5' => 'new tittle',
                'setting-6' => '50',
                //  'setting-7' => 'spanish',
                'setting-8' => '50',
                'setting-9' => '50',
                'setting-10' => 'newsmtp-user',
                'setting-11' => 'newsmtp-pass'
        ));
        $this->byName('submit-changes')->click();

        $recaptchapublic = RedBean::load('settings',1);
        $recaptchaprivate = RedBean::load('settings',2);
        $maxvotes = RedBean::load('settings',3);
        $mainmail = RedBean::load('settings',4);
        $title = RedBean::load('settings',5);
        $max_results = RedBean::load('settings',6);
        $language = RedBean::load('settings',7);
        $smtphost = RedBean::load('settings',8);
        $smtpport = RedBean::load('settings',9);
        $smtpuser = RedBean::load('settings',10);
        $smtppass = RedBean::load('settings',11);

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
        $userbanned = RedBean::load('users',3);
        date_default_timezone_set('America/Los_Angeles');
        $this->assertEquals($userbanned->banned,date('Ymd', strtotime('+10 days')));
    }
    public function testDisableBanUser() {
        Scripts::LoginAdmin();
        $this->byLinkText('Users Management')->click();
        $this->byLinkText('Banned List ')->click();
        $this->byLinkText('Disable ban')->click();
        $passdisbann= RedBean::load('users',3);
        $this->assertEquals($passdisbann->banned,'0');
    }
}
