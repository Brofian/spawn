<?php

namespace modules\Main;

use webu\system\Core\Base\Controller\Controller;
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
            '' => 'index',
        ];
    }


    public function index(Request $request, Response $response)
    {
        echo '<link rel="icon" type="image/png" href="favicon.png">';
        echo "Main Index Action";
    }

}