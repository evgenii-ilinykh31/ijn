<?php

namespace Helpers;


require_once "models/notes.php";
require_once "organizers/constants.php";
require_once 'logWriter.php';

use Helpers\Models\Notes;
use Helpers\Organizers\Constants;

class Note {

    public static function getList(int $userId, int $offset = 0): array
    {
        return Notes::getList($userId, Constants::notesNumberPerRequest, $offset);
    }


    public static function couldBeSaved(string $text): bool
    {

        $text = trim(htmlentities($text), " \t\n\r\0\x0B\&nbsp;\,");

        if ($text)
        {
            return true;
        }

        return false;
    }


//    public static function isCanFind(string $text): bool
//    {
//        $text = preg_replace('/\s+/', '', $text);
//
//        $text = preg_replace('/\,+/', ',', $text);
//
//        if (strlen($text) == 0)
//        {
//            return false;
//        }
//
//        return true;
//
//    }


    protected static function prepareFind(string $text): string
    {
        $text = trim($text);

        $text = preg_replace('/\s+/', ' ', $text);

        $text = preg_replace('/\s*\,+\s*/', ',', $text);

        $text = preg_replace('/\,+/', ',', $text);

        return $text;
    }


    public static function find(int $userId, string $words): array
    {
        //expects: word11 word12 word13, word21 word22 word23

        $wordsList = explode(',', self::prepareFind($words));

        for ($i = 0; $i < count($wordsList); $i++)
        {
            $wordsList[$i] = explode(' ', $wordsList[$i]);
        }

        return Notes::find($userId, $wordsList);
    }


    public static function delete(int $id, int $userId): bool
    {
        if ( ! Notes::getUserId($id) === $userId)
        {
            return false;
        }

        Notes::delete($id);

        return true;
    }


    public static function saveGetId(int $userId, string $text): int
    {
        return Notes::insertNewGetId($userId, trim($text));
    }

}

?>