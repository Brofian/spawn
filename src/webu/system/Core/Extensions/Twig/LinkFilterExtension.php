<?php

namespace webu\system\Core\Extensions\Twig;


use webu\system\Core\Base\Extensions\Twig\FilterExtension;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleAction;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleController;
use webu\system\Core\Helper\FrameworkHelper\CUriConverter;
use webu\system\Core\Helper\RoutingHelper;

class LinkFilterExtension extends FilterExtension
{

    /**
     * @return string
     */
    protected function getFilterName(): string
    {
        return "link";
    }

    /**
     * @return callable
     */
    protected function getFilterFunction(): callable
    {
        return function($context, $actionId, $parameters = []) {

            /** @var ModuleCollection $moduleCollection */
            $moduleCollection = $context["context"]["ModuleCollection"];

            return RoutingHelper::getLinkByIdFromCollection($actionId, $moduleCollection, $parameters);
        };
    }

    /**
     * @return array
     */
    protected function getFilterOptions(): array
    {
        return [
            'needs_context' => true,
            'is_safe' => ['html']
        ];
    }
}