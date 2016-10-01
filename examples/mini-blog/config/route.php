<?php
return [
    '/'       => ['GET' => ['StaticPage', 'top']],
    '/signup' => ['GET' => ['User', 'signup_prompt']],
    '/login'  => ['GET' => ['User', 'login_prompt']],

    '/user'        => ['POST' => ['User', 'signup']],

    '/user/login'  => ['POST' => ['User', 'login'],
                       'PUT'  => ['User', 'login'],
                       'DELETE' => ['User', 'logout']
    ],

    '/user/:user_id' => ['GET' => ['Timeline', 'show']],
    '/user/:user_id/tweet' => ['POST' => ['Tweet', 'create'],],
    '/user/:follower_id/follow/:followee_id' => ['POST' => ['Follow', 'create'],
                                                 'DELETE' => ['Follow', 'delete']],
];
