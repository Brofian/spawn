<?php

namespace spawn\system\Core\Services;

use spawn\system\Core\Base\Custom\FileEditor;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Base\Database\Definition\EntityCollection;
use spawn\system\Core\Base\EventSystem\EventInitializer;
use spawn\system\Core\Base\Helper\DatabaseHelper;
use spawn\system\Core\Contents\Modules\ModuleLoader;
use spawn\system\Core\Contents\ValueBag;
use spawn\system\Core\Custom\CSRFTokenAssistant;
use spawn\system\Core\Custom\Logger;
use spawn\system\Core\Custom\StringConverter;
use spawn\system\Core\Helper\CookieHelper;
use spawn\system\Core\Helper\FrameworkHelper\CUriConverter;
use spawn\system\Core\Helper\HeaderHelper;
use spawn\system\Core\Helper\RoutingHelper;
use spawn\system\Core\Helper\ScssHelper;
use spawn\system\Core\Helper\SessionHelper;
use spawn\system\Core\Helper\TwigHelper;
use spawn\system\Core\Helper\XMLReader;

class ServiceContainerProvider {

    const CORE_SERVICE_LIST = [
        'system.cookie.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => CookieHelper::class,
        ],
        'system.session.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => SessionHelper::class,
        ],
        'system.database.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => DatabaseHelper::class,
        ],
        'system.header.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => HeaderHelper::class,
        ],
        'system.database.connection' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => DatabaseConnection::class,
        ],
        'system.routing.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => RoutingHelper::class,
        ],
        'system.twig.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => TwigHelper::class,
        ],
        'system.scss.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => ScssHelper::class,
        ],
        'system.xml.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => XMLReader::class,
        ],
        'system.curi.converter.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => CUriConverter::class,
        ],
        'system.file.editor.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => FileEditor::class,
        ],
        'system.logger.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => Logger::class,
        ],
        'system.string.converter.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => StringConverter::class,
        ],
        'system.request.curi.valuebag' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => ValueBag::class,
        ],
        'system.csrf_token.helper' => [
            ServiceProperties::_TAGS => [ServiceTags::BASE_SERVICE],
            ServiceProperties::_STATIC => true,
            ServiceProperties::_CLASS => CSRFTokenAssistant::class,
            ServiceProperties::_ARGUMENTS => [
                ["type"=>"service","value"=>"system.session.helper"],
            ]
        ],
    ];

    protected static ServiceContainer $serviceContainer;

    public static function getServiceContainer(): ServiceContainer {
        if(!isset(self::$serviceContainer)) {
            $serviceLoader = new ServiceLoader();
            $moduleLoader = new ModuleLoader();

            /** @var EntityCollection $modules */
            $modules = $moduleLoader->loadModules();
            self::$serviceContainer = $serviceLoader->loadServices($modules);
            self::addCoreServices();

            EventInitializer::registerSubscriberFromServices(self::$serviceContainer);
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