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
        $err = [];

        $name = filter_input(INPUT_POST, 'user_name', FILTER_VALIDATE_REGEXP,
                             ['options'=>["regexp"=>"/[a-zA-Z0-9_\-]{4,20}/"]]);
        if (! $name) $err[] = 'invalid user name';

        $pass = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP,
                             ['options'=>["regexp"=>"/[a-zA-Z0-9_!@#$%^&~`\\|:;<>{}\-]{8,20}/"]]);
        if (! $pass) $err[] = 'invalid password';

        if (! empty($err)){
            $_SESSION['FLASH'] = $err;
            http_response_code(400);
            self::signup_prompt();
            // DOS の対策でタイマで遅延した方がいい?
            exit;
        }

        \Model\User::create($name, $pass);
        // https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.3.4
        // 多分 303 が適切。あとでちゃんと読む
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

                // DOS の対策でタイマで遅延した方がいい?
                // $_SESSION['user_id'] = ;
                header("Location: /", true, 303);
            } else {
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

    // APIで POST を受ける場合
    // 入力は基本的に JSON. form ではない
    // 201 を返す
    // header("Location: /...", true, 201);
    // とかもあった方がいいかも
    // 中身の処理は、HTML 返す場合と同じ
}
