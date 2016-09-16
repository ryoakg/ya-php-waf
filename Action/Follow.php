<?php
namespace Action;

class Follow {
    public static function create($url_param){
        $follower_id = $url_param['follower_id'];
        $followee_id = $url_param['followee_id'];
        \Model\Following::follow((int)$follower_id, (int)$followee_id);

        $redirect_to = isset($_SESSION['prev_url']) ? $_SESSION['prev_url'] : "/user";
        header("Location: {$redirect_to}", true, 303);
    }

    public static function delete($url_param){
        $follower_id = $url_param['follower_id'];
        $followee_id = $url_param['followee_id'];
        \Model\Following::unfollow((int)$follower_id, (int)$followee_id);

        $redirect_to = isset($_SESSION['prev_url']) ? $_SESSION['prev_url'] : "/user";
        header("Location: {$redirect_to}", true, 303);
    }
}
