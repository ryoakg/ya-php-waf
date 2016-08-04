<?php
if (PHP_SAPI !== 'cli') throw new Exception(PHP_SAPI . ' is not supported SAPI.');

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

echo <<<EOT
You can use this like:
>>> \Model\User::create('taro','secret')
>>> \Model\User::all()
>>> \Model\User::verify(1,'secret')

EOT;

eval(\Psy\sh());
