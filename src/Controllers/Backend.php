<?php

namespace modules\Main\Controllers;

use webu\system\Core\Base\Controller\Controller;
use webu\system\core\Request;
use webu\system\core\Response;

class Backend extends Controller {

    /**
     * @inheritDoc
     */
    public static function getControllerAlias(): string
    {
        return 'backend';
    }

    /**
     * @inheritDoc
     */
    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
            'login' => 'login'
        ];
    }

    public function onControllerStart() {}

    public function onControllerStop() {}



    public function index(Request $request, Response $response) {

    }


    public function login(Request $request, Response $response) {

    }


}