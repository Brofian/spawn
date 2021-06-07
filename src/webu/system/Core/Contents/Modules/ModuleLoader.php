<?php

namespace webu\system\Core\Contents\Modules;

use SimpleXMLElement;
use webu\Database\StructureTables\WebuModuleActions;
use webu\Database\StructureTables\WebuModules;
use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Custom\StringConverter;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLHelper;
use webuApp\Models\ModuleActionStorage;
use webuApp\Models\ModuleStorage;

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
    private $moduleCollection = null;

    /**
     * ModuleLoader constructor.
     */
    public function __construct()
    {
        $this->moduleCollection = new ModuleCollection();
    }


    public function loadModules(DatabaseConnection $connection) : ModuleCollection {
        /** @var \PDOStatement $unfetchedActions */
        $unfetchedActions = ModuleStorage::loadAllWithReferences($connection);


        $moduleArray = array();
        $moduleControllerArray = array();
        $moduleActionArray = array();
        while($action = $unfetchedActions->fetch()) {
            $moduleSlug = $action[WebuModules::RAW_COL_SLUG];

            if(!isset($moduleArray[$moduleSlug])) {
                $module = new Module(basename($action[WebuModules::RAW_COL_PATH]));
                $module->setActive($action[WebuModules::RAW_COL_ACTIVE]);
                $module->setId($action[WebuModules::RAW_COL_ACTIVE]);
                $module->setBasePath($action[WebuModules::RAW_COL_PATH]);
                $module->setSlug($moduleSlug);

                $moduleResourceConfig = json_decode($action[WebuModules::RAW_COL_RESSOURCE_CONFIG], true);
                $module->setUsingNamespaces($moduleResourceConfig["using"]);
                $module->setResourceNamespaceRaw($moduleResourceConfig["namespace_raw"]);
                $module->setResourceNamespace($moduleResourceConfig["namespace"]);
                $module->setResourcePath($moduleResourceConfig["path"]);
                $module->setResourceWeight($moduleResourceConfig["weight"]);

                $moduleInformations = json_decode($action[WebuModules::RAW_COL_INFORMATIONS], true);
                foreach($moduleInformations as $key => $information) {
                    $module->setInformation($key, $information);
                }

                $moduleArray[$moduleSlug] = $module;
            }


            $controllerClass = $action[WebuModuleActions::RAW_COL_CLASS];
            if($controllerClass && !isset($moduleControllerArray[$controllerClass])) {
                $controller = new ModuleController($controllerClass, basename($controllerClass), []);
                $controller->moduleSlug = $action[WebuModules::RAW_COL_SLUG];
                $moduleControllerArray[$controllerClass] = $controller;
            }


            if($action[WebuModuleActions::RAW_COL_IDENTIFIER]) {
                $modAction = new ModuleAction(
                    $action[WebuModuleActions::RAW_COL_IDENTIFIER],
                    $action[WebuModuleActions::RAW_COL_CUSTOM_URL],
                    $action[WebuModuleActions::RAW_COL_ACTION]
                );
                $modAction->controllerCls = $action[WebuModuleActions::RAW_COL_CLASS];
                $moduleActionArray[] = $modAction;
            }

        }



        //combine the loaded parts
        /** @var ModuleController $controller */
        foreach($moduleControllerArray as $ctrlClass => &$controller) {
            foreach($moduleActionArray as $action) {
                if($action->controllerCls == $ctrlClass) {
                    $controller->addAction($action);
                }
            }
        }

        /** @var Module $modules */
        foreach($moduleArray as &$module) {

            foreach($moduleControllerArray as $controllerFromArray) {
                if($module->getSlug() == $controllerFromArray->moduleSlug) {
                   $module->addModuleController($controllerFromArray);
                }
            }
            $this->moduleCollection->addModule($module);
        }

        return $this->moduleCollection;
    }

    /**
     * This functions reads the modules live from the existing files
     *
     * @param string $rootPath
     * @return ModuleCollection
     */
    public function readModules() : ModuleCollection {

        /*
         * Stucture:
         * - moduleRootPath
         * -- moduleNamespacePath
         * --- modulePath (snake-case)
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

                        $slug = $this->moduleLocationToSlug($namespace, $moduleElement);

                        $this->loadModule(basename($currentModulePath), $currentModulePath, $moduleCount, $slug);
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


    /**
     * @param $moduleName
     * @param $basePath
     * @param $moduleId
     */
    private function loadModule($moduleName, $basePath, $moduleId, $slug) {

        $module = new Module($moduleName);
        $module->setBasePath(str_replace(ROOT, "", $basePath));
        $module->setSlug($slug);

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