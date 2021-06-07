<?php

namespace webu\system\core;

/*
 *  The default Class to store all Request Informations
 */

use webu\system\Core\Base\Controller\BaseController;
use webu\system\Core\Base\Helper\DatabaseHelper;
use webu\system\Core\Contents\ContentLoader;
use webu\system\Core\Contents\Context;
use webu\system\Core\Contents\Modules\Module;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleController;
use webu\system\Core\Contents\Modules\ModuleLoader;
use webu\system\Core\Contents\Modules\ModuleNamespacer;
use webu\system\Core\Custom\Logger;
use webu\system\Core\Helper\CookieHelper;
use webu\system\Core\Helper\FrameworkHelper\CUriConverter;
use webu\system\Core\Helper\RoutingHelper;
use webu\system\Core\Helper\SessionHelper;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Services\ServiceLoader;
use webu\system\Environment;
use webu\system\Throwables\ModulesNotLoadedException;
use webu\system\Throwables\NoModuleFoundException;

class Request
{

    /** @var Environment */
    private $environment = null;
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
    /** @var ModuleCollection  */
    private $moduleCollection = null;

    /**
     * Request constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }


    public function gatherInformationsFromRequest()
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
            $this->requestController = array_shift($params);
        }


        /* Save the Action */
        if (sizeof($params) > 0) {
            $this->requestActionPath = array_shift($params);;
        }

        /* Save Params */
        $this->requestURIParams = $params ?? [];

    }


    private function setRequestURI()
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        $getSeperatorPosition = strrpos($requestURI, '?');

        if ($getSeperatorPosition === false) {
            $this->requestURI = trim($requestURI, "/");
        }
        else {
            $this->requestURI = trim(substr($requestURI, 0, $getSeperatorPosition), "/");
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


    public function loadController()
    {

        $moduleLoader = new ModuleLoader();
        $this->moduleCollection = $moduleLoader->loadModules($this->getDatabaseHelper()->getConnection());

        /*
        $serviceLoader = new ServiceLoader();
        $serviceLoader->loadServices($this->moduleCollection);
        */

        //sort modules by resource Weight
        $moduleList = $this->moduleCollection->getModuleList();
        if(count($moduleList) < 1) {
            throw new ModulesNotLoadedException();
        }
        ModuleCollection::sortModulesByWeight($moduleList);

        $twigHelper = $this->environment->response->getTwigHelper();
        /** @var Module $module */
        foreach($moduleList as $module) {
            $twigHelper->addTemplateDir(ROOT . $module->getResourcePath() . "/template");
        }


        $this->routingHelper = new RoutingHelper($this->moduleCollection);
        $routingResult = $this->routingHelper->route($this->requestURI);

        if($routingResult === false) {
            $routingResult = $this->routingHelper->route("404");
        }

        if($routingResult === false) {
            throw new NoModuleFoundException();
        }


        $uriParameters = CUriConverter::getParametersFromUri($this->requestURI, $routingResult["uri"]);


        /** @var Module $module */
        $module = $routingResult["module"];
        /** @var ModuleController $controller */
        $controller = $routingResult["controller"];
        /** @var string $method */
        $method = $routingResult["method"];
        /** @var string $actionId */
        $actionId = $routingResult["id"];

        $this->environment->response->getTwigHelper()->assign("namespace", $module->getResourceNamespace());
        $this->environment->response->getTwigHelper()->assign("environment", MODE);
        $this->environment->response->getScssHelper()->setBaseVariable("assetsPath", URIHelper::createPath([
            MAIN_ADDRESS_FULL,
            CACHE_DIR,
            "public",
            $module->getResourceNamespace(),
            "assets"
        ], "/"));
        $this->environment->response->getScssHelper()->setBaseVariable("defaultAssetsPath", URIHelper::createPath([
            MAIN_ADDRESS_FULL,
            CACHE_DIR,
            "public",
            ModuleNamespacer::getGlobalNamespace(),
            "assets"
        ], "/"));


        //prepare the params for the method
        $params = [
            $this,
            $this->environment->response
        ];
        $params = array_merge($params, $uriParameters);


        /* Insert gathered Information to Context */
        $this->fillContext();
        $this->getContext()->set("ModuleCollection", $this->moduleCollection);
        $this->getContext()->set("Module", $module->getName());
        $this->getContext()->set("ControllerClass", $controller->getClass());
        $this->getContext()->set("Controller", $controller->getName());
        $this->getContext()->set("Action", $method);
        $this->getContext()->set("ActionId", $actionId);



        $cls = $controller->getClass();
        /** @var BaseController $ctrl */
        $ctrl = new $cls();
        $ctrl->init($this, $this->environment->response);

        //stop execution immediately when stopped
        if($ctrl->isExecutionStopped()) {
            return;
        }

        //call the controller method
        call_user_func_array(
            [$ctrl,$method],
            $params
        );

        //stop execution immediately when stopped
        if($ctrl->isExecutionStopped()) {
            return;
        }

        $ctrl->end($this, $this->environment->response);
    }


    protected function fillContext() {
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
    public function getCookieHelper(): CookieHelper
    {
        return $this->cookies;
    }

    /** @return SessionHelper */
    public function getSessionHelper(): SessionHelper
    {
        return $this->session;
    }

    /** @return DatabaseHelper */
    public function getDatabaseHelper() : DatabaseHelper
    {
        return $this->database;
    }

    /**
     * @return RoutingHelper
     */
    public function getRoutingHelper() : RoutingHelper
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
     * @return ModuleCollection
     */
    public function getModuleCollection() {
        return $this->moduleCollection;
    }


}