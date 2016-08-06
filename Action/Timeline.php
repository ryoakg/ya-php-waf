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

        $tweets = array_map(function ($x){ return $x['body']; },
                            \Model\Tweet::id_of($user_id));
        $page = 0;
        $n_pages = 10;
        $page_range = 2;
        T::render('layout',
                  ['title' => 'タイムライン',
                   'logged_in' => \Model\Login::is_logged_in(),
                   'content' =>
                   function() use ($user_id, $tweets, $page, $n_pages, $page_range){
                       T::render('user/timeline',
                                 ['tweet_editor' =>
                                  ($user_id === $_SESSION['user_id']) ? ['post_to' => "/user/{$user_id}/tweet"] : NULL,

                                  'tweets' => $tweets,

                                  'pagination' => ['url' => '',
                                                   'n_pages' => $n_pages,
                                                   'current' => $page,
                                                   'range' => $page_range,
                                  ]]);}]);
    }
}
