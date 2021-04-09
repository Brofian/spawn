<?php

namespace webu\system\Core\Custom;

class Logger
{

    const logdir = ROOT . '/var/.log/';
    const accesslog = 'access-log.txt';
    const errorlog = 'error-log.txt';
    const devlog = 'dev-log.txt';


    public function __construct()
    {
    }


    public static function writeToAccessLog(string $text, string $title = '')
    {
        $string = self::getCurrentTime();
        if ($title != '') {
            $string .= $title;
            $string .= PHP_EOL;
        }
        $string .= $text;
        $string .= PHP_EOL;

        $log = self::logdir . self::accesslog;
        if(!is_file($log)) {
            mkdir(self::logdir);
        }
        file_put_contents($log, $string, FILE_APPEND);
    }

    public function clearAccessLog()
    {
        $log = self::logdir . self::accesslog;
        file_put_contents($log, '');
    }

    public static function writeToErrorLog(string $text, string $title = '')
    {
        $string = self::getCurrentTime();
        if ($title != '') {
            $string .= $title;
        }
        $string .= PHP_EOL;
        $string .= $text;
        $string .= PHP_EOL;

        $log = self::logdir . self::errorlog;
        file_put_contents($log, $string, FILE_APPEND);
    }

    public function clearErrorLog()
    {
        $log = self::logdir . self::errorlog;
        file_put_contents($log, '');
    }

    public static function getCurrentTime()
    {
        return '[' . date('Y-m-d h:i:s') . '] ';
    }


    public static function writeToDevlog(string $text, string $title = '')
    {
        $string = self::getCurrentTime();
        if ($title != '') {
            $string .= $title;
            $string .= PHP_EOL;
        }
        $string .= $text;
        $string .= PHP_EOL;

        $log = self::logdir . self::devlog;
        if(!is_file($log)) {
            mkdir(self::logdir);
        }
        file_put_contents($log, $string, FILE_APPEND);
    }
}