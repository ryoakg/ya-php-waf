<?php
namespace Framework;

final class Template {
    static private function resolve($template){
        return APP_ROOT_DIR . "/Template/{$template}.php";
    }

    static public function h($str){
        return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
    }

    static public function render($template, $vars){
        extract($vars);
        require self::resolve($template);
    }

    static public function render_list($template, $list){
        foreach($list as $vars)
            self::render($template, $vars);
    }

    static public function render_as_string($template, $vars){
        extract($vars);
        ob_start();
        ob_implicit_flush(0);
        require self::resolve($template);
        return ob_get_clean();
    }
}
