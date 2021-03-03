<?php

namespace webu\system\Core\Helper;



use webu\system\Core\Base\Controller\BaseController;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleAction;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleController;
use webu\system\Core\Helper\FrameworkHelper\CUriConverter;

class RoutingHelper
{

    /** @var ModuleCollection */
    private $routeList;

    public function __construct(ModuleCollection $moduleCollection)
    {
        $this->routeList = $this->initURIRegex($moduleCollection);
    }


    /**
     * @param ModuleCollection $moduleCollection
     * @return array
     */
    private function initURIRegex(ModuleCollection $moduleCollection) {
        $routeList = array();


        /** @var Module $module */
        foreach($moduleCollection->getModuleList() as $module) {

            /** @var ModuleController $controller */
            foreach($module->getModuleControllers() as $controller) {

                /** @var ModuleAction $action */
                foreach($controller->getActions() as $action) {
                    $uriVars = [];
                    $newUri = CUriConverter::cUriToRegex($action->getCustomUrl(), $uriVars);
                    $routeList[$action->getId()] = [
                        "uri" => $newUri,
                        "uri_vars" => $uriVars,
                        "module" => $module,
                        "controller" => $controller,
                        "method" => $action->getAction()
                    ];
                }
            }
        }

        return $routeList;
    }


    /**
     * @param string $path
     * @return bool|mixed
     */
    public function route(string $path = "") {

        $path = "/" . $path;

        foreach($this->routeList as $routeItem) {
            if(preg_match($routeItem["uri"], $path)) {
                return $routeItem;
            }
        }

        return false;
    }

}