<?php

namespace webu\system\Core\Services;


use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Contents\XMLContentModel;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLReader;

class ServiceLoader {

    public function loadServices(ModuleCollection $moduleCollection) : ServiceContainer {

        if(ServiceCache::doesServiceCacheExist() && MODE != 'dev') {
            return ServiceCache::readServiceCache();
        }

        $serviceContainer = new ServiceContainer();
        foreach($moduleCollection->getModuleList() as $module) {
            $moduleId = $module->getId();
            $pluginXMLPath = URIHelper::joinMultiplePaths(ROOT, $module->getBasePath(), "plugin.xml");
            $pluginXML = XMLReader::readFile($pluginXMLPath);

            $services = $this->extractServicesFromPluginXMl($pluginXML);

            foreach($services as $service) {
                $service->setModuleId($moduleId);
                $serviceContainer->addService($service);
            }
        }

        ServiceCache::saveServiceCache($serviceContainer);

        return $serviceContainer;
    }

    /**
     * @param XMLContentModel $pluginXML
     * @return Service[]
     */
    protected function extractServicesFromPluginXMl(XMLContentModel $pluginXML) : array {

        /** @var XMLContentModel $servicesTag */
        $servicesTag = $pluginXML->getChildrenByType("services")->first();
        if(!$servicesTag) return [];

        /** @var XMLContentModel[] $serviceTags */
        $serviceTags = $servicesTag->getChildrenByType("service");

        $services = [];
        foreach($serviceTags as $serviceTag) {
            $service = new Service();

            if($serviceTag->getAttribute("class")) {
                $service->setClass($serviceTag->getAttribute("class"));
            }
            if($serviceTag->getAttribute("id")) {
                $service->setId($serviceTag->getAttribute("id"));
            }
            if($serviceTag->getAttribute("parent")) {
                $service->setParent($serviceTag->getAttribute("parent"));
            }
            if($serviceTag->getAttribute("decorates")) {
                $service->setDecorates($serviceTag->getAttribute("decorates"));
            }
            if($serviceTag->getAttribute("abstract")) {
                $service->setAbstract($serviceTag->getAttribute("abstract") != "");
            }

            /** @var XMLContentModel $tagElement */
            $tagElement = $serviceTag->getChildrenByType("tag")->first();
            if($tagElement) {
                $service->setTag($tagElement->getValue());
            }

            /** @var XMLContentModel[] $arguments */
            $arguments = $serviceTag->getChildrenByType("argument");
            foreach($arguments as $argument) {
                $type = $argument->getAttribute("type");
                $value = $argument->getAttribute("value");
                $service->addArgument($type, $value);
            }


            $services[] = $service;
        }

        return $services;
    }


}