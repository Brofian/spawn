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
use webu\system\Core\Helper\XMLReader;
use webu\system\Core\Services\Service;
use webu\system\Core\Services\ServiceContainer;
use webu\system\Core\Services\ServiceLoader;
use webu\system\Core\Services\ServiceTags;
use webu\system\Environment;
use webu\system\Throwables\ModulesNotLoadedException;
use webu\system\Throwables\NoModuleFoundException;

class Request
{

    private ?Environment $environment = null;
    private array $get = array();
    private array $post = array();
    private ?CookieHelper $cookies = null;
    private ?SessionHelper $session = null;
    private ?DatabaseHelper $database = null;
    private ?RoutingHelper $routingHelper = null;
    private string $baseURI = '';
    private string $requestURI = '';
    private array $requestURIParams = array();
    private string $requestController = 'index';
    private string $requestActionPath = 'index';
    private ?Context $context = null;
    private ?ModuleCollection $moduleCollection = null;
    private ?ServiceContainer $serviceContainer = null;



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

        $moduleLoader = new ModuleLoader();
        $moduleLoader->readModules();

        $this->moduleCollection = $moduleLoader->loadModules($this->getDatabaseHelper()->getConnection());

        dd($this->moduleCollection);

        $serviceLoader = new ServiceLoader();
        $this->serviceContainer = $serviceLoader->loadServices($this->moduleCollection);


        $this->routingHelper = new RoutingHelper($this->moduleCollection);
    }

    public function addCoreServices() {

        $this->serviceContainer->addService(
            (new Service())->setId('system.cookie.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\CookieHelper')
                ->setInstance($this->getCookieHelper())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.session.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\SessionHelper')
                ->setInstance($this->getSessionHelper())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.database.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\DatabaseHelper')
                ->setInstance($this->getDatabaseHelper())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.database.connection')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Base\Database\DatabaseConnection')
                ->setInstance($this->getDatabaseHelper()->getConnection())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.routing.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\RoutingHelper')
                ->setInstance($this->getRoutingHelper())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.twig.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\TwigHelper')
                ->setInstance($this->environment->response->getTwigHelper())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.scss.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\ScssHelper')
                ->setInstance($this->environment->response->getScssHelper())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.xml.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\XMLReader')
                ->setInstance(new XMLReader())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.curi.converter.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Helper\FrameworkHelper\CUriConverter')
                ->setInstance(new CUriConverter())
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.file.editor.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Base\Custom\FileEditor')
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.logger.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Base\Custom\Logger')
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.string.converter.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Base\Custom\StringConverter')
        );

        $this->serviceContainer->addService(
            (new Service())->setId('system.query.builder.helper')
                ->setStatic(true)
                ->setTag(ServiceTags::BASE_SERVICE_STATIC)
                ->setClass('webu\system\Core\Base\Database\Query\QueryBuilder')
        );


    }


    public function loadController()
    {

        $controller = $this->get["controller"] ?? "";
        $action = $this->get["action"] ?? "";

        //find service
        $controllerService = $this->serviceContainer->getService($controller);
        if(!$controllerService) {
            //controller does not exist
            $controllerService = $this->serviceContainer->getService('system.fallback.404');
            $action = 'error404';
        }

        $actionMethod =  $action."Action";
        if(!method_exists($controllerService->getClass(), $actionMethod)) {
            //action does not exist
            $controllerService = $this->serviceContainer->getService('system.fallback.404');
            $actionMethod = 'error404Action';
        }


        //$uriParameters = CUriConverter::getParametersFromUri($this->requestURI, $routingResult["uri"]);

        $controllerInstance = $controllerService->getInstance();
        $controllerInstance->$actionMethod(); //pass uri parameters



        $this->environment->response->getTwigHelper()->assign("namespace", $module->getResourceNamespace());
        $this->environment->response->getTwigHelper()->assign("environment", MODE);
        $this->environment->response->getScssHelper()->setBaseVariable("assetsPath", URIHelper::createPath([
            MAIN_ADDRESS_FULL,CACHE_DIR,"public",$module->getResourceNamespace(),"assets"
        ], "/"));



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
    public function getCookieHelper(): ?CookieHelper
    {
        return $this->cookies;
    }

    /** @return SessionHelper */
    public function getSessionHelper(): ?SessionHelper
    {
        return $this->session;
    }

    /** @return DatabaseHelper */
    public function getDatabaseHelper() : ?DatabaseHelper
    {
        return $this->database;
    }

    /**
     * @return RoutingHelper
     */
    public function getRoutingHelper() : ?RoutingHelper
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


    public function getModuleCollection() : ?ModuleCollection {
        return $this->moduleCollection;
    }


}