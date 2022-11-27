<?php

namespace Helpers\Models;


use Helpers\Email\PHPMailer\Exception;
use PDO;

/*
create table users(
id integer(255) auto_increment primary key not null,
email varchar(255) unique key not null,
password text not null,
lastLanguage varchar(255),
cr_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
last_change TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
 */

require_once 'myPdo.php';

require_once 'confirmations.php';
require_once 'notes.php';
require_once 'sessions.php';


class Users {

    const tableName = 'users';

    const id = 'id';
    const email = 'email';
    const password = 'password';
    const lastLanguage = 'lastLanguage';

    public static function getId(string $email): string
    {
        $pdo = MyPdo::getPDO();
        $statement = $pdo->prepare('select ' . self::id . ' from ' . self::tableName . ' where ' . self::email . '=?');
        $statement->execute([$email]);

        return $statement->fetchColumn();
    }

    public static function insertNewGetId(string $email, string $password, string $language): int
    {

        $db = MyPdo::getPDO();

        $sql = 'insert into ' . self::tableName . '(' . self::email . ', ' . self::password . ', ' . self::lastLanguage . ') values(?, ?, ?)';

        $statement = $db->prepare($sql);

        $statement->execute([$email, $password, $language]);

        return $db->lastinsertid();
    }


    public static function getEmail(string $id): string
    {
        $statement = MyPdo::getPDO()->prepare('select ' . self::email . ' from ' . self::tableName . ' where ' . self::id . '=?');
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }

    public static function getPassword(string $id): string
    {
        $statement = MyPdo::getPDO()->prepare('select ' . self::password . ' from ' . self::tableName . ' where ' . self::id . '=?');
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }


    public static function setLastLanguage(int $id, string $languageCode): void
    {
        $db = MyPdo::getPDO();

        $sql = 'UPDATE ' . self::tableName . ' SET ' . self::lastLanguage . '=' . '?' . ' WHERE ' . self::id . '=' . '?';

        $statement = $db->prepare($sql);

        $statement->execute([$languageCode, $id]);

    }

    public static function setNewPassword(string $id, string $hashedNewPassword): void
    {
        $db = MyPdo::getPDO();

        $sql = 'UPDATE ' . self::tableName . ' SET ' . self::password . '=' . '?' . ' WHERE ' . self::id . '=' . '?';

        $statement = $db->prepare($sql);

        $statement->execute([$hashedNewPassword, $id]);
    }

    public static function getLastLanguage(string $id): string
    {
        $statement = MyPdo::getPDO()->prepare('select ' . self::lastLanguage . ' from ' . self::tableName . ' where ' . self::id . '=?');
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }


    public static function deleteUnconfirmed($periodInSeconds): void
    {
        $statement = MyPdo::getPDO()->prepare(
            'DELETE ' . self::tableName . ', ' . Confirmations::tableName . ', ' . Notes::tableName . ', ' . Sessions::tableName .
            ' FROM ' . self::tableName . ', ' . Confirmations::tableName . ', ' . Notes::tableName . ', ' . Sessions::tableName .
            ' WHERE ' . self::tableName . '.' . self::id . '=' . Confirmations::tableName . '.' . Confirmations::userId .
            ' AND UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(' . Confirmations::tableName . '.' . Confirmations::cr_date . ') > ?' .
            ' AND ' . self::tableName . '.' . self::id . '=' . Notes::tableName . '.' . Notes::userId .
            ' AND ' . self::tableName . '.' . self::id . '=' . Sessions::tableName . '.' . Sessions::userId);

        $statement->execute([$periodInSeconds]);
    }

}


?>