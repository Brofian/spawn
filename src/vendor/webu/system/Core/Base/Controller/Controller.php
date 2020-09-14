<?php

namespace webu\system\Core\Base\Controller;


use webu\system\core\Request;
use webu\system\core\Response;

class Controller
{


    /**
     * Has to be declared in every controller!
     * @return string
     */
    public static function getControllerAlias(): string
    {
        return '';
    }


    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
        ];
    }

    public function index(Request $request, Response $response)
    {
        echo 'Main Index Action';
    }


}