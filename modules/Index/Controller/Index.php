<?php

namespace webu\modules\index\Controller;

use webu\system\Core\Base\Controller\BaseController;
use webu\system\core\Request;
use webu\system\core\Response;


class Index extends BaseController {


    public function onControllerStart(Request $request, Response $response)
    {
    }

    public function index(Request $request, Response $response) {
        //$response->getTwigHelper()->setRenderFile("test.html.twig");
    }

    public function page(Request $request, Response $response) {

    }
}