<?php declare(strict_types=1);

namespace spawn\system\Core\Extensions\Twig;


use spawn\system\Core\Base\Extensions\Twig\FilterExtension;
use spawn\system\Core\Contents\Modules\ModuleCollection;
use spawn\system\Core\Helper\RoutingHelper;

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