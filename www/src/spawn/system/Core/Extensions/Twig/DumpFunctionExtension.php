<?php

namespace spawn\system\Core\Extensions\Twig;


use spawn\system\Core\Extensions\Twig\Abstracts\FunctionExtension;

class DumpFunctionExtension extends FunctionExtension
{

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return "dump";
    }


    /**
     * @return callable
     */
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

    /**
     * @return array
     */
    protected function getFunctionOptions(): array
    {
        return [
            'needs_context' => true,
        ];
    }
}