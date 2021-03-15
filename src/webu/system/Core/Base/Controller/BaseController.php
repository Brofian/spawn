<?php

namespace webu\system\Core\Base\Controller;


use webu\system\Core\Custom\Debugger;
use webu\system\Core\Helper\RoutingHelper;
use webu\system\Core\Helper\TwigHelper;
use webu\system\core\Request;
use webu\system\core\Response;

abstract class BaseController
{

    /** @var bool */
    protected $stopExecution = false;

    /** @var TwigHelper */
    protected $twig;


    public final function init(Request $request, Response $response)
    {
        $this->twig = $response->getTwigHelper();
        //$this->twig->assign('controller', $this->getControllerAlias());
        //$this->twig->assign('action', $request->getRequestActionPath());
        $this->onControllerStart($request, $response);
    }

    public final function end(Request $request, Response $response)
    {
        $this->onControllerStop($request, $response);
    }

    protected function onControllerStart(Request $request, Response $response)
    {
    }

    protected function onControllerStop(Request $request, Response $response)
    {
    }

    protected final function stopExecution()
    {
        $this->stopExecution = true;
    }

    public final function isExecutionStopped()
    {
        return $this->stopExecution;
    }


    public function index(Request $request, Response $response)
    {
        echo 'Undefined Index Action';
    }


    protected function setJsonOutput($outputValue)
    {
        $this->twig->setOutput(json_encode($outputValue));
    }


}