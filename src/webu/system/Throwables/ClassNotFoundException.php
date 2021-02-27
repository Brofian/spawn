<?php

namespace webu\system\Throwables;

use Throwable;

class ClassNotFoundException extends \Exception
{

    protected $message = "The class \"{#1}\" could'nt be loaded! Check if the file exists!";
    protected $code = 421;
    protected $file = "";
    protected $line = "";
    protected $trace = [];
    protected $traceAsString = "";
    protected $previous = null;


    public function  __construct(string $className)
    {
        $this->message = str_replace("{#1}", $className, $this->message);
        if(defined('STDIN')) {
            $this->message = "\e[91m" . $this->message . "\e[39m" . PHP_EOL;
        }

        $this->file = debug_backtrace()[1]['file'];
        $this->line = debug_backtrace()[1]['line'];
        $this->trace = debug_backtrace();
        $this->traceAsString = debug_print_backtrace();
        $this->previous = new \Exception();
    }




    public function __toString() : string
    {
        return $this->message . PHP_EOL . $this->traceAsString . PHP_EOL;
    }
}