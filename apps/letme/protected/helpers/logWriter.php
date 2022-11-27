<?php

namespace Helpers;


class LogWriter {

    const log = '/home/ijn/apps/letme/protected/helpers/logs/log.txt';
    protected static $substractHolder = 0;

    public static function write($file = '', $function = '', $message = '')
    {

        $milliseconds = microtime(true);

        if (self::$substractHolder)
        {
            $milliseconds = $milliseconds . '  |  ' . (floatval($milliseconds) - floatval(self::$substractHolder));

            self::$substractHolder = 0;
        }
        else
        {
            self::$substractHolder = $milliseconds;
        }

        file_put_contents(self::log, "\n $file  |  $function  |  $milliseconds  |  $message", FILE_APPEND | LOCK_EX);
    }

}

?>
