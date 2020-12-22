<?php

namespace webu\system\core;

/*
 *  The default Class to store all Request Informations
 */

use webu\system\Core\Base\Controller\Controller;
use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\Core\Contents\ContentLoader;
use webu\system\Core\Contents\Context;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Custom\Logger;
use webu\system\Core\Helper\CookieHelper;
use webu\system\Core\Helper\RoutingHelper;
use webu\system\Core\Helper\SessionHelper;
use webu\system\Environment;

class Request
{

    /** @var array */
    private $get = array();
    /** @var array */
    private $post = array();
    /** @var CookieHelper */
    private $cookies = null;
    /** @var SessionHelper */
    private $session = null;
    /** @var DatabaseHelper */
    private $database = null;
    /** @var RoutingHelper  */
    private $routingHelper = null;
    /** @var string */
    private $baseURI = '';
    /** @var string */
    private $requestURI = '';
    /** @var array */
    private $requestURIParams = array();
    /** @var string */
    private $requestController = 'index';
    /** @var string */
    private $requestActionPath = 'index';
    /** @var Context */
    private $context = null;


    public function __construct()
    {
    }

    public function gatherInformations()
    {
        //Load all Informations, found in the request
        $this->setParams();
        $this->setBaseURI();
        $this->setRequestURI();
        $this->setRequestURIParams();
    }

    public function addToAccessLog()
    {
        $text = 'Call to "';
        $text .= MAIN_ADDRESS . '/' . $this->requestURI;
        if (sizeof($this->requestURIParams)) {
            $text .= '" with the params ';
            $text .= implode(', ', $this->requestURIParams);
        }

        Logger::writeToAccessLog($text);
    }


    private function setRequestURIParams()
    {
        $params = explode("/", $this->requestURI);
        foreach ($params as &$param) {
            $param = strtolower(trim($param));
        }

        //get the controller
        if (sizeof($params) > 0) {
            $this->requestController = $params[0];
            array_shift($params);
        }


        /* Save the Action */
        if (sizeof($params) > 0) {
            $this->requestActionPath = $params[0];
            array_shift($params);
        }

        /* Save Params */
        if(sizeof($params) > 0) {
            $this->requestURIParams = $params;
        }
        else {
            $this->requestURIParams = [];
        }

    }

    private function setRequestURI()
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        $getSeperator = strrpos($requestURI, '?');

        if ($getSeperator === false) {
            $this->requestURI = trim($requestURI, "/");
        }
        else {
            $this->requestURI = trim(substr($requestURI, 0, $getSeperator), "/");
        }

    }

    private function setBaseURI()
    {
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }
        $uri .= $_SERVER['HTTP_HOST'];

        $this->baseURI = $uri; //e.g. 'http://localhost//'
    }

    private function setParams()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookies = new CookieHelper();
        $this->session = new SessionHelper();
        $this->database = new DatabaseHelper();
        $this->context = new Context();
    }



    public function loadController($requestController, $requestActionPath, Environment $e)
    {

        $routingHelper = new RoutingHelper();
        $this->routingHelper = $routingHelper;
        $erg = $this->routingHelper->route(
            $requestController,
            $requestActionPath
        );

        /** @var Controller $controller */
        $controller = new $erg['controller']();
        /** @var string $action */
        $action = $erg['action'];


        /* Insert gathered Information to Context */
        $this->fillContext();



        /* Call Controller */
        $params = $this->routingHelper->addValuesToCustomParams($e, $controller,$action);

        $controller->init($this,$e->response);

        //call the controller method
        call_user_func_array(
            [
                $controller,    $action
            ],
            $params
        );

        $controller->end($this, $e->response);

    }

    private function fillContext() {

        $contentLoader = new ContentLoader($this);
        $contentLoader->init($this->getContext());

    }



    /*
     * Getter & Setter Methoden
     */

    /** @return array */
    public function getParamGet(): array
    {
        return $this->get;
    }

    /** @return array */
    public function getParamPost(): array
    {
        return $this->post;
    }

    /** @return CookieHelper */
    public function getParamCookies(): CookieHelper
    {
        return $this->cookies;
    }

    /** @return SessionHelper */
    public function getParamSession(): SessionHelper
    {
        return $this->session;
    }

    /** @return DatabaseHelper */
    public function getDatabase() : DatabaseHelper
    {
        return $this->database;
    }

    /**
     * @return RoutingHelper
     */
    public function getRouting() : RoutingHelper
    {
        return $this->routingHelper;
    }

    /**
     * @return string
     */
    public function getRequestController()
    {
        return $this->requestController;
    }

    /**
     * @return string
     */
    public function getRequestActionPath()
    {
        return $this->requestActionPath;
    }

    /**
     * @return string
     */
    public function getRequestURI()
    {
        return $this->requestURI;
    }

    /**
     * @return array
     */
    public function getRequestURIParams()
    {
        return $this->requestURIParams;
    }

    /**
     * @return Context
     */
    public function getContext() {
        return $this->context;
    }

    /**
     * @param string $name
     * @param bool $fallback
     * @return bool|mixed
     */
    public function getContextVar(string $name, $fallback = false) {
        if($name == '' || isset($this->context->getContext()[$name])) {
            return $this->context->getContext()[$name];
        }
        else {
            return $fallback;
        }
    }


}