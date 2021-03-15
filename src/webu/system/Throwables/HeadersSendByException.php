<?php

namespace webu\system\Throwables;

use Throwable;

class HeadersSendByException extends \Exception
{

    protected $message = "The headers were already send by!";
    protected $code = 422;
    protected $file = "";
    protected $line = "";
    protected $trace = [];
    protected $traceAsString = "";
    protected $previous = null;


    public function  __construct()
    {
        parent::__construct();

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