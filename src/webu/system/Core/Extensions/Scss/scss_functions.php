<?php


namespace webu\system\Core\Extensions\Scss;

use ScssPhp\ScssPhp\Compiler;

class scss_functions {


    public static function registerFunctions(Compiler &$compiler)
    {
        $compiler->registerFunction("unitize-test", function ($args) {
            $inp = $args[0][1];

            die();
        });

    }


}