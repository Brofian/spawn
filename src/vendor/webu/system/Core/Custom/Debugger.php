<?php

namespace webu\system\Core\Custom;

class Debugger
{


    //the format for dumping an variable
    private static function writeBacktrace($var, $backtrace)
    {
        $string = '';
;
        echo "
                <div style='background: #FFAAAA; border: 2px solid black; padding:10px'>
                    At: <b>" . $backtrace[0]["file"] . ":" . $backtrace[0]["line"] . "</b>
                    <pre>";
        var_dump($var);
        echo "</pre></div>";

        return $string;
    }


    //dumps the variable and continue system execution
    public static function dump($var)
    {

        echo self::writeBacktrace($var, debug_backtrace());

    }

    //dumps the variable and dies
    public static function ddump($var)
    {

        echo self::writeBacktrace($var, debug_backtrace());

        die();
    }


}