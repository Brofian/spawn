<?php

namespace modules\Berichtsheft;

use webu\system\Core\Base\Controller\Controller;
use webu\system\core\Request;
use webu\system\core\Response;

class Berichtsheft extends Controller
{

    //The Main Controller for the module

    public static function getControllerAlias(): string
    {
        return 'Berichtsheft';
    }

    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
            'test' => 'test'
        ];
    }


    public function index(Request $request, Response $response)
    {
        echo "Berichtsheft Index Action";
    }

    public function test(Request $request, Response $response)
    {
        echo "Berichtsheft Test Action";
    }

}