<?php

namespace webu\modules\index\Controller;

use webu\system\Core\Base\Controller\BaseController;
use webu\system\core\Request;
use webu\system\core\Response;


class Api extends BaseController
{


    public function onControllerStart(Request $request, Response $response)
    {
        //this is just an api, so return true by default
        $response->getTwigHelper()->setOutput(json_encode(true));
    }

    /*
     *
     * Callable Actions
     *
     */


    public function contactFormSubmit(Request $request, Response $response) {

        sleep(5);

        $response->getTwigHelper()->setOutput(json_encode([
            "result"=>"hllo world"
        ]));
    }


}