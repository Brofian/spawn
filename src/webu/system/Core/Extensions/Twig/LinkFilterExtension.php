<?php

namespace webu\system\Core\Extensions\Twig;


use webu\system\Core\Base\Extensions\Twig\FilterExtension;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleAction;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleController;
use webu\system\Core\Helper\FrameworkHelper\CUriConverter;

class LinkFilterExtension extends FilterExtension
{

    /** @var ModuleCollection */
    public $moduleCollection = null;

    protected function getFilterName(): string
    {
        return "link";
    }

    protected function getFilterFunction(): callable
    {
        return function($context, $actionId, $parameters = []) {

            if(!$actionId || !isset($context["context"]["ModuleCollection"])) {
                return MAIN_ADDRESS_FULL . "/";
            }

            /** @var ModuleCollection $moduleCollection */
            $moduleCollection = $context["context"]["ModuleCollection"];

            /** @var Module $module */
            foreach($moduleCollection->getModuleList() as $module) {
                /** @var ModuleController $controller */
                foreach($module->getModuleControllers() as $controller) {
                    /** @var ModuleAction $action */
                    foreach($controller->getActions() as $action) {

                        if($action->getId() == $actionId) {
                            return MAIN_ADDRESS_FULL . CUriConverter::cUriToUri($action->getCustomUrl(), $parameters);
                        }

                    }
                }
            }

            return MAIN_ADDRESS_FULL . "/";
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