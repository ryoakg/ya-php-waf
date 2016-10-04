<?php
namespace Framework;

final class Db {
    private function __construct() {}

    static private $conn;

    public static function getConnection(){
        if (! self::$conn){
            self::$conn = new \PDO(\Config\Db\dsn);
            self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        }
        return self::$conn;
    }
}
