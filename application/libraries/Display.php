<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Display {

    /**
     * @param $string
     * @return mixed|string
     * @deprecated use slugify static instead keep for retrocompatibility
     */
    public function getParsedString($string) {
        return $this->slugify($string);
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}