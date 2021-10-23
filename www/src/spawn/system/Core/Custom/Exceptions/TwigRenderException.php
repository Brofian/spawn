<?php

namespace spawn\system\Core\Custom\Extensions;

use spawn\system\Throwables\AbstractException;
use Throwable;

class TwigRenderException extends AbstractException {

    public function __construct(string $filepath, Throwable $previous = null)
    {
        parent::__construct([
            'filepath' => $filepath
        ], $previous);
    }

    protected function getMessageTemplate(): string
    {
        return 'Error when rendering template %filepath%!';
    }

    protected function getExitCode(): int
    {
        return 34;
    }
}