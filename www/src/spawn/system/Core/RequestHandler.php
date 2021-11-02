<?php

namespace spawn\system\Core;

use spawn\system\Core\Contents\Response\AbstractResponse;
use spawn\system\Core\Contents\Response\SimpleResponse;
use spawn\system\Core\Contents\Response\JsonResponse;
use spawn\system\Core\Contents\ValueBag;
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
    protected ValueBag $cUrlValues;

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
        $this->cUrlValues = $request->getCurlValues();
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

        $responseObject = $controllerInstance->$actionMethod(...array_values($this->cUrlValues->toArray()));
        $responseObject = $this->validateAndCovertResponseObject($responseObject);

        /** @var Response $response */
        $response = $this->serviceContainer->getServiceInstance('system.kernel.response');
        $response->setResponseObject($responseObject);
    }

    /**
     * @param $responseObject
     * @return AbstractResponse
     */
    protected function validateAndCovertResponseObject($responseObject): AbstractResponse {
        if($responseObject instanceof AbstractResponse) {
            return $responseObject;
        }

        if(is_string($responseObject) || is_numeric($responseObject)) {
            return new SimpleResponse((string)$responseObject);
        }
        else if(is_array($responseObject)) {
            return new JsonResponse($responseObject);
        }

        return new SimpleResponse('Could not parse Controller Result to Response Object');
    }
}