<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getDisplayHelpers')) {
    function getDisplayHelpers() {
        return array (
            'getParsedName' => function ($string) {
                return str_replace(' ', '-', $string);
            }
        );
    }
}
