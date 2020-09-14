<?php

namespace webu\system\Core\Custom;

class Debugger {


    private static function writeBacktrace($var) {
        $string = '';

        $backtrace = debug_backtrace();
        $string .= "
                <div style='background: #FFAAAA; border: 2px solid black; padding:10px'>
                    At: <b>" . $backtrace[0]["file"] . ":" . $backtrace[0]["line"] ."</b>
                    <pre>";
        $string .= var_dump($var);
        $string .= "</pre></div>";

        return $string;
    }


    public static function mdump($var) {

        echo self::writeBacktrace($var);

    }

    public static function dump($var) {

        echo self::writeBacktrace($var);

        die();
    }


}