<?php
namespace Framework;

// $_GET, $_POST に意図しない値が入っている事を示す。
// サーバ側でなく、UI側の検証した方がプログラムがシンプルになるので
// サーバ側でエラー後のページ遷移など考えないで、
// 単純にこの例外を出して終りにした方がいい。
// そうした場合、この例外が発生するとしたら、
// アプリケーションを作っている途中か、運用中ならアタック
class InvalidRequestParameterException extends \Exception {}
