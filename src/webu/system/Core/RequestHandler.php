<?php

namespace webu\system\Core;

use webu\system\Core\Helper\RoutingHelper;
use webu\system\Core\Services\Service;
use webu\system\Core\Services\ServiceContainer;
use webu\system\Core\Services\ServiceContainerProvider;

class RequestHandler {

    protected ServiceContainer $serviceContainer;
    protected Service $controllerService;
    protected string $actionMethod;

    public function __construct()
    {
        $this->serviceContainer = ServiceContainerProvider::getServiceContainer();

    }


    public function handleRequest(): void {
        $this->findRouting();
        $this->callControllerMethod();
    }


    protected function findRouting() {
        /** @var RoutingHelper $routingHelper */
        $routingHelper = $this->serviceContainer->getServiceInstance('system.routing.helper');

        $routingHelper->route(
            $this->get["controller"] ?? "",
            $this->get["action"] ?? "",
            $controllerService,
            $actionMethod
        );
    }

    protected function callControllerMethod() {
        $controllerInstance = $this->controllerService->getInstance();
        $actionMethod = $this->actionMethod;
        $controllerInstance->$actionMethod(); //TODO: pass uri parameters
    }

}