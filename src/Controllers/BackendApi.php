<?php

namespace modules\Main\Controllers;


use webu\system\Core\Base\Controller\ApiController;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Database\Models\AuthUser;
use webu\system\Core\Database\Models\Variable;
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
        if($request->getParamSession()->get('webu_user_logged_in', false) == false) {
            //response code
            $response->setResponseCode(403);
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

        $authUserModel = new AuthUser($request->getDatabase()->getConnection());
        $userInfo = $authUserModel->tryLogin($parameter["username"], $parameter["password"]);
        $output = [
            "success" => ($userInfo === false) ? 0 : 1
        ];


        if($output) {
            //set session values
            $request->getParamSession()->set("webu_user_logged_in", true);
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
        }

    }


    private function getVariablesList(Request $request, Response $response) {
        $variableModel = new Variable($request->getDatabase()->getConnection());

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