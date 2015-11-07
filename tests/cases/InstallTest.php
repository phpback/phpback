<?php
require_once '_TestCase.php';

use RedBeanPHP\Facade as RedBean;

class InstallTest extends TestCase {
    public function testDatabaseInstallation() {
        $this->url('index.php');

        //All Fields initially with empty
        $fields = $this->getFields(array(
            'hostname',
            'username',
            'password',
            'database',
            'adminname',
            'adminemail',
            'adminpass',
            'adminrpass'
        ));

        foreach ($fields as $field)  {
            $this->assertEquals('', $field->value());
        }

        //Populate all fields
        $fields['hostname']->value('localhost');
        $fields['username']->value('root');
        $fields['password']->value('');
        $fields['database']->value('phpback_test');
        $fields['adminemail']->value('admin@phpback.org');
        $fields['adminname']->value('admin');
        $fields['adminpass']->value('admin');
        $fields['adminrpass']->value('admin');

        //Submit form
        $this->byName('install-form')->submit();

        //Should delete first intallation files
        $this->assertFileNotExists('install/index.php');
        $this->assertFileNotExists('install/install1.php');
        $this->assertFileNotExists('install/database_tables.sql');

        //Should create configuration file
        $this->assertFileExists('application/config/database.php');
        include 'application/config/database.php';
        $this->assertEquals($db['default']['username'], 'root');
        $this->assertEquals($db['default']['password'], '');
        $this->assertEquals($db['default']['database'], 'phpback_test');
        $this->assertEquals($db['default']['username'], 'root');
        $this->assertEquals($db['default']['dbdriver'], 'mysql');

        //Should have updated database with new tables
        $this->assertEquals(RedBean::inspect(), array(
            '_sessions',
            'categories',
            'comments',
            'flags',
            'ideas',
            'logs',
            'settings',
            'users',
            'votes'
        ));

        //Should have created the admin user
        $adminUser = RedBean::load('users', 1);
        $this->assertEquals($adminUser->name, 'admin');
        $this->assertEquals($adminUser->email, 'admin@phpback.org');
        $this->assertEquals($adminUser->isadmin, '3');
        $this->assertEquals($adminUser->votes, '20');
    }

    /**
     * @depends testDatabaseInstallation
     */
     public function testConfigurationInstallation($value='') {
         $this->url('install/index2.php');

         $fields = $this->getFields(array(
             'title',
             'mainmail',
         ));

         //Populate all fields
         $fields['title']->value('TestBack');
         $fields['mainmail']->value('admin@phpback.org');

         //Submit form
         $this->byName('install2-form')->submit();

         //Should delete first intallation files
         $this->assertFileNotExists('install/index2.php');
         $this->assertFileNotExists('install/install2.php');

         //Should update settings
         $settings = $this->getSettings();
         $this->assertEquals($settings['title'], 'TestBack');
         $this->assertEquals($settings['mainmail'], 'admin@phpback.org');
         $this->assertEquals($settings['language'], 'english');
         $this->assertEquals($settings['max_results'], '10');
         $this->assertEquals($settings['maxvotes'], '20');
     }
}
