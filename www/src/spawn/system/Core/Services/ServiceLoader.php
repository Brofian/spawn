<?php declare(strict_types=1);

namespace spawn\system\Core\Services;


use spawn\system\Core\Base\Database\Definition\EntityCollection;
use spawn\system\Core\Contents\XMLContentModel;
use spawn\system\Core\Helper\XMLReader;
use spawnApp\Database\ModuleTable\ModuleEntity;

class ServiceLoader {

    public function loadServices(EntityCollection $moduleCollection): ServiceContainer
    {

        if (ServiceCache::doesServiceCacheExist() && MODE != 'dev') {
            return ServiceCache::readServiceCache();
        }

        $serviceContainer = new ServiceContainer();

        /** @var ModuleEntity $module */
        foreach ($moduleCollection->getArray() as $module) {
            $moduleId = $module->getId();
            $pluginXMLPath = ROOT . $module->getPath() . "/plugin.xml";
            $pluginXML = XMLReader::readFile($pluginXMLPath);

            $services = $this->extractServicesFromPluginXMl($pluginXML);

            foreach ($services as $service) {
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
            if($serviceTag->getAttribute("static")) {
                $service->setStatic($serviceTag->getAttribute("static") != "");
            }

            /** @var XMLContentModel[] $tagElements */
            $tagElements = $serviceTag->getChildrenByType("tag");
            foreach($tagElements as $tagElement) {
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