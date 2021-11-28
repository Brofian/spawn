<?php

namespace spawn\system\Core;

use spawn\system\Core\Base\Database\Definition\EntityCollection;
use spawn\system\Core\Contents\Modules\ModuleLoader;
use spawn\system\Core\Services\ServiceContainer;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Core\Services\ServiceTags;
use spawn\system\Throwables\NoActionFoundInControllerException;
use spawn\system\Throwables\NoControllerFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Kernel
{

    protected Request $request;
    protected Response $response;
    protected EntityCollection $moduleCollection;

    public function __construct()
    {

        $moduleLoader = new ModuleLoader();
        $this->moduleCollection = $moduleLoader->loadModules();

        $serviceContainer = ServiceContainerProvider::getServiceContainer();

        $this->defineModuleCollection($serviceContainer);
        $this->defineRequest($serviceContainer);
        $this->defineResponse($serviceContainer);
    }

    /**
     * @throws NoActionFoundInControllerException
     * @throws NoControllerFoundException
     */
    public function handle(): void
    {
        $requestHandler = new RequestHandler();
        $requestHandler->handleRequest();
    }


    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getAnswer(): string {
        $this->response->prepareFiles();

        return $this->response->finish();
    }


    protected function defineRequest(ServiceContainer $serviceContainer): Request {
        $this->request = new Request();
        $serviceContainer->defineService(
            'system.kernel.request',
            Request::class,
            [ServiceTags::BASE_SERVICE],
            true,
            false,
            null,
            null,
            null,
            $this->request,
        );
        return $this->request;
    }

    protected function defineResponse(ServiceContainer $serviceContainer): Response {
        $this->response = new Response();
        $serviceContainer->defineService(
            'system.kernel.response',
            Response::class,
            [ServiceTags::BASE_SERVICE],
            true,
            false,
            null,
            null,
            null,
            $this->response,
        );
        return $this->response;
    }

    protected function defineModuleCollection(ServiceContainer $serviceContainer): EntityCollection
    {
        $serviceContainer->defineService(
            'system.modules.collection',
            EntityCollection::class,
            [ServiceTags::BASE_SERVICE],
            true,
            false,
            null,
            null,
            null,
            $this->moduleCollection,
            );
        return $this->moduleCollection;
    }


}