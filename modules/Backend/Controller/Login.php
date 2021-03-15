<?php

namespace webu\modules\Backend\Controller;

use webu\modules\Backend\Models\SidebarElement;
use webu\system\Core\Base\Controller\BaseController;
use webu\system\core\Request;
use webu\system\core\Response;


class Login extends BaseController {


    /*
     *
     * Callable Actions
     *
     */

    public function index(Request $request, Response $response) {
        $response->getTwigHelper()->assign("isLoginPage", true);
    }


}