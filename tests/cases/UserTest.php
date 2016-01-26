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
          'description' => 'The theory of relativity is strange',
          'title' => 'Relativity Theory'
        ));
        $this->select($this->byName('category'))->selectOptionByValue('2');
        $this->byName('post-idea-form')->submit();
        $newidea = RedBean::load('ideas',1);
        $this->assertEquals($newidea->title,'Relativity Theory');
        $this->assertEquals($newidea->authorid,'4');
        $this->assertEquals($newidea->votes,'0');
        $this->assertEquals($newidea->content,'The theory of relativity is strange');
        $this->assertEquals($newidea->comments,'0');
        $this->assertEquals($newidea->status,'new');
        $this->assertEquals($newidea->categoryid,'2');

    }

    public function testCreateComentary() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('home/idea/1/Relativity-Theory');
        $this->fillFields(array(
                'content' => 'the theory of relativity is fake.'
        ));
        $this->byName('commentbutton')->click();
        $newcomment = RedBean::load('comments',1);
        $this->assertEquals($newcomment->content,'the theory of relativity is fake.');
        $this->assertEquals($newcomment->ideaid,'1');
        $this->assertEquals($newcomment->userid ,'4');
    }
    public function testFlagComment() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('home/idea/1/Relativity-Theory');
        $this->byLinkText('flag comment')->click();
        $newflag = RedBean::load('flags',1);
        $this->assertEquals($newflag->toflagid,'1');
        $this->assertEquals($newflag->userid,'4');
    }
    public function testVoteIdea() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('home/idea/1/Relativity-Theory');
        $this->byName('Vote')->click();
        $this->byLinkText('2 Votes')->click();
        $newvote = RedBean::load('votes',1);
        $voteidea = RedBean::load('ideas',1);
        $user = RedBean::load('users',4);
        $this->assertEquals($newvote->number,'2');
        $this->assertEquals($newvote->userid,'4');
        $this->assertEquals($newvote->ideaid,'1');
        $this->assertEquals($user->votes,'48');//error
        $this->assertEquals($voteidea->votes,'2');
    }
    public function testDisableVoteIdea() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('home/profile/4');
        $this->byLinkText('Delete votes')->click();
        $voteidea = RedBean::load('ideas',1);
        $this->assertEquals($voteidea->votes,'0');
    }
    public function testChangePass() {
        Scripts::LoginUser('newemail2@phpback.org','Gates123');
        $this->url('/home/profile/4');
        $this->byLinkText('Change Password')->click();
        $this->fillFields(array(
            'old' => 'Gates123',
            'new' => 'Microsoft123',
            'rnew' => 'Microsoft123'
        ));
        $this->byLinkText('Change Password')->click();
        $this->byLinkText('Log out')->click();
        Scripts::LoginUser('newemail2@phpback.org','Microsoft123');
        $this->assertEquals($this->url(),'http://localhost:8080/home/');
        $this->assertContains('Logged as Bill Gates',$this->byTag('body')->text());
    }


}
