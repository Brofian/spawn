<?php

namespace webu\system\Throwables;

use Throwable;

class NoActionFoundInControllerException extends AbstractException
{
    public function __construct(string $action, string $controller, Throwable $previous = null)
    {
        parent::__construct(
            [$action, $controller],
            $previous
        );
    }

    protected function getMessageTemplate(): string
    {
        return 'Action %action% not found in %controller%';
    }

    protected function getExitCode(): int
    {
        return 54;
    }
}