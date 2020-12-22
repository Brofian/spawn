<?php

namespace modules\Main\Controllers;

use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Custom\Debugger;
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
            'loginapi' => 'loginApi',
            'debug' => 'debug'
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



    public function loginApi(Request $request, Response $response) {

        $parameter = $request->getParamGet();
        //$parameter["username"]
        //$parameter["password"]


        //TODO: check login information with db and return true or false
        $output = false;





        if($output) {
            $request->getParamSession()->set("webu_user_logged_in", true);
        }

        $response->getTwigHelper()->setOutput(json_encode($output));
    }





}