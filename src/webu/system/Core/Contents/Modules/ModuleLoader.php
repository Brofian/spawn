<?php

namespace webu\system\Core\Contents\Modules;

use SimpleXMLElement;
use webu\Database\StructureTables\WebuModuleActions;
use webu\Database\StructureTables\WebuModules;
use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Contents\XMLContentModel;
use webu\system\Core\Custom\StringConverter;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLHelper;
use webu\system\Core\Helper\XMLReader;
use webuApp\Database\ModuleTable;
use webuApp\Models\ModuleActionStorage;
use webuApp\Models\ModuleStorage;

class ModuleLoader {

    const REL_XML_PATH = "/plugin.xml";

    protected array $moduleRootPaths = [
        "/custom",
        "/vendor"
    ];

    protected array $ignoredDirs = [
        ".",
        ".."
    ];

    private ?ModuleCollection $moduleCollection = null;


    public function __construct()
    {
        $this->moduleCollection = new ModuleCollection();
    }


    public function loadModules(DatabaseConnection $connection) : ModuleCollection {

        $moduleEntries = ModuleStorage::findAll($connection);

        if(count($moduleEntries) < 1) {
            return $this->readModules($connection);
        }

        $this->moduleCollection = new ModuleCollection();

        foreach($moduleEntries as $moduleEntry) {

            $module = new Module($moduleEntry->getSlug());

            $module
                ->setId($moduleEntry->getId())
                ->setActive($moduleEntry->isActive())
                ->setSlug($moduleEntry->getSlug())
                ->setBasePath($moduleEntry->getPath());

            $resourceConfig = json_decode($moduleEntry->getResourceConfig());
            $module
                ->setUsingNamespaces($resourceConfig->using??[])
                ->setResourceWeight($resourceConfig->weight??1)
                ->setResourceNamespace($resourceConfig->namespace??ModuleNamespacer::getGlobalNamespace())
                ->setResourceNamespaceRaw($resourceConfig->namespace_raw??ModuleNamespacer::getGlobalNamespaceRaw())
                ->setResourcePath($resourceConfig->path??"");


            $informations = json_decode($moduleEntry->getInformations());

            foreach($informations as $key => $value) {
                $module->setInformation($key, $value);
            }

            $this->moduleCollection->addModule($module);
        }

        return $this->moduleCollection;
    }

    /**
     * This functions reads the modules live from the existing files
     */
    public function readModules(DatabaseConnection $connection) : ModuleCollection {

        /*
         * Stucture:
         * - moduleRootPath
         * -- moduleNamespacePath
         * --- modulePath (snake-case)
         */


        $moduleCount = 0;
        foreach($this->moduleRootPaths as $rootPath) {
            $currentPath = URIHelper::joinPaths(ROOT, $rootPath);
            if(!is_dir($currentPath)) continue;

            $modulePathElements = scandir($currentPath);

            foreach($modulePathElements as $namespace) {
                if(in_array($namespace, $this->ignoredDirs)) continue;
                $currentNamespacePath = URIHelper::joinPaths($currentPath, $namespace);
                if(!is_dir($currentNamespacePath)) continue;


                $moduleElements = scandir($currentNamespacePath);
                foreach($moduleElements as $moduleElement) {
                    $currentModulePath = URIHelper::joinPaths($currentNamespacePath, $moduleElement);
                    if($this->isModuleDirectory($currentModulePath)) {

                        $slug = $this->moduleLocationToSlug($namespace, $moduleElement);

                        $this->loadModule($currentModulePath, $slug, $connection);
                        $moduleCount++;
                    }
                }
            }
        }


        return $this->moduleCollection;
    }


    public static function moduleLocationToSlug($namespace, $module) {
        $namespace = StringConverter::snakeToPascalCase($namespace);
        $module = StringConverter::snakeToPascalCase($module);
        return ($namespace . $module);
    }



    protected function isModuleDirectory($directory) : bool {
        $xmlFilePath = URIHelper::joinPaths($directory, "plugin.xml");
        return (file_exists($xmlFilePath) && is_file($xmlFilePath));
    }


    private function loadModule(string $basePath, string $slug, DatabaseConnection $connection) {

        $module = new Module($slug);
        $module->setActive(false);
        $module->setBasePath(str_replace(ROOT, "", $basePath));
        $module->setSlug($slug);

        $moduleXML = (new XMLReader())->readFile($basePath . self::REL_XML_PATH);




        /*
         * Set Module Informations
         */
        /** @var XMLContentModel $moduleInfo */
        $moduleInfo = $moduleXML->getChildrenByType("info")->first();
        if($moduleInfo) {
            foreach($moduleInfo->getChildren() as $childInfo) {
                $module->setInformation($childInfo->getType(), trim($childInfo->getValue()));
            }
        }



        /*
         * Load Resources
         */
        /** @var XMLContentModel $moduleResources */
        $moduleResources = $moduleXML->getChildrenByType("resources")->first();
        if($moduleResources) {

            $module->setResourceWeight($moduleResources->getAttribute("weight"));
            $module->setResourcePath($moduleResources->getValue());
            $namespace = $moduleResources->getAttribute("namespace");

            if(!$namespace) {
                $namespace = ModuleNamespacer::getGlobalNamespaceRaw();
                $namespaceHash = ModuleNamespacer::getGlobalNamespace();
            }
            else {
                $namespaceHash = ModuleNamespacer::hashRawNamespace($namespace);
            }

            $module->setResourceNamespace($namespaceHash);
            $module->setResourceNamespaceRaw($namespace);
        }


        /*
         * Load "using"
         */
        /** @var XMLContentModel $moduleUsing */
        $moduleUsing = $moduleXML->getChildrenByType("using")->first();
        if($moduleUsing) {

            $usingNamespaces = [];
            foreach($moduleUsing->getChildrenByType("namespace") as $namespace) {
                $usingNamespaces[] = $namespace->getValue();
            }

            $module->setUsingNamespaces($usingNamespaces);
        }


        $moduleStorage = new ModuleStorage(
            $module->getSlug(),
            $module->getBasePath(),
            $module->isActive(),
            $module->getInformationsAsJson(),
            $module->getResourceConfigJson()
        );
        $moduleStorage->save($connection);


        $this->moduleCollection->addModule($module);
    }



}