<?php

namespace modules\Main\Controllers;


use webu\system\Core\Base\Controller\ApiController;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Contents\Context;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Database\Models\AuthUser;
use webu\system\Core\Database\Models\Variable;
use webu\system\Core\Helper\UserHelper;
use webu\system\core\Request;
use webu\system\core\Response;

class BackendApi extends ApiController {

    private $preventAccess = true;

    /**
     * @inheritDoc
     */
    public static function getControllerAlias(): string
    {
        return 'backendapi';
    }


    /**
     * @inheritDoc
     */
    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
            'loginapi' => 'loginApi',
            'variablesapi' => 'variablesApi',
        ];
    }

    public function onControllerStart(Request $request, Response $response) {
        $request->getContext()->setBackendContext();
        if($request->getSessionHelper()->get('webu_user_logged_in', false) == false) {
            //response code
            $response->getTwigHelper()->setOutput("You have no permission to use this resource! Please log in to verify");
            return;
        }
        $this->preventAccess = false;
    }

    public function onControllerStop(Request $request, Response $response) {
    }



    public function index(Request $request, Response $response) {
        $response->getTwigHelper()->setOutput("0");
    }


    public function loginApi(Request $request, Response $response) {

        $parameter = $request->getParamGet();

        /** @var UserHelper $userHelper */
        $userHelper = $request->getContext()->get(Context::INDEX_USER);
        $userHelper->login($parameter["username"], $parameter["password"]);

        $output = [
            "success" => $userHelper->isLoggedIn()
        ];


        if($output) {
            //set session values
            $request->getSessionHelper()->set("webu_user_logged_in", true);
        }



        $response->getTwigHelper()->setOutput(json_encode($output));
    }



    public function variablesApi(Request $request, Response $response) {
        if($this->preventAccess) {
            return;
        }


        $uriParams = $request->getCompiledURIParams();
        $targetAction = $uriParams[0] ?? "";

        switch($targetAction) {
            case "list":
                $this->getVariablesList($request, $response);
                break;
            case "remove":
                $this->removeVariables($request, $response);
                break;
            case "edit":
                $this->editVariable($request, $response);
                break;
        }

    }

    private function editVariable(Request $request, Response $response) {
        $getParams = $request->getParamGet();
        $variableAlreadyExists = ($getParams["id"] != "");

        $variableModel = new Variable($request->getDatabaseHelper()->getConnection());

        if($variableAlreadyExists) {
            $id = (int)$getParams["id"];
            $selectedVariable = $variableModel->findById($id);

            if(sizeof($selectedVariable) > 0) {
                //variable really exists -> update it
                $values = [
                    "name" => $getParams["name"],
                    "namespace" => $getParams["namespace"],
                    "type" => $getParams["type"],
                    "value" => $getParams["value"]
                ];
                $variableModel->updateById($id, $values);
                $response->getTwigHelper()->setOutput(json_encode(["success" => true]));
            }
            else {
                $variableAlreadyExists = false;
            }
        }

        if($variableAlreadyExists == false) {
            //variable does not exist yet -> create it
            $values = [
                "name" => $getParams["name"],
                "namespace" => $getParams["namespace"],
                "type" => $getParams["type"],
                "value" => $getParams["value"]
            ];
            $success = $variableModel->create($values);
            $response->getTwigHelper()->setOutput(json_encode(["success" => $success]));
        }

    }


    private function removeVariables(Request $request, Response $response) {
        $getParams = $request->getParamGet();
        $ids = $getParams["idlist"] ?? [];

        $variableModel = new Variable($request->getDatabaseHelper()->getConnection());
        $variableModel->removeEntriesByIds($ids);

        $response->getTwigHelper()->setOutput(1);
    }


    private function getVariablesList(Request $request, Response $response) {
        $variableModel = new Variable($request->getDatabaseHelper()->getConnection());

        $getParams = $request->getParamGet();

        $page = $getParams["page"] ?? 0;
        $itemsPerPage = $getParams["itemsPerPage"] ?? 10;
        $itemsPerPage = min($itemsPerPage, 200);
        $itemsPerPage = max($itemsPerPage, 10);

        $offset = ($page-1) * $itemsPerPage;


        $entries = $variableModel->findEntries($offset,$itemsPerPage);
        $response->getTwigHelper()->assign("variables", $entries);
        $response->getTwigHelper()->setRenderFile("backend/variables/elements/listing.html.twig");
    }

}