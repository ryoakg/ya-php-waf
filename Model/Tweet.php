<?php
namespace Model;

final class Tweet {
    public static function create($user_id, $body){
        $db = \Framework\Db::getConnection();
        $db->prepare('INSERT INTO tweet (user_id, body) values (?,?)')
            ->execute(array($user_id, $body));
    }

    public static function id_of($user_id, $from, $n){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT body FROM tweet
                               WHERE user_id = ?
                               ORDER BY id DESC
                               LIMIT ?
                              OFFSET ?');
        $stmt->execute(array($user_id, $n, $from));
        return array_map(function ($x){ return $x['body']; }, $stmt->fetchAll());
    }

    public static function n($user_id){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT count(*) AS n FROM tweet WHERE user_id = ?');
        $stmt->execute(array($user_id));
        $x = $stmt->fetch();
        return (int)$x['n'];
    }
}
