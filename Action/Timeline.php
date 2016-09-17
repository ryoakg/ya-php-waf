<?php
namespace Action;
use \Framework\Template as T;

class Timeline {
    public static function show($url_param){
        $user_id = $url_param['user_id'];
        $my_user_id = \Model\Login::get_current_user_id();
        if (! \Model\User::get($user_id)){
            http_response_code(404);
            echo '404 Not Found.';
            exit;
        }
        $page = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT, ['options'=>['min_range'=>0]]);
        if (! $page || $page < 0) $page = 0;

        $page_range = 5;        // 設定
        $tweets_per_page = 5;   // 設定

        $n_tweets = \Model\Tweet::n($user_id);
        $n_pages = (int) ceil($n_tweets / $tweets_per_page);
        $tweets = \Model\Tweet::with_following_users($user_id, $page*$tweets_per_page, $tweets_per_page);
        $user = \Model\User::get($user_id);
        T::render('layout',
                  ['logged_in' => \Model\Login::is_logged_in(),
                   'content' =>
                   function() use ($user_id, $user, $my_user_id, $tweets, $page, $n_pages, $page_range){
                       T::render('user/timeline',
                                 ['user_name' => $user['user_name'],
                                  'user_id' => $user_id,
                                  'tweets' => $tweets,

                                  'tweet_editor' =>
                                  ($my_user_id && $user_id === $my_user_id) ?
                                  ['post_to' => "/user/{$user_id}/tweet",
                                   'csrf_token' => \Model\CSRF::get_and_store()] : NULL,

                                  'follow' => ($my_user_id && $my_user_id !== $user_id) ?
                                  ['post_to' => "/user/{$my_user_id}/follow/{$user_id}",
                                   'is_followed' => \Model\Following::is_followed($my_user_id, $user_id)] : NULL,
                                  'pagination' => ['url' => "/user/{$user_id}",
                                                   'n_pages' => $n_pages,
                                                   'current' => $page,
                                                   'range' => $page_range,
                                  ]]);}]);
    }
}
