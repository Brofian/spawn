<?php

namespace modules\Fabian;

use webu\system\Core\Module\Module;
use webu\system\core\Request;
use webu\system\core\Response;

class Fabian extends Module {


    public function init(Request $request, Response $response) {
        $response->getTwigHelper()->addTemplateDir(__DIR__ . '\\Resources\\template');
    }


    public function install() {

    }

    public function uninstall() {

    }

}