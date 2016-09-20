<?php
namespace Model;

final class Tweet {
    public static function create($user_id, $body){
        // check wheather logged in
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

    public static function with_following_users($user_id, $from, $n){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('
SELECT tweet.body, user.user_name, tweet.created_at
  FROM tweet
  JOIN user ON user_id = user.id
 WHERE user_id = ? OR
       user_id IN (SELECT followee_id FROM following WHERE follower_id = ?)
 ORDER BY tweet.id DESC
 LIMIT ? OFFSET ?');
        $stmt->execute([$user_id, $user_id, $n, $from]);
        return $stmt->fetchAll();
    }

    public static function n($user_id){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT count(*) AS n FROM tweet WHERE user_id = ?');
        $stmt->execute(array($user_id));
        $x = $stmt->fetch();
        return (int)$x['n'];
    }
}
