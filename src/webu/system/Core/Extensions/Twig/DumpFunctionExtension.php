<?php

namespace webu\system\Core\Extensions\Twig;

use webu\system\Core\Base\Extensions\Twig\FunctionExtension;

class DumpFunctionExtension extends FunctionExtension
{
    protected function getFunctionName(): string
    {
        return "d";
    }

    protected function getFunctionFunction(): callable
    {
        return function ($var = "nothing to see here") {
            dump($var);
        };
    }

    protected function getFunctionOptions(): array
    {
        return [];
    }
}