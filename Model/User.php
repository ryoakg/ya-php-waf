<?php
namespace Model;

class User {
    private static $table = 'user';

    /**
     * Returns all user data.
     *
     * @return array of user
     */
    public static function all(){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT * FROM user');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Creates user account on the DB.
     *
     * @param string indicates user name.
     */
    public static function create($name, $password){
        $db = \Framework\Db::getConnection();
        $db->prepare('INSERT INTO user (user_name,password) values (?,?)')
            ->execute(array($name, password_hash($password, PASSWORD_DEFAULT, ['cost' => 11])));
    }

    public static function delete($id){
        $db = \Framework\Db::getConnection();
        $db->prepare('DELETE FROM user WHERE id = ?')
            ->execute(array($id));
    }

    public static function get($id){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->execute(array($id));
        $x = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $x ? $x : NULL;
    }

    public static function verify($name, $password){
        $db = \Framework\Db::getConnection();
        $stmt = $db->prepare('SELECT * FROM user WHERE user_name = ?');
        $stmt->execute(array($name));
        $x = $stmt->fetch(\PDO::FETCH_ASSOC);
        return password_verify($password, $x['password']) ? $x : NULL;
    }
}
