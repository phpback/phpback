<?php
require_once '_TestCase.php';

use RedBeanPHP\Facade as RedBean;

class UserTest extends TestCase {

    public function testCreateUser() {
        Scripts::CreateUser('newemail2@phpback.org','Bill Gates','Gates123');
        $newuser= RedBean::load('users',4);
        $this->assertEquals($newuser->id,'4');
        $this->assertEquals($newuser->name,'Bill Gates');
        $this->assertEquals($newuser->email,'newemail2@phpback.org');
    }

    public function testLoginUser() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->assertEquals($this->url(),'http://localhost:8080/home/');
        $this->assertContains('Logged as Bill Gates',$this->byTag('body')->text());

        //Test log out user
        $this->byLinkText('Log out')->click();
        $this->assertContains('Log in',$this->byTag('body')->text());
        $this->assertNotContains('Logged as Bill Gates',$this->byTag('body')->text());
    }

    public function testCreateIdea() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->byLinkText('Post a new idea')->click();
        $this->fillFields(array(
          'description' => 'The theory of relativity, or simply relativity in physics, usually encompasses two theories by Albert Einstein: special relativity and general relativity',
          'title' => 'Relativity Theory'
        ));
        $this->select($this->byName('category'))->selectOptionByValue('2');
        $this->byName('post-idea-form')->submit();
        $newidea = RedBean::load('ideas',1);
        $this->assertEquals($newidea->title,'Relativity Theory');
        $this->assertEquals($newidea->authorid,'4');
        $this->assertEquals($newidea->votes,'0');
        $this->assertEquals($newidea->content,'The theory of relativity, or simply relativity in physics, usually encompasses two theories by Albert Einstein: special relativity and general relativity');
        $this->assertEquals($newidea->comments,'0');
        $this->assertEquals($newidea->status,'new');
        $this->assertEquals($newidea->categoryid,'2');

    }


}
