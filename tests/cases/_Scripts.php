<?php

class Scripts {
    static $instance;

    public static function setInstance($element) {
        self::$instance = $element;
    }

    public static function LoginAdmin($email = 'admin@phpback.org', $password = 'admin') {
        $test = self::$instance;

        $test->url('/admin');

        $test->fillFields(array(
            'email' => $email,
            'password' => $password
        ));
        $test->byCssSelector('input[type=submit]')->click();
    }

    public static function CreateUser($email = 'newemail@phpback.org', $name = 'Steve Jobs', $password = 'Jobs123'){
      $test = self::$instance;

      $test->url('/home/register');
      $test->fillFields(array(
           'email' => $email ,
           'name'  => $name,
           'password' => $password,
           'password2' => $password
      ));
      $test->byName('registration-form')->submit();
    }

    public static function LoginUser($email = 'newemail@phpback.org', $password = 'Jobs123') {
        $test = self::$instance;

        $test->url('/home/login');

        $test->fillFields(array(
            'email' => $email,
            'password' => $password
        ));
        $test->byName('login-form')->submit();
    }
    public static function CreateCategory($name = 'Amsterdam') {
        $test = self::$instance;
        self::LoginAdmin();
        $test->byLinkText('System Settings')->click();
        $test->byLinkText('Categories')->click();

        $test->fillFields(array(
            'name' => $name,
            'description' => 'Value Category Description'
        ));
        $test->byName('add-category')->click();
    }
}
