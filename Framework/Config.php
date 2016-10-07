<?php
namespace Config;

const CONFIG_DIR = 'config/';

function is_associative_array($x){
    return is_array($x) && ! isset($x[0]);
}

function literal($x){
    return is_string($x) ? "'".str_replace("'","\\'",$x)."'" : $x;
}

function load($filename){
    $path = CONFIG_DIR . $filename;
    switch ($ext = strrchr($path, '.')){
    case '.php':
        return require($path);
    case '.json':
        return json_decode(file_get_contents($path), true);
    // case '.yaml':
    // case '.yml':
    //     if (function_exists('yaml_parse')){
    //         return yaml_parse(file_get_contents($path));
    //     }
    default:
        throw new \Exception("{$ext} is not supported file type.");
    }
}

function flatten($ns_stack, $assoc){
    $vars = [];
    $nss = [];
    foreach($assoc as $name => $x){
        if (is_associative_array($x)) {
            $stk = $ns_stack;
            $stk[] = $name;
            $nss = array_merge($nss, flatten($stk, $x));
        } else
            $vars[] = ['name' => $name, 'val' => $x];
    }
    if (! empty($vars))
        $nss[] = ['namespace' => $ns_stack, 'vars' => $vars];
    return $nss;
}

function render($xs){
    $rs = [];
    foreach($xs as $x){
        $rs[] = 'namespace ' . join('\\', $x['namespace']) . ';';
        foreach ($x['vars'] as $v)
            $rs[] = "const {$v['name']} = ".literal($v['val']).';';
        $rs[] = '';
    }
    return join(PHP_EOL, $rs);
}

function generate(){
    $xs = func_get_args();
    $xs = array_map('\Config\load', $xs);
    $xs = call_user_func_array('array_replace_recursive', $xs);
    $xs = flatten(['Config'], $xs);
    $xs = render($xs);
    return $xs;
}
