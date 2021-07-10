<?php declare(strict_types=1);

namespace webu\system\Core;


/*
 *  The Main Class to store all Response informations
 */

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Helper\FrameworkHelper\ResourceCollector;
use webu\system\Core\Helper\ScssHelper;
use webu\system\Core\Helper\TwigHelper;
use webu\system\Core\Helper\URIHelper;
use webu\system\Core\Services\ServiceContainerProvider;

class Response
{

    protected string $html = '';
    protected TwigHelper $twigHelper;
    protected ScssHelper $scssHelper;
    protected ModuleCollection $moduleCollection;


    public function __construct()
    {
        $serviceContainer = ServiceContainerProvider::getServiceContainer();
        $this->twigHelper = $serviceContainer->getServiceInstance('system.twig.helper');
        $this->scssHelper = $serviceContainer->getServiceInstance('system.scss.helper');
        $this->moduleCollection = $serviceContainer->getServiceInstance('system.modules.collection');

        $this->fillBaseContextData();
    }

    protected function fillBaseContextData() {
        $this->twigHelper->assign("environment", MODE);
        $this->scssHelper->setBaseVariable("assetsPath", URIHelper::createPath([
            CACHE_DIR,"public","assets"
        ], "/"));
    }


    public function prepareFiles() {

        //gather resources from the modules
        if(ResourceCollector::isGatheringNeeded() || MODE == 'dev') {
            $resourceCollector = new ResourceCollector();
            $resourceCollector->gatherModuleData($this->moduleCollection);
        }

        /* Render Scss */
        if (!$this->scssHelper->cacheExists() || MODE == 'dev') {
            $this->scssHelper->createCss($this->moduleCollection);
        }

    }


    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function finish(): string {
        /* Render twig */
        return $this->twigHelper->finish();
    }




}