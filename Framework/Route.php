<?php
namespace Framework;

// http://tools.ietf.org/html/rfc3875#section-4
// http://stackoverflow.com/questions/279966/php-self-vs-path-info-vs-script-name-vs-request-uri

// final やめて、これを継承させる様にすれば
// テスト専用の router が作れると思う
// そうすれば、$_GET, $_POST, URL, getallheaders() とか複数の入力から
// パラメタがちゃんと渡るかだけを検証できる

// $_SERVER['REQUEST_METHOD']
// で、リクエストの種類も見た方がいい

// URLに対応する関数の特定と
// その関数に対する引数をURLのPATHの部分から取得
// でもこの URL からの引数が全ての引数とは限らない
// 他にも、query-string, POSTとかの内容, http header, cookie, session情報, 環境変数 などが考えられ
// それぞれ特性も違う
// HTTPリクエストもGET,POSTのみとは限らない

// URLによって表現される外部に見せているリソースに
// 対する操作(= REQUEST_METHOD)と、
// 手続き(関数 or メソッド)を関連付ける

// ルーティングに必用な要素
//   path
//   method(POST の _method) GET,POST
//   処理
// APIも処理するなら、virtual host とかでドメイン分けた方がいいと思う
// アプリケーションが、virtual host を見て判断するのは
// 実装が特定のドメイン名に依存して出来て微妙かもしれないけど
final class Route {
    public static $url_pattern_map;

    public static function set_url_patterns($x) {
        self::$url_pattern_map = $x;
    }

    // webサーバ依存かもしれないけど、いらないかも
    private static function ensure_begining_with_slash($str){
        return $str[0] === '/' ? $str : '/' . $str;
    }
    // ensure_begining_with_slash('aaa'); // '/aaa'
    // ensure_begining_with_slash('/aaa'); // '/aaa'

    // 設定ミスに気付く様に入力をチェックした方がいいけど
    // filter_var('/a/b/c', FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
    // とかできないらしいのでやめる
    private static function _compile_url_pattern_to_named_capuring_regex($url) {
        $r = trim($url, '/');
        if (empty($r)) return '#\A/\z#';
        $r = explode('/', $r);
        $r = array_map(function($x){
            return $x[0] === ':' ?
                        '(?P<' . substr($x, 1) . '>[^/]+)' :
                        $x;
        }, $r);
        return '#\A/' . implode('/', $r) . '\z#';
    }
    // _compile_url_pattern('/a/b/c');  // '#/a/b/c#'
    // _compile_url_pattern('/a/b/c/'); // '#/a/b/c#'
    // _compile_url_pattern('/a/:b/c'); // '#/a/(?P<b>[^/]+)/c#'
    // _compile_url_pattern('/a/:b/:c'); // '#/a/(?P<b>[^/]+)/(?P<c>[^/]+)#'

    // [preg_match(_compile_url_pattern('/a/:b/:c'), '/a/1/20', $m), $m];
    // // array(
    // //   0 => 1,
    // //   1 => array(
    // //     0 => '/a/1/20',
    // //     'b' => '1',
    // //     1 => '1',
    // //     'c' => '20',
    // //     2 => '20'
    // //   )
    // // )

    // [preg_match(_compile_url_pattern('/a/:b/xyz/:c'), '/a/1/20', $m), $m];
    // // array(
    // //   0 => 0,
    // //   1 => array(
    // //   )
    // // )

    // [preg_match(_compile_url_pattern('/a/:b/xyz/:c'), '/a/1/xyz/5', $m), $m];
    // // array(
    // //   0 => 1,
    // //   1 => array(
    // //     0 => '/a/1/xyz/5',
    // //     'b' => '1',
    // //     1 => '1',
    // //     'c' => '5',
    // //     2 => '5'
    // //   )
    // // )

