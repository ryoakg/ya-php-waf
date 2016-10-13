<?php
namespace Action;

class Follow {
    private static function create_delete($fun, $url_param){
        \Model\CSRF::ensure_token_is_valid(filter_input(INPUT_POST, \Config\CSRF\form_var_name));

        $follower_id = $url_param['follower_id'];
        $followee_id = $url_param['followee_id'];

        call_user_func($fun, (int)$follower_id, (int)$followee_id);

        $redirect_to = isset($_SESSION['prev_url']) ? $_SESSION['prev_url'] : "/user";
        header("Location: {$redirect_to}", true, 303);
    }

    public static function create($url_param){
        self::create_delete(['\Model\Following','follow'], $url_param);
    }

    public static function delete($url_param){
        self::create_delete(['\Model\Following','unfollow'], $url_param);
    }
}
