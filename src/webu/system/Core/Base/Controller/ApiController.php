<?php

namespace webu\system\Core\Base\Controller;

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
}