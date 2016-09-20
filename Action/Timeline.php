<?php
namespace Action;
use \Framework\Template as T;

class Timeline {
    public static function timeline(){
        $user_id = $_SESSION['user_id'];
        $tweets = array_map(function ($x){ return $x['body']; },
                            \Model\Tweet::id_of($user_id));
        $page = 0;
        $n_pages = 10;
        $page_range = 2;
        T::render('layout',
                  ['title' => 'タイムライン',
                   'logged_in' => \Model\Login::is_logged_in(),
                   'content' =>
                   function() use ($tweets, $page, $n_pages, $page_range){
                       T::render('user/timeline',
                                 ['tweet_editor' => ['post_to' => 'user/tweet'
                                                     // "user/${$user_id}/tweet"
                                 ],
                                  'tweets' => $tweets,
                                  'pagination' => ['url' => '',
                                                   'n_pages' => $n_pages,
                                                   'current' => $page,
                                                   'range' => $page_range,
                                  ]]);}]);
    }
}
