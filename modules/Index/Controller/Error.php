<?php

namespace webu\modules\Index\Controller;

use webu\system\Core\Base\Controller\BaseController;
use webu\system\core\Request;
use webu\system\core\Response;

class Error extends BaseController {

    public function onControllerStart(Request $request, Response $response)
    {
        $request->getContext()->set('main_navigation', Index::createMainNavigation());
    }

    public function error404(Request $request, Response $response) {
    }


}