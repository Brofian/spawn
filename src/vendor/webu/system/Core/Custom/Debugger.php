<?php

namespace webu\system\Core\Custom;

class Debugger {


    public static function dump($var) {
        $backtrace = debug_backtrace();

        echo "<div style='background: #FFAAAA; border: 2px solid black; padding:10px'>
                At: <b>" . $backtrace[0]["file"] . ":" . $backtrace[0]["line"] ."</b>
                <pre>";
        var_dump($var);
        echo "</pre></div>";

        die();
    }


}