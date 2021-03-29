<?php

namespace webu\system\Core\Extensions\Twig;

use webu\system\Core\Base\Extensions\Twig\FunctionExtension;

class DumpFunctionExtension extends FunctionExtension
{
    protected function getFunctionName(): string
    {
        return "dump";
    }

    protected function getFunctionFunction(): callable
    {
        return function ($context, $var = "nothingtoseehere") {
            if($var == "nothingtoseehere") {
                dump($context);
            }
            else {
                dump($var);
            }
        };
    }

    protected function getFunctionOptions(): array
    {
        return [
            'needs_context' => true,
        ];
    }
}