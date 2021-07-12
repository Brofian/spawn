<?php

namespace spawn\system\Core;

use spawn\system\Core\Helper\RoutingHelper;
use spawn\system\Core\Services\Service;
use spawn\system\Core\Services\ServiceContainer;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Throwables\NoActionFoundInControllerException;
use spawn\system\Throwables\NoControllerFoundException;

class RequestHandler {

    protected ServiceContainer $serviceContainer;
    protected ?Service $controllerService;
    protected ?string $actionMethod;

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
        /** @var Request $request */
        $request = $this->serviceContainer->getServiceInstance('system.kernel.request');
        $getBag = $request->getGet();

        $routingHelper->route(
            $getBag->get('controller') ?? "",
            $getBag->get('action') ?? "",
            $this->controllerService,
            $this->actionMethod
        );

        if(!$this->controllerService) {
            throw new NoControllerFoundException($getBag->get('controller'));
        }

        if(!$this->actionMethod) {
            throw new NoActionFoundInControllerException($getBag->get('controller'), $getBag->get('action'));
        }
    }

    protected function callControllerMethod() {
        $controllerInstance = $this->controllerService->getInstance();
        $actionMethod = $this->actionMethod;

        $controllerInstance->$actionMethod();
    }

}