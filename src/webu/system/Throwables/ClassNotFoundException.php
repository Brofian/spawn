<?php declare(strict_types=1);

namespace webu\system\Throwables;

use Throwable;

class ClassNotFoundException extends \Exception
{

    /** @var string  */
    protected $message = "The class \"{#1}\" could'nt be loaded! Check if the file exists!";
    /** @var int  */
    protected $code = 421;
    /** @var mixed|string  */
    protected $file = "";
    /** @var mixed|string  */
    protected $line = "";
    /** @var array  */
    protected $trace = [];
    /** @var string|void  */
    protected $traceAsString = "";
    /** @var \Exception|null  */
    protected $previous = null;


    public function  __construct(string $className)
    {
        parent::__construct();

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