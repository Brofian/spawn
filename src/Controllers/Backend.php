<?php

namespace modules\Main\Controllers;

use webu\cache\database\table\WebuAuth;
use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Database\Models\AuthUser;
use webu\system\Core\Database\Models\Variable;
use webu\system\core\Request;
use webu\system\core\Response;

class Backend extends Controller {

    /**
     * @inheritDoc
     */
    public static function getControllerAlias(): string
    {
        return 'backend';
    }

    /**
     * @inheritDoc
     */
    public static function getControllerRoutes(): array
    {
        return [
            '' => 'index',
            'login' => 'login',
            'logout' => 'logout',
            'variables' => 'variables'
        ];
    }

    public function onControllerStart(Request $request, Response $response) {
        $request->getContext()->setBackendContext();
    }

    public function onControllerStop(Request $request, Response $response) {}



    public function index(Request $request, Response $response) {
        //if user is not logged in, redirect to the loading page
        if($request->getParamSession()->get('webu_user_logged_in', false) == false) {
            $this->login($request, $response);
        }

    }


    public function login(Request $request, Response $response) {
        //this page can be called from other functions, so reset the action to login
        $response->getTwigHelper()->assign('action', 'login');
    }



    public function logout(Request $request, Response $response) {
        $request->getParamSession()->set("webu_user_logged_in", false);
        $response->getTwigHelper()->setRenderFile("backend/login/logout.html.twig");
    }



    public function variables(Request $request, Response $response) {
        //if user is not logged in, redirect to the loading page
        if($request->getParamSession()->get('webu_user_logged_in', false) == false) {
            $this->login($request, $response);
        }


        //Assign Subpage from uri
        $availableSubpages = [
            "edit",
            "new",
            "index"
        ];
        $uriSubPage = "index";

        if(isset($request->getCompiledURIParams()[0])) {
            if(in_array($request->getCompiledURIParams()[0], $availableSubpages)) {
                $uriSubPage = $request->getCompiledURIParams()[0];
            }
        }
        $response->getTwigHelper()->assign("subpage", $uriSubPage);


        if($uriSubPage === "index") {
            //load number of available variables for pagination
            $variableModel = new Variable($request->getDatabase()->getConnection());
            $number = $variableModel->getTotalNumberOfEntries();

            $request->getParamCookies()->set("variables-list-length", 10, false, "/backend/variables");
            $itemsPerPage = $request->getParamCookies()->get("variables-list-length", 10);
            $itemsPerPage = min($itemsPerPage, 200);
            $itemsPerPage = max($itemsPerPage, 10);

            $pages = ceil($number / $itemsPerPage);

            $response->getTwigHelper()->assign("pages", $pages);
            $response->getTwigHelper()->assign("itemsPerPage", $itemsPerPage);
            $response->getTwigHelper()->assign("availableEntries", $number);
        }
        else if($uriSubPage === "edit") {


            $variableModel = new Variable($request->getDatabase()->getConnection());
            $id = $request->getCompiledURIParams()["id"];

            $variable = $variableModel->findById($id);

            if(sizeof($variable) > 0) {
                $variable = $variable[0];

                $response->getTwigHelper()->assign("variable", $variable);
            }

        }

    }



}