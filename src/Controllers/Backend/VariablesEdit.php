<?php

namespace modules\Main\Controllers\Backend;


use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Database\Models\Variable;
use webu\system\core\Request;
use webu\system\core\Response;

class VariablesEdit {

    /** @var Controller */
    protected $parentController;

    public function __construct(Controller $parentController)
    {
        $this->parentController = $parentController;
    }


    public function init(Request $request, Response $response) {
        $variableModel = new Variable($request->getDatabaseHelper()->getConnection());
        $id = $request->getCompiledURIParams()["id"];

        $variable = $variableModel->findById($id);

        if(sizeof($variable) > 0) {
            $variable = $variable[0];

            $response->getTwigHelper()->assign("variable", $variable);
        }
    }


}