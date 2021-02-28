<?php

namespace webu\system\Core\Extensions\Twig;

use webu\system\Core\Base\Extensions\Twig\FunctionExtension;
use webu\system\Core\Helper\URIHelper;

class AssetFunctionExtension extends FunctionExtension
{
    protected function getFunctionName(): string
    {
        return "assetPath";
    }

    protected function getFunctionFunction(): callable
    {
        return function ($namespace) {
            return URIHelper::createPath([
                MAIN_ADDRESS_FULL,
                "var",
                "cache",
                "public",
                $namespace
            ], "/");
        };
    }

    protected function getFunctionOptions(): array
    {
        return [];
    }
}