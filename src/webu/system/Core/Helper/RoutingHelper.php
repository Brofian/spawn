<?php

namespace webu\system\Core\Helper;



use webu\system\Core\Base\Controller\BaseController;
use webu\system\Core\Contents\Modules\Module;
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

                foreach($controller->getActions() as $uri => $method) {
                    $newUri = CUriConverter::cUriToRegex($uri);
                    $routeList[$newUri] = [
                        "module" => $module,
                        "controller" => $controller,
                        "method" => $method
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
    public function route(string $path = "/") {

        if($path == "") $path = "/";

        foreach($this->routeList as $routePattern => $item) {
            if(preg_match($routePattern, $path)) {
                return $item;
            }
        }

        return false;
    }

}