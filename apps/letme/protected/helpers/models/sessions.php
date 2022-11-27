<?php

namespace Helpers\Models;


/*
CREATE TABLE sessions(
id integer(255) auto_increment primary key not null,
userId integer(255) not null,
sessionName varchar(255) UNIQUE,
sessionPassword varchar(255),
tokenCSRF varchar(255),
cr_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
last_change TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
*/

require_once 'myPdo.php';

class Sessions {

    const tableName = 'sessions';

    const id = 'id';
    const userId = 'userId';
    const sessionName = 'sessionName';
    const sessionPassword = 'sessionPassword';
    const tokenCsrf = 'tokenCsrf';
    const last_change = 'last_change';

    public static function insertNew($userId, $sessionName, $sessionPassword, $tokenCsrf): void
    {
        $statement = MyPdo::getPDO()->prepare('insert into ' . self::tableName . '(' . self::userId . ', ' . self::sessionName . ', ' . self::sessionPassword . ', ' . self::tokenCsrf . ') values(?, ?, ?, ?)');

        $statement->execute([$userId, $sessionName, $sessionPassword, $tokenCsrf]);
    }

    public static function getId(string $sessionName): string
    {
        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::id . ' FROM ' . self::tableName . ' WHERE ' . self::sessionName . '=?');
        $statement->execute([$sessionName]);

        return $statement->fetchColumn();
    }

    public static function getPassword(string $sessionName): string
    {
        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::sessionPassword . ' FROM ' . self::tableName . ' WHERE ' . self::sessionName . '=?');
        $statement->execute([$sessionName]);

        return $statement->fetchColumn();
    }

    public static function delete($id)
    {
        $statement = MyPdo::getPDO()->prepare('DELETE FROM ' . self::tableName . ' WHERE ' . self::id . '=?');

        $statement->execute([$id]);
    }

    public static function getUserId(int $id): string
    {
        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::userId . ' FROM ' . self::tableName . ' WHERE ' . self::id . '=?');
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }

    public static function getTokenCsrf(int $id): string
    {
        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::tokenCsrf . ' FROM ' . self::tableName . ' WHERE ' . self::id . '=?');
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }

    public static function setLastChange(int $id): void
    {
        $statement = MyPdo::getPDO()->prepare('UPDATE ' . self::tableName . ' SET ' . self::last_change . '=now() WHERE ' . self::id . '=?');
        $statement->execute([$id]);
    }

    public static function deleteUnused($periodInSeconds): void
    {
        $statement = MyPdo::getPDO()->prepare('DELETE FROM ' . self::tableName . ' WHERE UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(' . self::last_change . ') > ?');
        $statement->execute([$periodInSeconds]);
    }

}

?>