    // このアプリケーションが扱うURLの共通部分
    // URLの中でこの部分は ルーティング に関係ないので、本当は必用ない
    // SCRIPT_NAME も REQUEST_URI も URL の PATH の部分のみ
    // mod_rewrite とかが REQUEST_URI を SCRIPT_NAME に書換える
    private static function _application_root_path_of_url($requested_url, $url_maybe_rewrited) {
        // $url_maybe_rewrited には query文字列 や fragment は含まれない
        if (0 === strpos($requested_url, $url_maybe_rewrited)) {
            // rewrite で SCRIPT_NAME のexecutableファイル名が消える様にして「いない」場合
            return $url_maybe_rewrited;
        } else if (0 === strpos($requested_url, dirname($url_maybe_rewrited))) {
            // rewrite で SCRIPT_NAME のexecutableファイル名が消える様にして「いる」場合
            // index.php とかいらない場合など
            // それでも、executableファイル名より前のディレクトリ名の部分は同じ
            return rtrim(dirname($url_maybe_rewrited), '/');
        } else {
            // 多分、そのドメインでこのアプリケーションしか動かさない場合
            // 例外でもいいかも
            return '';
        }
    }

    // routing の対象になる application 内での相対的なパス
    // 結局のところ、アプリケーションが感心を持っているのはこの部分だけ
    // ここから、コントローラ、アクション、パラメタなどが分かるので
    private static function _path_to_route($app_root, $request_uri) {
        return substr(parse_url($request_uri, PHP_URL_PATH), // PATH part of the URL
                      strlen($app_root)); // starts from here
    }
    // やっているのは、REQUEST_URI を ルーティングに必用な情報と
    // それより前の部分の、アプリケーションにとってどうでもいい部分に分けているという感じ。
    // でも本当は、ルーティングに必用な部分しかいらない。
    // それを計算する為に、先に それより前の部分 を計算しているだけ。
    public static function path_to_route() {
        return self::_path_to_route(self::_application_root_path_of_url($_SERVER['REQUEST_URI'],
                                                                        $_SERVER['SCRIPT_NAME']),
                                    $_SERVER['REQUEST_URI']);
    }

    // どこで実行するのがいいかは分からないけど
    // 1回コンパイルしてそれをキャッシュするのが一番効率がいい
    public static function compile_url_action_map($definitions) {
        $a = array();
        foreach ($definitions as $url_pattern => $http_method_action_map)
            $a[self::_compile_url_pattern_to_named_capuring_regex($url_pattern)] = $http_method_action_map;
        return $a;
        // self::$url_pattern_map = $a;
    }

    public static function http_method(){
        if (isset($_POST['_method'])) return $_POST['_method'];
        if (isset($_SERVER['REQUEST_METHOD'])) return $_SERVER['REQUEST_METHOD'];
        return NULL;
    }

    // REQUEST_METHOD も見る様にすると、引数増えるな
    // どういうルーティングをするかは、場合によって違う
    // だから引数も違ってくる
    // マクロが使えるなら、
    //   REQUEST_URI → url-path, REQUEST_METHOD → http-method
    // みたいな、イミフな環境変数とかにもっといい名前をつけて
    // それを元に、 resolve の引数の組合せを定義してくれるといいと思う
    public static function resolve($path_to_route) {
        // コンパイルしたものをキャッシュした方がいい
        // if (empty?(self::$url_pattern_map))
        //     self::$url_pattern_map = self::$compile_url_patterns(...);
        // $m = apc_fetch('route');
        // if (! $m){ $m = xxx; }

        $path_to_route = self::ensure_begining_with_slash($path_to_route);
        $method = self::http_method();
        foreach (self::$url_pattern_map as $pattern => $m) {
            if (preg_match($pattern, $path_to_route, $matches) === 0) continue;
            if (! isset($m[$method])) return NULL;
            list($cls, $method) = $m[$method];
            // コントローラがない、アクションがないという場合も含めて
            // try-catch を外でやりたいので、
            // ここでは何も実行しないで、関数だけ返す
            // 名前の通り resolve だけしたい
            $f = ["Action\\{$cls}", $method];
            return function() use($f, $matches) {
                call_user_func($f, $matches);
            };
        }
        return NULL;
    }
}

// $a = ['a' => 1, 'b' => 2];
// $b = $a;
// $b['c'] = 3;
// [$a, $b];

/*
$m = new ReflectionMethod('Route', 'ensure_begining_with_slash');
$m->setAccessible(true);
$m->invoke(NULL, 'aaa') === '/aaa';

*/
