<?php

namespace webu\system\Core\Services;

use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\Modules\ModuleLoader;

class ServiceContainerProvider {

    const CORE_SERVICE_LIST = [
        'system.cookie.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\CookieHelper',
        ],
        'system.session.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\SessionHelper',
        ],
        'system.database.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\DatabaseHelper',
        ],
        'system.header.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\HeaderHelper',
        ],
        'system.database.connection' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Base\Database\DatabaseConnection',
            ServiceProperties::_ARGUMENTS => [
                ['type' => 'value', 'value' => DB_HOST],
                ['type' => 'value', 'value' => DB_DATABASE],
                ['type' => 'value', 'value' => DB_PORT],
                ['type' => 'value', 'value' => DB_USERNAME],
                ['type' => 'value', 'value' => DB_PASSWORD],
            ]
        ],
        'system.routing.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\RoutingHelper',
        ],
        'system.twig.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\TwigHelper',
        ],
        'system.scss.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\ScssHelper',
        ],
        'system.xml.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\XMLReader',
        ],
        'system.curi.converter.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Helper\FrameworkHelper\CUriConverter',
        ],
        'system.file.editor.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Base\Custom\FileEditor',
        ],
        'system.logger.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Base\Custom\Logger',
        ],
        'system.string.converter.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Base\Custom\StringConverter',
        ],
        'system.query.builder.helper' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Base\Database\Query\QueryBuilder',
        ],
        'system.request.curi.valuebag' => [
            ServiceProperties::_TAG => ServiceTags::BASE_SERVICE_STATIC,
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => 'webu\system\Core\Contents\ValueBag',
        ],
    ];

    protected static ServiceContainer $serviceContainer;

    public static function getServiceContainer(bool $preventModuleServiceLoading = false): ServiceContainer {
        if(!isset(self::$serviceContainer)) {
            self::$serviceContainer = new ServiceContainer();
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