<?php
return ['Cli' => [
    'message' => join(PHP_EOL,
                      ['You can use this like:',
                       '>>> \Model\User::create(\'taro\',\'secret\')',
                       '>>> \Model\User::all()',
                       '>>> \Model\User::verify(1,\'secret\')',
                       '>>> \Model\Following::follow(1,3)',
                       '>>> \Model\Following::unfollow(1,3)',
                       '>>> \Model\Following::following(1)'])
]];
