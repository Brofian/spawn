<?php

namespace webu\system\Core\Base\Controller;


use webu\system\Core\Helper\RoutingHelper;
use webu\system\core\Request;
use webu\system\core\Response;

abstract class Controller
{

    /**
     * Has to be declared in every controller!
     * @return string
     */
    abstract public static function getControllerAlias() : string;

    /**
     *  return [
     *      '' => 'index',
     *  ];
     *
     * @return array
     */
    abstract public static function getControllerRoutes() : array;

    /**
     * Optional
     * @return array
     */
    public static function getAdditionalFunctionParams(): array {
        return [];
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getCurrentModulePath(Request $request) : string {
        /** @var RoutingHelper $routing */
        $moduleHelper = $request->getRouting()->getModuleHelper();
        $module = $moduleHelper->getCurrentController()->getModule();

        return $module->getAbsolutePath();
    }


    public function index(Request $request, Response $response)
    {
        echo 'Main Index Action';
    }


}