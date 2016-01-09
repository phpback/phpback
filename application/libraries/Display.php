<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Display {

    public function getParsedString($string) {
        $parsedString = str_replace([' ', ','], '-', $string);
        $parsedString = preg_replace('/[^a-zA-Z0-9-]/', '', $parsedString);

        return $parsedString;
    }
}