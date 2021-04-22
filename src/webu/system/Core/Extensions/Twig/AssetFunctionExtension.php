<?php

namespace webu\system\Core\Extensions\Twig;

use webu\system\Core\Base\Extensions\Twig\FunctionExtension;
use webu\system\Core\Contents\Modules\ModuleNamespacer;
use webu\system\Core\Helper\URIHelper;

class AssetFunctionExtension extends FunctionExtension
{

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return "assetPath";
    }


    /**
     * @return callable
     */
    protected function getFunctionFunction(): callable
    {
        return function ($namespace, $doHash = false) {

            if($doHash) {
                $namespace = ModuleNamespacer::hashRawNamespace($namespace);
            }

            return URIHelper::createPath([
                MAIN_ADDRESS_FULL,
                "var",
                "cache",
                "public",
                $namespace,
            ], "/");
        };
    }

    /**
     * @return array
     */
    protected function getFunctionOptions(): array
    {
        return [];
    }
}