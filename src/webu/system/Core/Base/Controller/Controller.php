<?php

namespace webu\system\Core\Base\Controller;


use webu\system\Core\Custom\Debugger;
use webu\system\Core\Helper\RoutingHelper;
use webu\system\core\Request;
use webu\system\core\Response;

abstract class Controller implements ControllerInterface
{

    protected $twig;

    public function init(Request $request, Response $response) {
        $this->twig = $response->getTwigHelper();
        $this->twig->assign('controller', $this->getControllerAlias());
        $this->twig->assign('action', $request->getRequestActionPath());
        $this->onControllerStart($request, $response);
    }

    public function end(Request $request, Response $response) {
        $this->onControllerStop($request, $response);
    }



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



    public function index(Request $request, Response $response)
    {
        echo 'Undefined Index Action';
    }


}