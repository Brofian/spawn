<?php

namespace webu\system\Core\Extensions\Twig;


use webu\system\Core\Base\Extensions\Twig\FilterExtension;

class IconFilterExtension extends FilterExtension
{

    protected function getFilterName(): string
    {
        return "icon";
    }

    protected function getFilterFunction(): callable
    {
        return function($icon, $additionalClasses = "") {
            $iconPath = ROOT . "\\src\\Resources\\public\\assets\\Backend\\icons\\" . $icon . ".svg";

            if(!file_exists($iconPath)) {
                return $iconPath;
            }

            $svgFile = file_get_contents($iconPath);
            $svgFile = "<span class='icon ".$additionalClasses."'>" . $svgFile . "</span>";
            return $svgFile ?? "Icon not found!";
        };
    }

    protected function getFilterOptions(): array
    {
        return [
            'is_safe' => ['html']
        ];
    }
}