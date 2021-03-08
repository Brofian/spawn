<?php

namespace webu\system\Core\Contents\Modules;

use SimpleXMLElement;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Helper\XMLHelper;

class ModuleLoader {

    const REL_XML_PATH = "/plugin.xml";

    /** @var ModuleCollection */
    private $moduleCollection;

    public function __construct()
    {
        $this->moduleCollection = new ModuleCollection();
    }


    public function loadModules(string $rootPath) {

        $cachedModuleCollection = ModuleCacher::readModuleCache();
        if($cachedModuleCollection && MODE != 'dev') return $cachedModuleCollection;


        if(!is_dir($rootPath)) return false;

        $moduleFolders = scandir($rootPath);

        $moduleCount = 0;
        foreach($moduleFolders as $moduleFolder) {
            if($moduleFolder == "." || $moduleFolder == "..") continue;

            $basePath = $rootPath . "/" . $moduleFolder ;
            URIHelper::pathifie($basePath);

            $this->loadModule($moduleFolder, $basePath, $moduleCount);
            $moduleCount++;
        }



        ModuleCacher::createModuleCache($this->moduleCollection);

        return $this->moduleCollection;
    }

    private function loadModule($moduleName, $basePath, $moduleId) {

        if( !file_exists(URIHelper::joinPaths($basePath, self::REL_XML_PATH)) ||
            !file_exists(URIHelper::joinPaths($basePath ,DIRECTORY_SEPARATOR.$moduleName.".php")))
        {
            return;
        }



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
                $namespaceHash = ModuleNamespacer::getGlobalNamespace();
            }
            else {
                $namespaceHash = ModuleNamespacer::hashRawNamespace($namespace);
            }
            $module->setResourceNamespace($namespaceHash);
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