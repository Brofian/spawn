<?php

namespace webu\system\Throwables;

use Throwable;

class NoControllerFoundException extends AbstractException
{
    public function __construct(string $controller, Throwable $previous = null)
    {
        parent::__construct([$controller], $previous);
    }

    protected function getMessageTemplate(): string
    {
        return 'Controller "%controller%" not found';
    }

    protected function getExitCode(): int
    {
        return 54;
    }
}