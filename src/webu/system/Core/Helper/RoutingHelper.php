<?php

namespace webu\system\Core\Helper;

use webu\system\Core\Base\Controller\ApiController;
use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Module\Module;
use webu\system\Environment;

class RoutingHelper
{

    //these routes can be set by the user or modules(maybe via backend?)
    public $specialRoutes = [
        //pattern:
        // 'controller:action' => controller class path
    ];

    //these routes are set in the config.php and must exist
    public $systemRoutes = [
        '404:index' => NOTFOUNDCONTROLLER,
        '505:index' => ERRORCONTROLLER,
        'webu:db-setup' => 'webu\\actions\\DatabaseSetupAction'
    ];

    /** @var ModuleHelper  */
    private $moduleHelper = null;

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

        //check system routes, set in the config.php (404,505, etc.) and default api controllers
        if($this->checkSystemRoutes($controller . ':' . $action)) {
            return $this->routing;
        }

        //

        //check the routes, set inside of a module
        if($this->checkModuleRoutes($controller, $action)) {
            return $this->routing;
        }


        $error = 'Path "' . $action . '" in Controller "'. $controller .'" not found!';
        Debugger::ddump($error); //ends with die()
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
            /** @var Controller $controller */
            $controller = new $item[0]();

            $this->routing['controller'] = $controller;
            $this->routing['action'] = $controller::getControllerAlias();
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
            /** @var Controller $controller */
            $controller = new $item[0]();

            $this->routing['controller'] = $controller;
            $this->routing['action'] = $controller::getControllerRoutes()[''];
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

        $this->moduleHelper = $moduleHelper;

        $controller = $moduleHelper->getControllerByAlias($reqController);

        if($controller === false) {
            //fallback
            $controller = $moduleHelper->getControllerByAlias(DEFAULTCONTROLLER);

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


    /**
     * If a module requires custom Params, then add them
     *
     * @param Environment $environment
     * @param Controller $controller
     * @param string $action
     * @return array
     */
    public function addValuesToCustomParams(Environment $environment, Controller $controller, string $action) {

        //$controller will be from the controller class
        //required params is an array, containing the methods as keys
        $requiredParams = $controller::getAdditionalFunctionParams();

        //default params (always there)
        $params = [
            $environment->request,
            $environment->response
        ];

        foreach($requiredParams as $actionKey => $requiredParam) {

            //safety checks
            if(is_string($actionKey) == false) continue;
            if($actionKey != $action)           continue;

            foreach($requiredParam as $reqparams) {
                if($reqparams == 'DebugInteger') {
                    $params[] = 42;
                }
            }

        }

        return $params;

    }


    /**
     * @return ModuleHelper
     */
    public function getModuleHelper() : ModuleHelper {
        return $this->moduleHelper;
    }

}