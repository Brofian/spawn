<?php

namespace bin\webu;

class IO {

    const DEFAULT_TEXT = "\e[39m";
    const BLACK_TEXT = "\e[30m";
    const RED_TEXT = "\e[31m";
    const GREEN_TEXT = "\e[32m";
    const YELLOW_TEXT = "\e[33m";
    const BLUE_TEXT = "\e[34m";
    const PURPLE_TEXT = "\e[35m";
    const CYAN_TEXT = "\e[36m";
    const LIGHT_GRAY_TEXT = "\e[37m";
    const DARK_GRAY_TEXT = "\e[90m";
    const LIGHT_RED_TEXT = "\e[91m";
    const LIGHT_GREEN_TEXT = "\e[92m";
    const LIGHT_YELLOW_TEXT = "\e[93m";
    const LIGHT_BLUE_TEXT = "\e[94m";
    const LIGHT_MAGENTA_TEXT = "\e[95m";
    const LIGHT_CYAN_TEXT = "\e[96m";
    const WHITE_TEXT = "\e[97m";





    const BLACK_BG = "\e[40m";
    const RED_BG = "\e[41m";
    const CYAN_BG = "\e[46m";
    const WHITE_BG = "\e[49m";

    const TAB = "   ";




    public static function print(string $text, string $flag = "") : bool {
        echo $flag . $text . self::DEFAULT_TEXT;
        return true;
    }

    public static function printLine(string $text, string $flag = "") : bool {
        echo $flag . $text . PHP_EOL . self::DEFAULT_TEXT;
        return true;
    }

    public static function endLine(string $flag = "") : bool {
        echo $flag . PHP_EOL . self::DEFAULT_TEXT;
        return true;
    }

    public static function printObject($object) {
        echo self::YELLOW_TEXT;
        var_dump($object);
        echo self::DEFAULT_TEXT;
    }

    public static function exec(string $cmd, bool $simplified = false, int &$errorCode = null) {
        $output = "";

        if($simplified) {
            $output = shell_exec($cmd);
            $errorCode = 0;
        }
        else {
            $output = exec($cmd, $output, $errorCode);
        }
        return $output;
    }

    public static function execInDir(string $cmd, string $dir, bool $simplified = false, int &$errorCode = null) {
        $currentDir = getcwd();

        if(!file_exists($dir) || !is_dir($dir)) {
            self::printLine("\"$dir\" is not a valid directory!", self::LIGHT_RED_TEXT);
            $errorCode = 1;
            return "\"$dir\" is not a valid directory!";
        }

        chdir($dir);

        $output = self::exec($cmd, $simplified, $errorCode);

        chdir($currentDir);


        return $output;
    }




}