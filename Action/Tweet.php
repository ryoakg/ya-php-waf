<?php
namespace Action;
use \Framework\Template as T;

class Tweet {
    public static function create(){
        $user_id = $_SESSION['user_id'];
        // ↓ 長さをチェック
        $tweet = $_POST['tweet'];
        // $_POST['token'];
        if ($user_id && $tweet) {
            \Model\Tweet::create($user_id, $tweet);
        }

        $redirect_to = isset($_SESSION['prev_url']) ? $_SESSION['prev_url'] : "/user";
        header("Location: {$redirect_to}", true, 303);
    }
}
