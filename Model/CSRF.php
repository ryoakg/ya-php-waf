<?php
namespace Model;

class CSRF {
    /* private */ const key = '*csrf_token*';

    public static function get_and_store(){
        $token = \Framework\SecureRandom::make_random_string(32); // 設定
        $_SESSION[self::key] = $token;
        return $token;
    }

    public static function is_valid_token($token){
        return isset($_SESSION[self::key]) && $_SESSION[self::key] === $token;
    }

    public static function ensure_token_is_valid($token){
        if (! self::is_valid_token($token)){
            http_response_code(403);
            throw new \Framework\CsrfException();
        }
    }
}
