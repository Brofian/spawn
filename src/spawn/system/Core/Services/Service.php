<?php declare(strict_types=1);

namespace spawn\system\Core\Services;

use ReflectionClass;
use spawn\system\Core\Custom\Mutable;

class Service extends Mutable {

    //the unique key, that identifies a service (is substituted by class, if not set)
    protected ?string $id = null;
    //the class, that corresponds to this service (is substituted by id, if not set)
    protected ?string $class = null;
    //abstract services can not be called as an instance and therefor dont need a class
    protected ?bool $abstract = null;
    //static services generate only one instance. This same instance is then shared whenever it is called
    protected ?bool $static = null;
    protected $instance = null;
    //this service can decorate another. When the other service is called, it will be replaced by this automatically
    protected ?string $decorates = null;
    //if set, this service uses the arguments of its parent before of its own
    protected ?string $parent = null;
    //free string, that is used to separate services by their functionality
    protected ?string $tag = ServiceTags::BASE_SERVICE;
    //the id of the module, this service belongs to
    protected ?string $moduleId = null;
    //the arguments, that are given when the class of this service is instanciated. Can either be a fixed value or another service
    /** @var string[]  */
    protected array $arguments = array();

    private ?ServiceContainer $serviceContainer;


    public function getInstance() {
        if($this->isAbstract()) {
            return null;
        }

        //if this is a static service, that was called before, just return the existing instance
        if($this->isStatic() && $this->instance) {
            return $this->instance;
        }

        //generate a new instance with the respective arguments
        try {
            $arguments = $this->getCallArguments();
            $reflection = new ReflectionClass($this->class);
            $myClassInstance = $reflection->newInstanceArgs($arguments);
        } catch (\ReflectionException $e) {
            return null;
        }

        //save the generated instance for the next use, if the service is static
        if($this->isStatic()) {
            $this->instance = $myClassInstance;
        }


        return $myClassInstance;
    }


    public function getCallArguments() : array {
        $arguments = [];

        if($this->getParent() !== null) {
            //if this service has a parent, include the parents arguments first
            $arguments = $this->serviceContainer->getService($this->parent)->getCallArguments();
        }

        foreach($this->arguments as $argument) {
            $arguments[] = $this->getValueFromArgument($argument);
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

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    public function setArguments(?array $arguments): self
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function addArgument(string $type, string $value) {
        $this->arguments[] = [
            'type' => $type,
            'value' => $value
        ];
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

    public function isStatic(): ?bool
    {
        return $this->static;
    }

    public function setStatic(bool $static): self
    {
        $this->static = $static;
        return $this;
    }

    public function setInstance($instance): self {
        $this->instance = $instance;
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

    public function getServiceContainer() : ?ServiceContainer {
        return $this->serviceContainer;
    }

    public function setServiceContainer(?ServiceContainer $serviceContainer) : self {
        $this->serviceContainer = $serviceContainer;
        return $this;
    }

    public function getModuleId() : ?string {
        return $this->moduleId;
    }

    public function setModuleId(?string $moduleId) : self {
        $this->moduleId = $moduleId;
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
        $service = new self();

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