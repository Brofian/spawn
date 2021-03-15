<?php

namespace webu\modules\Index\Controller;

use webu\system\Core\Base\Controller\BaseController;
use webu\system\core\Request;
use webu\system\core\Response;


class Index extends BaseController {


    public function onControllerStart(Request $request, Response $response)
    {
        $request->getContext()->set('main_navigation', $this->createMainNavigation());
    }


    /*
     *
     * Callable Actions
     *
     */

    public function index(Request $request, Response $response) {
    }

    public function impress(Request $request, Response $response) {
    }

    public function contact(Request $request, Response $response) {
        $response->getTwigHelper()->assign("form-target", "");
    }



    /*
     *
     *  Custom Functions for this controller
     *
     */
    public static function createMainNavigation() {
        return [
            'module.index.index.index' => "Home",
            'module.index.index.contact' => "Kontakt",
            'module.index.projects.list' => "Projekte"
        ];
    }

}