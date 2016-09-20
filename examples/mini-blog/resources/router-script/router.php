<?php
if (preg_match('#\A/(css|js|img)/#', $_SERVER["REQUEST_URI"])) {
    return false;               // ファイルシステムをそのまま反映
}

$_SERVER['SCRIPT_NAME'] = '/main.php';
require_once(__DIR__ . '/../..' . $_SERVER['SCRIPT_NAME']);
