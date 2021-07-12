<?php

namespace webu\system\Core\Custom;

class StringConverter {


    public static function snakeToPascalCase($string) {
        $string = strtolower($string);

        $stringParts = explode("-", $string);
        $string = "";
        foreach($stringParts as $part) {
            $string .= ucFirst($part);
        }
        return $string;
    }

    public static function pascalToSnakeCase($string) {
        $letters = str_split($string);
        $string = "";
        foreach($letters as $letter) {
            if(ctype_lower($letter) || $string == "") {
                $string .= $letter;
            }
            else {
                $string .= "-" . $letter;
            }
        }

        return strtolower($string);
    }


}