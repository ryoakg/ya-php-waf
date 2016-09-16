<?php
namespace Model;

class Following {
    public static function follow($follower, $followee){
        $db = \Framework\Db::getConnection();
        $db->prepare('INSERT INTO following (follower_id,followee_id) values (?,?)')
            ->execute([$follower, $followee]);
    }

    public static function unfollow($follower, $followee){
        $db = \Framework\Db::getConnection();
        $db->prepare('DELETE FROM following WHERE follower_id = ? AND followee_id = ?')
            ->execute([$follower, $followee]);
    }

    public static function is_followed($follower, $followee){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT count(*) AS n FROM following WHERE follower_id = ? AND followee_id = ?');
        $stmt->execute([$follower, $followee]);
        return $stmt->fetch()['n'] > 0;
    }

    public static function following($follower){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT followee_id FROM following WHERE follower_id = ?');
        $stmt->execute([$follower]);
        return array_map(function($x){ return $x['followee_id']; },
                         $stmt->fetchAll());
    }
}
