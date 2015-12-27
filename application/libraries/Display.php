<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Display {

    public function getParsedString($string) {
        return str_replace(' ', '-', $string);
    }
}