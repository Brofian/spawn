<?php

namespace spawn\system\Core\Custom\Extensions;

use spawn\system\Throwables\AbstractException;
use Throwable;

class InvalidCsrfTokenException extends AbstractException {

    public function __construct(string $unvalidatedPurpose, Throwable $previous = null)
    {
        parent::__construct([
            'purpose' => $unvalidatedPurpose
        ], $previous);
    }

    protected function getMessageTemplate(): string
    {
        return 'Could not verify CSRF Token!';
    }

    protected function getExitCode(): int
    {
        return 38;
    }
}