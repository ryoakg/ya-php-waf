<?php
namespace Action;
use \Framework\Template as T;

class User {
    private static function get_username_from_post(){
        return filter_input(INPUT_POST, 'user_name', FILTER_VALIDATE_REGEXP,
                                 ['options'=>["regexp"=>"/[a-zA-Z0-9_\-]{4,20}/"]]);
    }

    private static function get_password_from_post(){
        return filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP,
                            ['options'=>["regexp"=>"/[a-zA-Z0-9_!@#$%^&~`\\|:;<>{}\-]{8,20}/"]]);
    }

    public static function signup_prompt(){
        T::render('layout',
                  ['title' => 'アカウント登録',
                   'logged_in' => \Model\Login::is_logged_in(),
                   'content' =>
                   function(){ T::render('user/signup',
                                  ['user_name' => NULL,
                                   'password' => NULL,
                                  ]);}]);
    }

    public static function signup(){
        if (! $name = self::get_username_from_post()) throw new \Framework\InvalidRequestParameterException('username');
        if (! $password = self::get_password_from_post()) throw new \Framework\InvalidRequestParameterException('password');

        // ユーザ名が重複した場合は、例外が出るのでここで捕捉すべき
        // APIなど作ってUI側もっていく事もできるけど、
        // UI側で同じユーザ名はいないとチェックしたけど、
        // サーバ側にリクエストが来た時点で、
        // 他の誰かにユーザ名を取られて失敗する可能性はある
        // とりあえず放っておく
        \Model\User::create($name, $password);

        // https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.4
        //   the entity of the response SHOULD contain a short hypertext note with a hyperlink to the new URI(s).
        //   面倒なので省略
        header("Location: /", true, 303);
    }

    public static function login_prompt(){
        T::render('layout',
                  ['title' => 'ログイン',
                   'logged_in' => \Model\Login::is_logged_in(),
                   'content' =>
                   function(){ T::render('user/login',
                                  ['user_name' => NULL,
                                   'password' => NULL,
                                  ]);}]);
    }

    public static function login(){
        if (! $name = self::get_username_from_post()) throw new \Framework\InvalidRequestParameterException('username');
        if (! $password = self::get_password_from_post()) throw new \Framework\InvalidRequestParameterException('password');

        \Model\Login::login($name, $password);
        $user_id = \Model\Login::get_current_user_id();
        $redirect_to = "/user/{$user_id}";
        header("Location: {$redirect_to}", true, 303);
    }

    public static function logout(){
        \Model\Login::logout();
        header("Location: /", true, 303);
    }
}
