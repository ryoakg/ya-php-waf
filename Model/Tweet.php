<?php
namespace Model;

final class Tweet {
    public static function create($user_id, $body){
        $db = \Framework\Db::getConnection();
        $db->prepare('INSERT INTO tweet (user_id, body) values (?,?)')
            ->execute(array($user_id, $body));
    }

    public static function id_of($user_id){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT body FROM tweet WHERE user_id = ? ORDER BY id');
        $stmt->execute(array($user_id));
        return $stmt->fetchAll();
    }
}
