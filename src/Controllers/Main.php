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
            '' => 'index',      //creates the route "yoururl.com/main" and assigns the method "index" as the executioner
            'test' => 'test',
        ];
    }

    public static function getAdditionalFunctionParams() : array {
        return  [
            //function name => [list of additional param identifiers]
            'index' => [],
            'test' => [
                'DebugInteger' //see list in RoutingHelper:148
            ]
        ];
    }


    public function index(Request $request, Response $response)
    {
        $twig = $response->getTwigHelper();

        //assign variable to template
        $twig->assign('name', 'Fabian in Main');
        //change the render file
        $twig->setRenderFile('base.html.twig');
    }

    public function test(Request $request, Response $response)
    {
        $response->getTwigHelper()->assign('name', 'Fabian in Main test');
    }

}