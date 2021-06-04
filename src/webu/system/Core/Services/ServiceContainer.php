<?php

namespace webu\system\Core\Services;


class ServiceContainer {

    /** @var Service[]  */
    protected array $services = array();

    public function addService(Service $service) : self{
        $this->services[$service->getId()] = $service;
        return $this;
    }

    public function getService(string $key) : ?Service{
        if(isset($this->services[$key])) {
            return $this->services[$key];
        }
        else {
            return null;
        }
    }

    public function getServiceInstance(string $key) {
        if(isset($this->services[$key])) {
            return $this->services[$key]->getInstance();
        }
        else {
            return null;
        }
    }

    public function getServicesByTag(string $tag) : ?array {
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

}