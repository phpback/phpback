<?php
/**
 * Hashing class: password encryption library
 * @copyright    Copyright (c) 2014 PHPBack
 * @author       Ivan Diaz <ivan@phpback.org>
 * @author       Benjamin BALET<benjamin.balet@gmail.com>
 * @license      http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link         https://github.com/ivandiazwm/phpback
 * @since        1.0
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Hashing {

    /**
     * Hash a password using BCRYPT algo and 8 iterations
     * @param string $password Clear password to be hashed
     * @return string Hashed password
     */
    function hash($password) {
        //Hash the clear password using bcrypt (8 iterations)
        $salt = '$2a$08$' . substr(strtr(base64_encode($this->getRandomBytes(16)), '+', '.'), 0, 22) . '$';
        $hash = crypt($password, $salt);
        return $hash;
    }

    /**
     * Checks if a password matches a hash
     * @param string $password Clear password to be checked
     * @param string $hash Hash of the password to be tested
     * @return boolean True if the passwords matches the hash, Else otherwise
     */
    public function matches($password, $hash) {
        return (crypt($password, $hash) === $hash);
    }

    /**
     * Generate some random bytes by using openssl, dev/urandom or random
     * @param int $count length of the random string
     * @return string a string of pseudo-random bytes (must be encoded)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    function getRandomBytes($length) {
        if(function_exists('openssl_random_pseudo_bytes')) {
            $rnd = openssl_random_pseudo_bytes($length, $strong);
            if ($strong === TRUE)
                return $rnd;
        }
        $sha =''; $rnd ='';
        if (file_exists('/dev/urandom')) {
            $fp = fopen('/dev/urandom', 'rb');
            if ($fp) {
                if (function_exists('stream_set_read_buffer')) {
                    stream_set_read_buffer($fp, 0);
                }
                $sha = fread($fp, $length);
                fclose($fp);
            }
        }
        for ($i=0; $i<$length; $i++) {
            $sha  = hash('sha256',$sha.mt_rand());
            $char = mt_rand(0,62);
            $rnd .= chr(hexdec($sha[$char].$sha[$char+1]));
        }
        return $rnd;
    }
}