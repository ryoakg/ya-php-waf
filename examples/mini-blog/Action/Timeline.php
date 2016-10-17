<?php
namespace Action;
use \Framework\Template as T;

class Timeline {
    public static function show($url_param){
        $user_id = $url_param['user_id'];
        $my_user_id = \Model\Login::get_current_user_id();
        if (! \Model\User::get($user_id)){
            http_response_code(404);
            T::render('layout',
                      ['title' => 'そのユーザは存在しません',
                       'logged_in' => \Model\Login::is_logged_in(),
                       'content' => function (){}]);
            return;
        }
        $page = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT, ['options'=>['min_range'=>0, 'default'=>0]]);

        $page_range = \Config\Timeline\Pagination\page_range;
        $tweets_per_page = \Config\Timeline\Pagination\items_per_page;

        $n_tweets = \Model\Tweet::n($user_id);
        $n_pages = (int) ceil($n_tweets / $tweets_per_page);
        $tweets = \Model\Tweet::with_following_users($user_id, $page*$tweets_per_page, $tweets_per_page);
        $user = \Model\User::get($user_id);

        $csrf_token = \Model\CSRF::get_and_store();
        T::render('layout',
                  ['logged_in' => \Model\Login::is_logged_in(),
                   'content' =>
                   function() use ($user_id, $user, $my_user_id, $tweets, $page, $n_pages, $page_range, $csrf_token){
                       T::render('user/timeline',
                                 ['user_name' => $user['user_name'],
                                  'user_id' => $user_id,
                                  'tweets' => $tweets,

                                  'tweet_editor' =>
                                  ($my_user_id && $user_id === $my_user_id) ?
                                  ['post_to' => "/user/{$user_id}/tweet",
                                   \Config\CSRF\form_var_name => $csrf_token] : NULL,

                                  'follow' => ($my_user_id && $my_user_id !== $user_id) ?
                                  ['post_to' => "/user/{$my_user_id}/follow/{$user_id}",
                                   'is_followed' => \Model\Following::is_followed($my_user_id, $user_id),
                                   \Config\CSRF\form_var_name => $csrf_token] : NULL,

                                  'pagination' => ['url' => "/user/{$user_id}",
                                                   'n_pages' => $n_pages,
                                                   'current' => $page,
                                                   'range' => $page_range,
                                  ]]);}]);
    }
}
