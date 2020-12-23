<?php

namespace modules\Main\Controllers;

use webu\cache\database\table\WebuAuth;
use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Database\Models\AuthUser;
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
            'logout' => 'logout',
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

    public function logout(Request $request, Response $response) {
        $request->getParamSession()->set("webu_user_logged_in", false);
        $response->getTwigHelper()->setOutput("
            <html>
                <head>
                    <meta http-equiv='refresh' content='3' url='/backend/login' />
                    <script>window.location.replace('/backend/login')</script> 
                    <meta name='robots' content='noindex,nofollow'>
                </head>
                <body>
                    <div><b>Sie werden nun weiter geleitet...</b></div>
                    Wenn sie nach 5 Sekunden nicht automatisch weiter geleitet wurden, <a href='/backend/login'>hier</a> klicken
                </body>
            </html>
        ");
    }




}