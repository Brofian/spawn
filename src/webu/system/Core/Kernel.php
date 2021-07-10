<?php

namespace webu\system\Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleLoader;
use webu\system\Core\Services\ServiceContainer;
use webu\system\Core\Services\ServiceLoader;
use webu\system\Core\Services\ServiceTags;

class Kernel {

    protected Request $request;
    protected Response $response;
    protected ModuleCollection $moduleCollection;

    public function __construct()
    {
        $moduleLoader = new ModuleLoader();
        $this->moduleCollection = $moduleLoader->loadModules(
            new DatabaseConnection(DB_HOST, DB_DATABASE, DB_PORT, DB_USERNAME, DB_PASSWORD)
        );

        $serviceLoader = new ServiceLoader();
        $serviceContainer = $serviceLoader->loadServices($this->moduleCollection);

        $this->defineModuleCollection($serviceContainer);
        $this->defineRequest($serviceContainer);
        $this->defineResponse($serviceContainer);
    }

    public function handle(): void {
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
            ServiceTags::BASE_SERVICE_STATIC,
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
            Request::class,
            ServiceTags::BASE_SERVICE_STATIC,
            true,
            false,
            null,
            null,
            null,
            $this->response,
        );
        return $this->response;
    }

    protected function defineModuleCollection(ServiceContainer $serviceContainer): ModuleCollection {
        $serviceContainer->defineService(
            'system.modules.collection',
            ModuleCollection::class,
            ServiceTags::BASE_SERVICE_STATIC,
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