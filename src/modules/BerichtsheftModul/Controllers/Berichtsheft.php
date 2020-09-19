<?php

namespace modules\BerichtsheftModul\Controllers;

use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Helper\ModuleHelper;
use webu\system\Core\Helper\RoutingHelper;
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


        $twig->assign('name', 'Angelina');

        //echo "Berichtsheft Index Action";
        $twig->addTemplateDir($this->getCurrentModulePath($request) . '\\Resources');
    }

    public function test(Request $request, Response $response, int $i)
    {
        //echo "Berichtsheft Test Action: " . $i;
    }

}