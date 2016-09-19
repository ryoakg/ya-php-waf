<?php
namespace Action;
use \Framework\Template as T;

class User {
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
        $name = filter_input(INPUT_POST, 'user_name', FILTER_VALIDATE_REGEXP,
                             ['options'=>["regexp"=>"/[a-zA-Z0-9_\-]{4,20}/"]]);
        if (! $name) throw new InvalidRequestParameterException('username');
        $pass = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP,
                             ['options'=>["regexp"=>"/[a-zA-Z0-9_!@#$%^&~`\\|:;<>{}\-]{8,20}/"]]);
        if (! $password) throw new InvalidRequestParameterException('password');

        // ユーザ名が重複した場合は、例外が出るのでここで捕捉すべき
        // APIなど作ってUI側もっていく事もできるけど、とりあえずやらない
        \Model\User::create($name, $pass);

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
        $err = [];
        try{
            $name = filter_input(INPUT_POST, 'user_name', FILTER_VALIDATE_REGEXP,
                                 ['options'=>["regexp"=>"/[a-zA-Z0-9_\-]{4,20}/"]]);
            $pass = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP,
                                 ['options'=>["regexp"=>"/[a-zA-Z0-9_!@#$%^&~`\\|:;<>{}\-]{8,20}/"]]);

            if ($name && $pass && \Model\Login::login($name, $pass)){
                // https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.4
                // 多分 303 が適切。あとでちゃんと読む

                $redirect_to = "/user/{$_SESSION['user_id']}";
                header("Location: {$redirect_to}", true, 303);
            } else {
                // DOS の対策でタイマで遅延した方がいい?
                $_SESSION['FLASH'][] = 'ログイン失敗';
                self::login_prompt();
            }
        } catch (\Exception $e){
            header("Location: /hoge.php");
        }
    }

    public static function logout(){
        \Model\Login::logout();
        header("Location: /", true, 303);
    }
}
