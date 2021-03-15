<?php

namespace webu\modules\Backend\Controller;

use webu\modules\backend\models\BackendUserModel;
use webu\modules\Backend\Models\SidebarElement;
use webu\system\Core\Base\Controller\BaseController;
use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Helper\RoutingHelper;
use webu\system\core\Request;
use webu\system\core\Response;


class Login extends BaseController {

    const BACKEND_HOME_ACTION_ID = "module.backend.index.index";
    const LOGIN_ACTION_ID = "module.backend.login.index";
    const LOGOUT_ACTION_ID = "module.backend.login.logout";


    /** @var BackenduserModel */
    protected $backendUser;

    public function onControllerStart(Request $request, Response $response)
    {
        $this->backendUser = new BackendUserModel($request, $response);

        if($this->backendUser->isLoggedIn() && $request->getContext()->get("ActionId") != self::LOGOUT_ACTION_ID) {
            //when logged in, redirect to main
            $headerHelper = $response->getHeaderHelper();
            $headerHelper->redirect(self::BACKEND_HOME_ACTION_ID, [], $headerHelper::RC_REDIRECT_TEMPORARILY);
            $this->twig->setOutput("Access denied! You are already logged in!");
            $this->stopExecution();
        }
    }


    /*
     *
     * Callable Actions
     *
     */
    public function index(Request $request, Response $response) {
        $response->getTwigHelper()->assign("isLoginPage", true);
        $response->getTwigHelper()->assign("loginRequestTarget", "module.backend.login.trylogin");
    }


    public function logout(Request $request, Response $response) {
        $this->backendUser->logout();

        $headerHelper = $response->getHeaderHelper();
        $headerHelper->redirect(self::LOGIN_ACTION_ID, [], $headerHelper::RC_REDIRECT_TEMPORARILY);
        $this->twig->setOutput("Logged out!");
    }

    public function tryLogin(Request $request, Response $response) {

        $post = $request->getParamPost();

        if(!isset($post["username"], $post["password"])) {
            $this->setJsonOutput(["success"=>"false"]);
        }

        $this->backendUser->tryLogin((string)$post["username"], (string)$post["password"]);


        if($this->backendUser->isLoggedIn()) {
            $this->setJsonOutput([
                "success"=>"true",
                "target"=> $request->getRoutingHelper()->getLinkFromId("module.backend.index.index")
            ]);
            return;
        }

        $this->setJsonOutput(["success"=>"false"]);
    }



}