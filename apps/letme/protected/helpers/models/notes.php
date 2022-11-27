<?php

namespace Helpers\Models;


require_once 'myPdo.php';


use PDO;

/*
CREATE TABLE notes(
id integer(255) auto_increment PRIMARY KEY not null,
userId integer(255) not null,
text TEXT,
weight integer(255),
isDelete BOOL DEFAULT 0,
cr_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
last_change TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
 */

class Notes {

    const tableName = 'notes';

    const id = 'id';
    const userId = 'userId';
    const text = 'text';
    const weight = 'weight';
    const isDelete = 'isDelete';


    public static function getList(int $userId, int $quantity, int $offset = 0): array
    {

        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::id . ', ' . self::text . ' FROM ' . self::tableName . ' WHERE ' . self::userId . '=? AND ' . self::isDelete . '=? ORDER BY ' . self::weight . ', ' . self::id . ' LIMIT ?, ?');
        $statement->execute([$userId, 0, $offset, $offset + $quantity]);

        return $statement->fetchAll(PDO::FETCH_NUM);
    }


    public static function find(int $userId, array $wordsList): array
    {

        $pdo = MyPdo::getPDO();

        $query = 'SELECT ' . self::id . ', ' . self::text . ' FROM ' . self::tableName . ' WHERE ' . self::userId . ' = ? AND ' . self::isDelete . '=?';

        $subQuery = '';

        $executeArray = [$userId, 0];

        for ($i = 0; $i < count($wordsList); $i++)
        {
            $subList = $wordsList[$i];

            $subQuery .= '(';

            for ($j = 0; $j < count($subList); $j++)
            {
                $subQuery .= self::text . ' LIKE ?';

                $executeArray[] = '%' . $subList[$j] . '%';

                if ($j < count($subList) - 1)
                {
                    $subQuery .= ' AND ';
                }
            }

            $subQuery .= ')';

            if ($i < count($wordsList) - 1)
            {
                $subQuery .= ' OR ';
            }
        }

        if (count($wordsList) > 0)
        {
            $query .= ' AND (' . $subQuery . ')';
        }

        $query .= ' ORDER BY ' . self::weight . ', ' . self::id;

        $statement = $pdo->prepare($query);

        $statement->execute($executeArray);

        $result = $statement->fetchAll(PDO::FETCH_NUM);

        return $result;
    }


    public static function insertNewGetId(int $userId, string $text): int
    {
        $db = MyPdo::getPDO();

        $weight = self::getLastWeight($userId) + 1;

        $statement = $db->prepare(
            'insert into ' . self::tableName . '(' . self::userId . ', ' . self::text . ', ' . self::weight . ') values(?, ?, ?)'
        );

        $statement->execute([$userId, $text, $weight]);

        return $db->lastInsertId();
    }


    public static function delete(int $id): void
    {
        //Delete marked to delete earlier
        $userId = self::getUserId($id);
        $statementDelete = MyPdo::getPDO()->prepare('DELETE FROM ' . self::tableName . ' WHERE ' . self::isDelete . '=? AND ' . self::userId . '=?');
        $statementDelete->execute([1, $userId]);

        $statementMarkAsDelete = MyPdo::getPDO()->prepare('UPDATE ' . self::tableName . ' SET ' . self::isDelete . '=?' . ' WHERE ' . self::id . '=?');
        $statementMarkAsDelete->execute([1, $id]);

        self::renumAndUpdateWeights(self::getIdWeightsAssoc(self::getUserId($id)));
    }


    public static function undoDelete(int $id): void
    {
        $statementMarkAsDelete = MyPdo::getPDO()->prepare('UPDATE ' . self::tableName . ' SET ' . self::isDelete . '=?' . ' WHERE ' . self::id . '=?');
        $statementMarkAsDelete->execute([0, $id]);
    }


    public static function getUserId(string $id): int
    {
        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::userId . ' FROM ' . self::tableName . ' WHERE ' . self::id . '=?');
        $statement->execute([$id]);

        return $statement->fetchColumn();
    }


    public static function edit(string $newText, int $id): void
    {
        $statement = MyPdo::getPDO()->prepare('UPDATE ' . self::tableName . ' SET ' . self::text . '=?' . ' WHERE ' . self::id . '=?');
        $statement->execute([$newText, $id]);
    }


    public static function changeWeight(int $movingId, int $beforeId = 0): void
    {

        $userId = self::getUserId($movingId);

        $assocIdWeightArray = self::getIdWeightsAssoc($userId);

        $movingArrayIndex = 0;
        $beforeArrayIndex = 0;

        //находим index элемента которой двигаем и index элемента перед которым вставляем
        for ($i = 0; $i < count($assocIdWeightArray); $i++)
        {
            $subArray = $assocIdWeightArray[$i];
            if ($subArray[self::id] === $movingId)
            {
                $movingArrayIndex = $i;
            }

            if ($subArray[self::id] === $beforeId)
            {
                $beforeArrayIndex = $i;
            }
        }

        $insertingArray = $assocIdWeightArray[$movingArrayIndex];

        unset($assocIdWeightArray[$movingArrayIndex]);

        if ($beforeId && $beforeArrayIndex <= $movingArrayIndex)
        {//before - в начале массива(раньше, чем moving)
            array_splice($assocIdWeightArray, $beforeArrayIndex, 0, [$insertingArray]);
        }
        elseif ($beforeId && $beforeArrayIndex > $movingArrayIndex)
        {//before - в конце массива(позже, чем moving)
            array_splice($assocIdWeightArray, $beforeArrayIndex - 1, 0, [$insertingArray]);
        }
        else
        {
            $assocIdWeightArray[] = $insertingArray;
        }

        self::renumAndUpdateWeights($assocIdWeightArray, $userId);
    }


    public static function getLastWeight($userId): int
    {
        //Этой функции без разницы помечена заметка на удаление или нет
        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::weight . ' FROM ' . self::tableName . ' WHERE ' . self::userId . '=? ORDER BY ' . self::weight . ' DESC, ' . self::id . ' DESC');
        $statement->execute([$userId]);

        return intval($statement->fetchColumn());
    }

    public static function getIdWeightsAssoc($userId): array
    {
        $statement = MyPdo::getPDO()->prepare('SELECT ' . self::id . ', ' . self::weight . ' FROM ' . self::tableName . ' WHERE ' . self::userId . '=? ORDER BY ' . self::weight . ', ' . self::id);
        $statement->execute([$userId]);
        $userWithWeightArray = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $userWithWeightArray;
    }

    public static function renumAndUpdateWeights(array $notesIdWeightArray, int $userId): void
    {
        $valuesQuestionMarks = '';
        $valuesArray = [];

        $notesIdWeightArray = array_values($notesIdWeightArray);

        for ($i = 0; $i < count($notesIdWeightArray); $i++)
        {
            $weight = $i + 1;

            $valuesQuestionMarks .= '(?, ?, ?)';
            if ($i < count($notesIdWeightArray) - 1)
            {
                $valuesQuestionMarks .= ', ';
            }

            $valuesArray[] = $notesIdWeightArray[$i][self::id];
            $valuesArray[] = $userId;
            $valuesArray[] = $weight;
        }

        //INSERT INTO users(id, weight) VALUES(1, 1), (2, 2), (3, 3) ON DUPLICATE KEY UPDATE weight=VALUES(weight)

        $query = 'INSERT INTO ' . self::tableName . '(' . self::id . ', ' . self::userId . ', ' . self::weight . ')' .
            ' VALUES ' . $valuesQuestionMarks . ' ON DUPLICATE KEY UPDATE ' . self::userId . '=VALUES(' . self::userId . '), ' . self::weight . '=VALUES(' . self::weight . ')';


        $statement = MyPdo::getPDO()->prepare($query);

        $statement->execute($valuesArray);
    }

    public static function getIdOfBeforeWeightById(int $userId, int $id): string
    {
        $query = 'SELECT ' . self::id .
            ' FROM ' . self::tableName .
            ' WHERE ' . self::userId . '=?' .
            ' AND ' . self::weight . '>(SELECT ' . self::weight . ' FROM ' . self::tableName . ' WHERE ' . self::id . '=?)' .
            ' ORDER BY ' . self::weight . ' LIMIT ?';

        $statement = MyPdo::getPDO()->prepare($query);

        $statement->execute([$userId, $id, 1]);

        return $statement->fetchColumn();
    }
}

?>