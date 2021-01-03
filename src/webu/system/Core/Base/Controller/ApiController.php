<?php

namespace webu\system\Core\Base\Controller;

use webu\system\Core\Custom\Debugger;
use webu\system\core\Request;
use webu\system\core\Response;

abstract class ApiController extends Controller {

    public function isApi() {
        return true;
    }

    public function init(Request $request, Response $response) {
        $this->twig = $response->getTwigHelper();
        $this->twig->assign('controller', $this->getControllerAlias());
        $this->twig->assign('action', $request->getRequestActionPath());
        $this->twig->setRenderFile("blank.html.twig");

        $this->onControllerStart($request, $response);
    }


}