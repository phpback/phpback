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
}
