<?php
const APP_ROOT_DIR = __DIR__;
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Framework/Template.php';
use \Framework\Template as T;

// Framework\Template::render('test',[]);

// Framework\Template::render_with_layout('test2', [],
//                                        function(){
//                                            Framework\Template::render('test_cont',[]);
//                                        });


// T::render('test2',
//           ['content_renderer' =>
//            function(){ T::render('test_cont',[]); }
//           ]);


// T::render('layout',
//           ['title' => 'test page',
//            'content' => function(){ T::render('test_cont',[]); }
//           ]);

T::render('pagination',
          ['n_pages' => 10,
           'url' => '/',
           'current' => 0,
           'range' => 2,
          ]);
