<?php


namespace Helpers\Workers;


class LanguagesCode {

    const languageCode = 'languageCode';

    const ru = 'ru';
    const en = 'en';
    const es = 'es';

    const languages = [self::ru, self::en, self::es];


    public static function getDefaultValue(): string
    {
        return self::en;
    }


    public static function isLanguageCodeInPost(): bool
    {
        if (array_key_exists(self::languageCode, $_POST))
        {
            return true;
        }

        return false;
    }

    public static function getLanguageCodePost(): string
    {
        return self::checkAndGetLanguageCode($_POST[self::languageCode]);
    }



    public static function isLanguageCodeInCookie(): bool
    {
        if (array_key_exists(self::languageCode, $_COOKIE))
        {
            return true;
        }

        return false;
    }


    public static function getLanguageCodeCookie(): string
    {
        return self::checkAndGetLanguageCode($_COOKIE[self::languageCode]);
    }


    public static function setLanguageCodeCookie(string $code): void
    {
        setcookie(
            self::languageCode,
            $code,
            time() + 10 * 365 * 24 * 60 * 60
        );
    }



    public static function getLanguageCodeBrowserDefault(): string
    {

        $code = self::checkAndGetLanguageCode(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

        if ($code)
        {
            return $code;
        }
        else
        {
            return self::ru;
        }
    }


    public static function checkAndGetLanguageCode($code): string
    {
        if (in_array($code, self::languages))
        {
            return $code;
        }

        return self::en;
    }


    public static function getLanguageCodeGeneralWayForSession(): string
    {
        if (self::isLanguageCodeInCookie())
        {
            return self::getLanguageCodeCookie();
        }

        return self::getLanguageCodeBrowserDefault();
    }

}