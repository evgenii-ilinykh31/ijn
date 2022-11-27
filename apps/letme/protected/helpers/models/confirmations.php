<?php

namespace Helpers\Models;


use PDO;

require_once "myPdo.php";

/*
CREATE TABLE confirmations(
id INTEGER(255) AUTO_INCREMENT PRIMARY KEY NOT NULL,
userId VARCHAR(255) UNIQUE KEY NOT NULL,
token VARCHAR(255),
send TINYINT(1) DEFAULT 0,
cr_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 */

class Confirmations {

    const tableName = 'confirmations';

    const id = 'id';
    const userId = 'userId';
    const token = 'token';
    const send = 'send';
    const cr_date = 'cr_date';

    public static function insertNew(int $userId, string $token): void
    {
        $statement = MyPdo::getPDO()->prepare('insert into ' . self::tableName . '(' . self::userId . ', ' . self::token . ') values(?, ?)');
        $statement->execute([$userId, password_hash($token, PASSWORD_DEFAULT)]);
    }


    public static function getId(string $token): int
    {
        $statement = MyPdo::getPDO()->prepare('select ' . self::id . ' from ' . self::tableName . ' where ' . self::token . '=?');
        $statement->execute([$token]);

        return $statement->fetchColumn();
    }


    public static function getUserId(string $token): int
    {
        $statement = MyPdo::getPDO()->prepare('select ' . self::userId . ' from ' . self::tableName . ' where ' . self::token . '=?');
        $statement->execute([$token]);

        return $statement->fetchColumn();
    }


    public static function getToken(int $userId): string
    {
        $statement = MyPdo::getPDO()->prepare('select ' . self::token . ' from ' . self::tableName . ' where ' . self::userId . '=?');
        $statement->execute([$userId]);

        return $statement->fetchColumn();
    }

    public static function setTokenNull(int $userId): void
    {
        $statement = MyPdo::getPDO()->prepare('update ' . self::tableName . ' set ' . self::token . '=? where ' . self::userId . '=?');
        $statement->execute([0, $userId]);
    }

    public static function getAllUnsentUserId(): array
    {
        $statement = MyPdo::getPDO()->prepare('select ' . self::userId . ' from ' . self::tableName . ' where ' . self::send . '=?');
        $statement->execute([0]);

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function setSent(int $userId): void
    {
        $statement = MyPdo::getPDO()->prepare('update ' . self::tableName . ' set ' . self::send . '=? where ' . self::userId . '=?');
        $statement->execute([1, $userId]);
    }

    public static function deleteConfirmed(string $token): void
    {
        $statement = MyPdo::getPDO()->prepare('DELETE FROM ' . self::tableName . ' where ' . self::token . '=?');
        $statement->execute([$token]);
    }

}

?>