<?php
namespace Action;
use \Framework\Template as T;

class Timeline {
    public static function show($url_param){
        $user_id = $url_param['user_id'];
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
        $tweets = \Model\Tweet::id_of($user_id, $page*$tweets_per_page, $tweets_per_page);
        T::render('layout',
                  ['title' => 'タイムライン',
                   'logged_in' => \Model\Login::is_logged_in(),
                   'content' =>
                   function() use ($user_id, $tweets, $page, $n_pages, $page_range){
                       T::render('user/timeline',
                                 ['tweet_editor' =>
                                  (isset($_SESSION['user_id']) && $user_id === $_SESSION['user_id']) ? ['post_to' => "/user/{$user_id}/tweet"] : NULL,

                                  'tweets' => $tweets,

                                  'pagination' => ['url' => "/user/{$user_id}",
                                                   'n_pages' => $n_pages,
                                                   'current' => $page,
                                                   'range' => $page_range,
                                  ]]);}]);
    }
}
