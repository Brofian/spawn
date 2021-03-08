<?php

namespace webu\system\core;


/*
 *  The Main Class to store all Response informations
 */

use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\Context;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Helper\FrameworkHelper\ResourceCollector;
use webu\system\Core\Helper\ScssHelper;
use webu\system\Core\Helper\TwigHelper;
use webu\system\Environment;

class Response
{

    /** @var string */
    private $html = '';
    /** @var int */
    private $responseCode = 200;
    /** @var TwigHelper  */
    private $twigHelper = null;
    /** @var ScssHelper  */
    private $scssHelper = null;

    public function __construct()
    {
        $this->twigHelper = new TwigHelper();
        $this->scssHelper = new ScssHelper();
    }


    /**
     * @param Environment $environment
     */
    public function prepare(Environment $environment) {

        //gather resources from the modules
        if(ResourceCollector::isGatheringNeeded() || MODE == 'dev-false') {
            $resourceCollector = new ResourceCollector();
            $resourceCollector->gatherModuleData($environment->request->getModuleCollection());
        }


    }


    /**
     * @param Context $context
     * @param ModuleCollection $moduleCollection
     * @return void
     */
    public function finish(ModuleCollection $moduleCollection, Context $context) {

        /* Render Scss */
        $this->scssHelper->createCss($moduleCollection);


        /* Render twig */
        $this->getTwigHelper()->assign('context', $context->getContext());
        $pageHtml = $this->getTwigHelper()->finish();

        $this->html = $pageHtml;
    }







    /**
     * @return TwigHelper
     */
    public function getTwigHelper() {
        return $this->twigHelper;
    }

    /**
     * @return ScssHelper
     */
    public function getScssHelper() {
        return $this->scssHelper;
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string
     */
    public function setHtml(string $html)
    {
        $this->html = $html;
    }


}