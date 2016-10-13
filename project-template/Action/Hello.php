<?php
namespace Action;
use Framework\Template as T;

class Hello {
    public static function hello(){
        T::render('layout', [
            'content' => function(){ T::render('hello',[]); }
        ]);
    }
}
