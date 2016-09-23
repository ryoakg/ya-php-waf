<?php
const APP_ROOT_DIR = __DIR__;
require_once __DIR__ . '/vendor/autoload.php';

// ini(仮)  http://qiita.com/mpyw/items/2f9955db1c02eeef43ea
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

// initialize Loader
spl_autoload_register(function($class){
    $file = str_replace('\\', DIRECTORY_SEPARATOR , $class) . '.php';
    if (is_readable($file)) require_once($file);
}, true);
spl_autoload_register(function($class){
    throw new Exception("class `$class` is not found.");
}, true);

session_start();

\Framework\Route::set_url_patterns(
    \Framework\Route::compile_url_action_map([
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
    ])
);

$path_to_route = \Framework\Route::path_to_route();

if ($_SERVER['REQUEST_METHOD'] === 'GET') $_SESSION['prev_url'] = $path_to_route;

$action = \Framework\Route::resolve($path_to_route);
if (! $action) {
    http_response_code(404);
    echo '404 Not Found.';
    exit;
}

try {
    $action();
} catch (\Framework\CsrfException $e){
    http_response_code(403);
    echo "403 Forbidden";
} catch (\Framework\InvalidRequestParameterException $e){
    http_response_code(400);
    echo "400 Bad Request";
} catch (\Framework\UnauthorizedRequestException $e){
    http_response_code(401);
    echo "401 Unauthorized";
} catch (Exception $e){
    throw $e;
}
