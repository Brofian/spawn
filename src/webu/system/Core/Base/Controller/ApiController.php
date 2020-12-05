<?php

namespace webu\system\Core\Base\Controller;

use webu\system\core\Request;
use webu\system\core\Response;

class ApiController extends Controller {


    /**
     * Return the default api alias
     * @inheritDoc
     */
    public static function getControllerAlias(): string
    {
        return "api";
    }

    /**
     * Return no routes
     * @inheritDoc
     */
    public static function getControllerRoutes(): array
    {
        return [
            '' => 'run'
        ];
    }

    public function onControllerStart(Request $request, Response $response)
    {
        // TODO: Implement onControllerStart() method.
    }

    public function onControllerStop(Request $request, Response $response)
    {
        // TODO: Implement onControllerStop() method.
    }
}