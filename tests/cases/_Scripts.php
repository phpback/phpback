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
}
