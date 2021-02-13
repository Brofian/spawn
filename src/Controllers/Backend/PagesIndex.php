<?php

namespace modules\Main\Controllers\Backend;


use webu\system\Core\Base\Controller\Controller;
use webu\system\core\Request;
use webu\system\core\Response;

class PagesIndex {

    /** @var Controller */
    protected $parentController;

    public function __construct(Controller $parentController)
    {
        $this->parentController = $parentController;
    }


    public function init(Request $request, Response $response) {

    }


}