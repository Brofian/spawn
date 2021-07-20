<?php

namespace spawn\system\Core\Services;

use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Contents\Modules\ModuleLoader;

class ServiceContainerProvider {

    const CORE_SERVICE_LIST = [
        'system.cookie.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\CookieHelper',
        ],
        'system.session.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\SessionHelper',
        ],
        'system.database.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Base\Helper\DatabaseHelper',
        ],
        'system.header.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\HeaderHelper',
        ],
        'system.database.connection' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Base\Database\DatabaseConnection',
        ],
        'system.routing.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\RoutingHelper',
        ],
        'system.twig.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\TwigHelper',
        ],
        'system.scss.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\ScssHelper',
        ],
        'system.xml.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\XMLReader',
        ],
        'system.curi.converter.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Helper\FrameworkHelper\CUriConverter',
        ],
        'system.file.editor.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Base\Custom\FileEditor',
        ],
        'system.logger.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Base\Custom\Logger',
        ],
        'system.string.converter.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Base\Custom\StringConverter',
        ],
        'system.query.builder.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Base\Database\Query\QueryBuilder',
        ],
        'system.request.curi.valuebag' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'spawn\system\Core\Contents\ValueBag',
        ],
    ];

    protected static ServiceContainer $serviceContainer;

    public static function getServiceContainer(): ServiceContainer {
        if(!isset(self::$serviceContainer)) {
            $serviceLoader = new ServiceLoader();
            $moduleLoader = new ModuleLoader();

            $modules = $moduleLoader->loadModules();
            self::$serviceContainer = $serviceLoader->loadServices($modules);

            self::addCoreServices();
        }

        return self::$serviceContainer;
    }


    protected static function addCoreServices(): void {

        $propertySetterList = ServiceProperties::getPropertySetterMethods();

        foreach(self::CORE_SERVICE_LIST as $coreServiceId => $coreServiceData) {
            $service = new Service();
            $service->setId($coreServiceId);

            foreach($propertySetterList as $property => $setterMethod) {
                if(isset($coreServiceData[$property])) {
                    $service->$setterMethod($coreServiceData[$property]);
                }
            }

            self::$serviceContainer->addService($service);
        }

    }






}