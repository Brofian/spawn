<?php

namespace modules\Main\Controllers\Backend;

use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Database\Models\Variable;
use webu\system\core\Request;
use webu\system\core\Response;

class VariablesIndex {

    /** @var Controller */
    protected $parentController;

    public function __construct(Controller $parentController)
    {
        $this->parentController = $parentController;
    }


    public function init(Request $request, Response $response) {
        //load number of available variables for pagination
        $variableModel = new Variable($request->getDatabaseHelper()->getConnection());
        $number = $variableModel->getTotalNumberOfEntries();

        $request->getCookieHelper()->set("variables-list-length", 10, false, "/backend/variables");
        $itemsPerPage = $request->getCookieHelper()->get("variables-list-length", 10);
        $itemsPerPage = min($itemsPerPage, 200);
        $itemsPerPage = max($itemsPerPage, 10);

        $pages = ceil($number / $itemsPerPage);

        $response->getTwigHelper()->assign("pages", $pages);
        $response->getTwigHelper()->assign("itemsPerPage", $itemsPerPage);
        $response->getTwigHelper()->assign("availableEntries", $number);

    }



}