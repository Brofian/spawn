<?php

namespace modules\BerichtsheftModul\Controllers;

use webu\system\Core\Base\Controller\Controller;
use webu\system\core\Request;
use webu\system\core\Response;

class blabla extends Controller {

    /**
     * @inheritDoc
     */
    public static function getControllerAlias(): string
    {
        return "blabla";
    }

    /**
     * @inheritDoc
     */
    public static function getControllerRoutes(): array
    {
        return [
          '' => 'index'
        ];
    }


    public function index(Request $request, Response $response) {
        echo "blabla Index action";
    }
}