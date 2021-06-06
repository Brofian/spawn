<?php

namespace webu\system\Core\Services;

use webu\system\Core\Custom\Mutable;

class Service extends Mutable {

    //the unique key, that identifies a service (is substituted by class, if not set)
    protected ?string $id;
    //the class, that corresponds to this service (is substituted by id, if not set)
    protected ?string $class;
    //abstract services can not be called as an instance and therefor dont need a class
    protected ?bool $abstract;
    //this service can decorate another. When the other service is called, it will be replaced by this automatically
    protected ?string $decorates;
    //if set, this service uses the arguments of its parent instead of its own
    protected ?string $parent;
    //free string, that is used to separate services by their functionality
    protected ?string $tag;
    //the arguments, that are given when the class of this service is instanciated. Can either be a fixed value or another service
    /** @var string[]  */
    protected array $arguments;

    protected ServiceContainer $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->id = null;
        $this->class = null;
        $this->abstract = null;
        $this->decorates = null;
        $this->parent = null;
        $this->tag = null;
        $this->arguments = array();
    }


    public function getInstance() {
        if($this->isAbstract()) {
            return null;
        }

        $arguments = $this->getCallArguments();

        return call_user_func_array(new $this->class, $arguments);
    }


    public function getCallArguments() : array {
        $arguments = [];

        if($this->getParent() === null) {
            foreach($this->arguments as $argument) {
                $arguments[] = $this->getValueFromArgument($argument);
            }
        }
        else {
            $arguments = $this->serviceContainer->getService($this->parent)->getCallArguments();
        }

        return $arguments;
    }


    protected function getValueFromArgument(array $argument) {
        $argType = $argument['type'];
        $argValue = $argument['value'];

        switch($argType) {
            case "service":
                return $this->serviceContainer->getServiceInstance($argValue);
            case "value":
                return $argValue;
            default:
                return $argValue;
        }
    }


    public function getId(): ?string
    {
        if($this->id === null) {
            return $this->class;
        }

        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        if($this->class === null) {
            $this->class = $id;
        }

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): void
    {
        $this->tag = $tag;
    }

    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    public function setArguments(?array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function getClass(): ?string
    {
        if($this->class === null) {
            return $this->id;
        }

        return $this->class;
    }

    public function setClass(string $class): self
    {
        if($this->id === null) {
            $this->id = $class;
        }

        $this->class = $class;
        return $this;
    }

    public function isAbstract(): ?bool
    {
        return $this->abstract;
    }

    public function setAbstract(bool $abstract): self
    {
        $this->abstract = $abstract;
        return $this;
    }

    public function getDecorates(): ?string
    {
        return $this->decorates;
    }

    public function setDecorates(string $decorates): self
    {
        $this->decorates = $decorates;
        return $this;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(string $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function __toString()
    {
        $serviceString = '[';
        $serviceString .= "\"id\"=>\"$this->id\",";
        $serviceString .= "\"class\"=>\"$this->class\",";
        $serviceString .= "\"tag\"=>\"$this->tag\",";
        $serviceString .= "\"abstract\"=>".($this->abstract ? "true" : "false").",";
        $serviceString .= "\"decorates\"=>\"$this->decorates\",";
        $serviceString .= "\"parent\"=>\"$this->parent\",";
        $serviceString .= "\"arguments\"=>[";
        $isFirstArgument = true;
        foreach($this->arguments as $argument) {
            if($isFirstArgument) {
                $isFirstArgument = false;
            }
            else {
                $serviceString .= ',';
            }

            $serviceString .= "[\"type\"=>\"".$argument['type']."\",\"value\"=>\"".$argument['value']."\"]";
        }

        $serviceString .= ']]';

        return $serviceString;
    }

    public static function __fromArray(array $serviceArray, ServiceContainer $serviceContainer) : self {
        $service = new self($serviceContainer);

        if($serviceArray["id"]) $service->setId($serviceArray["id"]);
        if($serviceArray["class"]) $service->setClass($serviceArray["class"]);
        if($serviceArray["tag"]) $service->setTag($serviceArray["tag"]);
        if($serviceArray["abstract"]) $service->setAbstract($serviceArray["abstract"]);
        if($serviceArray["decorates"]) $service->setDecorates($serviceArray["decorates"]);
        if($serviceArray["parent"]) $service->setParent($serviceArray["parent"]);
        if($serviceArray["arguments"]) $service->setArguments($serviceArray["arguments"]);

        return $service;
    }

}