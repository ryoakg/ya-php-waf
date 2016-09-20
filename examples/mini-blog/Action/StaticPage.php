<?php
namespace Action;
use Framework\Template as T;

class StaticPage {
    public static function top(){
        $logged_in = \Model\Login::is_logged_in();
        T::render('layout',
                  ['title' => 'トップページ',
                   'logged_in' => $logged_in,
                   'content' =>
                   function() use($logged_in){ T::render('top',
                                                  ['logged_in' => $logged_in]);} ]);
    }
}
