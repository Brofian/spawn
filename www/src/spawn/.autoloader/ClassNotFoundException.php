<?php declare(strict_types=1);

namespace spawn\autoloader;

use Exception;

class ClassNotFoundException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct(
            "Could not autoload class \"$class\"! Please check if it exists",
            001
        );
    }

}