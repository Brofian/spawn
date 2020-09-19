<?php

namespace modules\Main\Controllers;

use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Module\Module;
use webu\system\core\Request;
use webu\system\core\Response;

class Main extends Controller
{
    //The Main Controller for the module

    public static function getControllerAlias(): string
    {
        return 'Main';
    }

    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index', //creates the route "yoururl.com/main" and assigns the method "index" as the executioner
        ];
    }


    public function index(Request $request, Response $response)
    {
        //echo "Main Index Action";
    }

}