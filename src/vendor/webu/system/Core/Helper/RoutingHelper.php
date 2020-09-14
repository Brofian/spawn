<?php

namespace webu\system\Core\Helper;

use webu\system\Core\Custom\Debugger;

class RoutingHelper
{

    //these routes can be set by the user or modules(maybe via backend?)
    public $specialRoutes = [
        //pattern:
        // 'controller:action' => controller class path
    ];

    //these routes are set in the config.php and must exist
    public $systemRoutes = [
        '404:index' => NOTFOUNDCONTROLLER
    ];


    protected $routing = [
        'controller' => '',
        'action' => '',
    ];

    public function route($controller, $action)
    {

        //check special routes, set by a module
        if($this->checkSpecialRoutes($controller . ':' . $action)) {
            return $this->routing;
        }

        //check system routes, set in the config.php (404,505, etc.)
        if($this->checkSystemRoutes($controller . ':' . $action)) {
            return $this->routing;
        }

        //check the routes, set inside of a module
        if($this->checkModuleRoutes($controller, $action)) {
            return $this->routing;
        }


        $error = "Path " . $action . '" in Controller "'. $controller .'" not found!';
        Debugger::dump($error); //ends with die()
        return 'This return statement cannot be reached anyways';
    }

    /**
     * @param string $identifier
     * @return bool|mixed
     */
    private function checkSpecialRoutes(string $identifier) : string
    {
        if (isset($this->specialRoutes[$identifier])) {
            $item = explode(':', $this->specialRoutes[$identifier]);
            $this->routing['controller'] = $item[0];
            $this->routing['action'] = $item[1];
            return true;
        }
        return false;
    }


    /**
     * @param string $identifier
     * @return bool
     */
    private function checkSystemRoutes(string $identifier) : bool
    {
        if (isset($this->systemRoutes[$identifier])) {
            $item = explode(':', $this->systemRoutes[$identifier]);
            $this->routing['controller'] = $item[0];
            $this->routing['action'] = $item[1];
            return true;
        }
        return false;
    }


    /**
     * @param string $reqController
     * @param string $reqAction
     * @return bool
     */
    private function checkModuleRoutes(string $reqController, string $reqAction) : bool
    {
        $moduleHelper = new ModuleHelper();
        $moduleHelper->loadModules();

        $controller = $moduleHelper->getModuleByAlias($reqController);

        if($controller === false) {
            //fallback
            $controller = $moduleHelper->getModuleByAlias(DEFAULTCONTROLLER);

            if ($controller === false) {
                return false;
            }
        }


        $actions = $controller::getControllerRoutes();
        if(isset($actions[$reqAction])) {
            $action = $actions[$reqAction];
        }
        else {
            return false;
        }

        $this->routing['controller'] = $controller;
        $this->routing['action'] = $action;
        return true;
    }


}