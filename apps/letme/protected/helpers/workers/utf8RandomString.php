<?php

namespace Helpers\Workers;

use IntlChar;

class Utf8RandomString {

    public static function get($length): string{
        $string = '';
        $i = 0;
        while($i < $length){
            $char = IntlChar::chr(mt_rand(1, 57343));
            if( $char != null && IntlChar::isprint($char) ){
                $string .= $char;
                $i++;
            }
        }
        return $string;
    }

}

?>