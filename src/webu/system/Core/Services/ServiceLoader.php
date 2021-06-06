<?php

namespace webu\system\Core\Services;


use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLReader;

class ServiceLoader {

    public function loadServices(ModuleCollection $moduleCollection) : ServiceContainer {

        if(ServiceCache::doesServiceCacheExist()) {
            return ServiceCache::readServiceCache();
        }

        $serviceContainer = new ServiceContainer();
        foreach($moduleCollection->getModuleList() as $module) {
            $pluginXMLPath = URIHelper::joinPaths($module->getBasePath(), "plugin.xml");
            $pluginXML = XMLReader::readFile($pluginXMLPath);
            $services = $this->extractServicesFromPluginXMl($pluginXML);
        }

        ServiceCache::saveServiceCache($serviceContainer);

        return $serviceContainer;
    }

    protected function extractServicesFromPluginXMl($pluginXML) : array {

        dd($pluginXML);

        return [];
    }


}