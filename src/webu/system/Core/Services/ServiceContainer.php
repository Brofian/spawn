<?php

namespace webu\system\Core\Services;


class ServiceContainer {

    /** @var Service[]  */
    protected array $services = array();
    /** @var String[]  */
    protected array $decorations = array();

    public function addService(Service $service) : self{
        $this->services[$service->getId()] = $service->setServiceContainer($this);
        return $this;
    }

    public function getService(string $key) : ?Service{

        //check if this key is decorated -> query and return the decorating service
        if(isset($this->decorations[$key])) {
            return $this->getService($this->decorations[$key]);
        }

        //check if this service is registered
        if(isset($this->services[$key])) {
            return $this->services[$key];
        }
        else {
            return null;
        }
    }

    public function getServiceInstance(string $key) {

        $service = $this->getService($key);

        if($service) {
            return $this->services[$key]->getInstance();
        }

        return null;
    }

    /**
     * @param string $tag
     * @return Service[]
     */
    public function getServicesByTag(string $tag) : array {
        $services = [];

        foreach($this->services as $service) {
            if($service->getTag() == $tag) {
                $services[$service->getId()] = $service;
            }
        }

        return $services;
    }


    public function getServices() : ?array {
        return $this->services;
    }


    public function updateDecorations() {
        $this->decorations = [];

        foreach($this->services as $service) {
            $decoratedServiceId = $service->getDecorates();

            if($decoratedServiceId) {
                $decoratedService = $this->getService($decoratedServiceId);

                if($decoratedService) {
                    $this->decorations[$decoratedService->getId()] = $service->getId();
                }
            }
        }
    }

}