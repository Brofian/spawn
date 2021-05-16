<?php

namespace webu\system\Core\Contents\Modules;

use SimpleXMLElement;
use webu\system\Core\Custom\StringConverter;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLHelper;

class ModuleLoader {

    const REL_XML_PATH = "/plugin.xml";

    protected $moduleRootPaths = [
        "/custom",
        "/vendor"
    ];

    protected $ignoredDirs = [
        ".",
        ".."
    ];


    /** @var ModuleCollection */
    private $moduleCollection;

    /**
     * ModuleLoader constructor.
     */
    public function __construct()
    {
        $this->moduleCollection = new ModuleCollection();
    }

    /**
     * @param string $rootPath
     * @return bool|ModuleCollection
     */
    public function loadModules() {
        /*
        $cachedModuleCollection = ModuleCacher::readModuleCache();
        if($cachedModuleCollection && MODE != 'dev') return $cachedModuleCollection;
        */

        /*
         * Stucture:
         * - moduleRootPath
         * -- moduleNamespacePath
         * --- modulePath
         */


        $moduleFolders = [];
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

                        //TODO: dump($this->moduleLocationToSlug($namespace, $moduleElement));

                        $this->loadModule(basename($currentModulePath), $currentModulePath, $moduleCount);
                        $moduleCount++;
                    }
                }
            }
        }

        /*
        dd($moduleFolders);
        die();
        */

        ModuleCacher::createModuleCache($this->moduleCollection);

        return $this->moduleCollection;
    }


    public static function moduleLocationToSlug($namespace, $module) {
        $namespace = StringConverter::snakeToPascalCase($namespace);
        $module = StringConverter::snakeToPascalCase($module);
        return ($namespace . $module);
    }



    protected function isModuleDirectory($directory) : bool {
        $xmlFilePath = URIHelper::joinPaths($directory, "plugin.xml");
        if(!file_exists($xmlFilePath) || !is_file($xmlFilePath)) {
            return false;
        }

        $directoryName = basename($directory);
        $moduleClassPath = URIHelper::joinPaths($directory, $directoryName.".php");
        if(!file_exists($moduleClassPath) || !is_file($moduleClassPath)) {
            return false;
        }

        return true;
    }


    /**
     * @param $moduleName
     * @param $basePath
     * @param $moduleId
     */
    private function loadModule($moduleName, $basePath, $moduleId) {

        $module = new Module($moduleName);
        $module->setBasePath($basePath);

        /** @var $pluginXML SimpleXMLElement */
        $pluginXML = (new XMLHelper())->readFile($basePath . self::REL_XML_PATH);


        /*
         * Set Module active
         */
        $module->setActive(((string)$pluginXML->attributes()->active == "true"));


        /*
         * Set Modle Informations
         */
        if(isset($pluginXML->info)) {
            foreach($pluginXML->info->children() as $key => $info) {
                $module->setInformation($key, trim($info));
            }
        }


        /*
         * Load Controllers
         */
        if(isset($pluginXML->controllerlist) && $module->isActive()) {
            foreach($pluginXML->controllerlist->children() as $controller) {

                $controllerActions = array();
                foreach($controller->actions->action as $action) {
                    $controllerActions[] = new ModuleAction(
                        (string)$action->attributes()->id,
                        (string)$action->url,
                        (string)$action->method
                    );

                }


                $moduleController = new ModuleController(
                    (string)$controller["class"],
                    (string)$controller["name"],
                    $controllerActions
                );
                $module->addModuleController($moduleController);
            }
        }


        /*
         * Load Resources
         */
        if(isset($pluginXML->resources)) {
            $module->setResourceWeight((string)$pluginXML->resources->attributes()->weight);

            $module->setResourcePath((string)$pluginXML->resources);

            $namespace = (string)$pluginXML->resources->attributes()->namespace;

            if($namespace == "") {
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
        if(isset($pluginXML->using)) {
            $using = (array)$pluginXML->using;

            $module->setUsingNamespaces((array)$using["namespace"]);
        }


        /*
         * Database tables
         */
        if(isset($pluginXML->tablelist)) {
            foreach($pluginXML->tablelist->children() as $child) {
                $module->addDatabaseTableClass((string)$child->attributes()->class);
            }
        }


        $module->setId("$moduleId");
        $this->moduleCollection->addModule($module);
    }



}