<?php

namespace webu\system\Core\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use webu\system\Core\Base\Extensions\Twig\FunctionExtension;

class LinkFunctionExtension extends FunctionExtension
{

    protected function getFunctionName(): string
    {
        return "link";
    }

    protected function getFunctionFunction(): callable
    {
        return function ($relativeLink) {
            if($relativeLink[0] != '/') {
                $relativeLink = '/' . $relativeLink;
            }

            return MAIN_ADDRESS_FULL . $relativeLink;
        };
    }

    protected function getFunctionOptions(): array
    {
        return [];
    }
}