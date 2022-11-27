<?php

namespace Helpers\Models;


use PDO;

class MyPdo {

    //SINGLETON

    protected static $pdo = false;

    public static function getPDO(): PDO
    {
        if ( ! self::$pdo)
        {
            self::$pdo = self::pdo();
        }

        return self::$pdo;
    }

    protected static function pdo(): PDO
    {
        $user = 'ijn_letme_mr-user-CA';

        $pass = ']Jvh!"VY7X_^"m)k';

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO(self::getDsn(), $user, $pass, $opt);

        return self::$pdo;
    }


    protected static function getDsn(): string
    {
        $host = 'localhost';
        $db = 'letme';
        $charset = 'utf8mb4';

        return "mysql:host=$host;dbname=$db;charset=$charset";
    }
}

?>