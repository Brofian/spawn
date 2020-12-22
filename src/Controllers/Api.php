<?php

namespace modules\Main\Controllers;

use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Custom\Debugger;
use webu\system\core\Request;
use webu\system\core\Response;

class Api extends Controller {

    /**
     * @inheritDoc
     */
    public static function getControllerAlias(): string
    {
        return 'api';
    }

    /**
     * @inheritDoc
     */
    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
            'debug' => 'debug',
        ];
    }

    public function onControllerStart(Request $request, Response $response) {}

    public function onControllerStop(Request $request, Response $response) {}



    public function index(Request $request, Response $response) {

    }

    public function debug(Request $request, Response $response) {
        $output = [
            "answerCode" => 1234
        ];

        $response->getTwigHelper()->setOutput(json_encode($output));
    }



}