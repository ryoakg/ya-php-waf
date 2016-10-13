<?php
namespace Action;
use \Framework\Template as T;

class Tweet {
    public static function create(){
        \Model\CSRF::ensure_token_is_valid(filter_input(INPUT_POST, \Config\CSRF\form_var_name));
        if (! $user_id = \Model\Login::get_current_user_id()) throw new \Framework\UnauthorizedException();
        if (! $tweet = filter_input(INPUT_POST, 'tweet')) throw new \Framework\InvalidRequestParameterException('tweet');

        // ディスク容量が足りないとかでなければ例外は出ないはず
        // 少なくともサーバ側の要因
        \Model\Tweet::create($user_id, $tweet);

        $redirect_to = isset($_SESSION['prev_url']) ? $_SESSION['prev_url'] : "/user";
        header("Location: {$redirect_to}", true, 303);
    }
}
