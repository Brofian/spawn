<?php declare(strict_types=1);

namespace spawn\system\Core\Contents\Modules;

class Module {

    protected ?string $id = null;
    protected bool $active = false;
    protected array $informations = array();
    protected string $moduleName;
    protected string $basePath;
    protected string $resourcePath;
    protected int $resourceWeight;
    protected string $resourceNamespace;
    protected string $resourceNamespaceRaw;
    protected array $databaseTableClasses = array();
    protected array $usingNamespaces = array();
    protected string $slug;


    public function __construct(string $moduleName)
    {
        $this->moduleName = $moduleName;
    }


    /*
     *
     * SETTER
     *
     */


    public function addDatabaseTableClass(string $tableClass) {
        $this->databaseTableClasses[] = $tableClass;
    }


    public function setInformation(string $key, string $value) : self {
        $this->informations[$key] = $value;
        return $this;
    }

    public function setBasePath(string $basePath) : self{
        $this->basePath = $basePath;
        return $this;
    }

    public function setResourcePath(string $resourcePath) : self{
        $this->resourcePath = $resourcePath;
        return $this;
    }

    public function setResourceWeight(int $resourceWeight) : self {
        $this->resourceWeight = $resourceWeight;
        return $this;
    }

    public function setResourceNamespace(string $resourceNamespace) : self {
        $this->resourceNamespace = $resourceNamespace;
        return $this;
    }


    public function setResourceNamespaceRaw(string $resourceNamespaceRaw): self {
        $this->resourceNamespaceRaw = $resourceNamespaceRaw;
        return $this;
    }




    /*
     *
     * GETTER
     *
     */

    public function getName() : string {
        return $this->moduleName;
    }

    public function getInformation(string $key = "") : string {
        if(isset($this->informations[$key])) {
            return $this->informations[$key];
        }
        else {
            return "";
        }
    }

    public function getInformations() : array {
        return $this->informations;
    }

    public function getInformationsAsJson() : string {
        return json_encode($this->informations);
    }


    public function getBasePath() : string {
        return $this->basePath;
    }


    public function getResourceConfigJson() : string {
        return json_encode([
            "weight" => $this->getResourceWeight(),
            "namespace" => $this->getResourceNamespace(),
            "namespace_raw" => $this->getResourceNamespaceRaw(),
            "using" => $this->getUsingNamespaces(),
            "path" => $this->getResourcePath()
        ]);
    }

    public function getResourcePath() : string {
        return $this->resourcePath;
    }

    public function getResourceWeight() : int {
        return $this->resourceWeight;
    }

    public function getResourceNamespace() : string {
        return $this->resourceNamespace;
    }

    public function getResourceNamespaceRaw() : string {
        return $this->resourceNamespaceRaw;
    }

    public function getDatabaseTableClasses() : array {
        return $this->databaseTableClasses;
    }

    public function getUsingNamespaces(): array
    {
        return $this->usingNamespaces;
    }

    public function setUsingNamespaces(array $usingNamespaces): self
    {
        $this->usingNamespaces = $usingNamespaces;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }





}