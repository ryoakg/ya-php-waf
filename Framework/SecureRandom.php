<?php
namespace Framework;

class SecureRandom {
    static public function make_random_string($size){
        switch(true){
        case function_exists('random_bytes'):
            return bin2hex(random_bytes($size)); // PHP7+
        case function_exists('mcrypt_create_iv'):
            return bin2hex(mcrypt_create_iv($size, MCRYPT_DEV_URANDOM));
        case function_exists('openssl_random_pseudo_bytes'):
            $r = bin2hex(openssl_random_pseudo_bytes($size, $is_strong));
            return $is_strong ? $r : false;
        default:
            return NULL;
        }
    }
}
