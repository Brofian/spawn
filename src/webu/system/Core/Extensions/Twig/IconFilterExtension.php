<?php

namespace webu\system\Core\Extensions;


use webu\system\Core\Base\Extensions\Twig\FilterExtensionInterface;

class IconFilterExtension extends FilterExtensionInterface
{

    protected function getFilterName(): string
    {
        return "icon";
    }

    protected function getFilterFunction(): callable
    {
        return function($iconPath) {
           return "->$iconPath<-";
        };

        return function($iconPath) {
            $svgFile = file_get_contents($iconPath);
            return $svgFile ?? "Icon not found!";
        };
    }
}