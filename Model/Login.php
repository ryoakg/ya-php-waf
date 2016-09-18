<?php
namespace Model;

class Login {
    public static function is_logged_in(){
        return isset($_SESSION['logged-in-at']);
    }

    public static function login($name, $password){
        $data = \Model\User::verify($name, $password);
        if ($data) {
            $_SESSION['logged-in-at'] = time();
            $_SESSION['user_id'] = $data['id'];
        }
        return self::is_logged_in();
    }

    public static function logout(){
        unset($_SESSION['logged-in-at']);
    }

    public static function logout_if_needed(){
        $day = 24*60*60;        // 設定で変えられる方がいい
        if (time() - $_SESSION['logged-in-at'] < $day) self::logout();
    }

    public static function get_current_user_id(){
        return self::is_logged_in() ? $_SESSION['user_id'] : NULL;
    }

    public static function ensure_logged_in(){
        if (self::is_logged_in()){
            http_response_code(401);
            throw new \Exception('Unauthorized');
        }
    }
}
