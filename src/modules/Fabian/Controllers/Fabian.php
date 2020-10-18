<?php

namespace modules\Fabian\Controllers;

use webu\system\Core\Base\Controller\Controller;
use webu\system\core\Request;
use webu\system\core\Response;

class Fabian extends Controller
{


    //The Main Controller for the module

    public static function getControllerAlias(): string
    {
        return 'Fabian';
    }

    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
        ];
    }

    /**
     * @return array
     */
    public static function getAdditionalFunctionParams() : array {
        return  [
            //function name => [list of additional param identifiers (strings)]
            'index' => [],
        ];
    }


    public function index(Request $request, Response $response)
    {

        $twig = $response->getTwigHelper();


        //assign variables to the twig template
        $twig->assign('name', 'Fabian');

        //set the starting file for the rendering and add the template dir for this module
        $twig->setRenderFile('base.html.twig');
        $twig->addTemplateDir($this->getCurrentModulePath($request) . '\\Resources\\template');
    }

}