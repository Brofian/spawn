<?php

namespace webu\system\Core\Extensions\Twig;


use webu\system\Core\Base\Extensions\Twig\FilterExtension;
use webu\system\Core\Contents\Modules\ModuleNamespacer;
use webu\system\Core\Helper\URIHelper;

class IconFilterExtension extends FilterExtension
{

    protected function getFilterName(): string
    {
        return "icon";
    }

    protected function getFilterFunction(): callable
    {
        return function($context, $icon, $additionalClasses = "") {

            $namespace = ModuleNamespacer::getGlobalNamespace();
            if(isset($context["namespace"])) {
                $namespace = $context["namespace"];
            }

            $iconPath = URIHelper::createPath([
                ROOT . CACHE_DIR,
                "public",
                $namespace,
                "assets",
                "icons",
                $icon.".svg"
            ]);


            if(!file_exists($iconPath)) {
                if(MODE == 'dev')   return "Icon \"" . $iconPath . "\" not found!";
                else                return "Missing icon";
            }

            $svgFile = file_get_contents($iconPath);
            $svgFile = "<span class='icon ".$additionalClasses."'>" . $svgFile . "</span>";
            return $svgFile;
        };
    }

    protected function getFilterOptions(): array
    {
        return [
            'needs_context' => true,
            'is_safe' => ['html']
        ];
    }
}