<?php declare(strict_types=1);

namespace webu\system\Throwables;

class ClassNotFoundException extends AbstractException
{
    protected function getMessageTemplate(): string
    {
        return 'The class %class% could\'nt be loaded! Check if the file exists!';
    }

    protected function getExitCode(): int
    {
        return 50;
    }
}