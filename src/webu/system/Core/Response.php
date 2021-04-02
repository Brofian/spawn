<?php

namespace webu\system\core;


/*
 *  The Main Class to store all Response informations
 */

use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\Context;
use webu\system\Core\Contents\Modules\ModuleCollection;
use webu\system\Core\Helper\FrameworkHelper\ResourceCollector;
use webu\system\Core\Helper\HeaderHelper;
use webu\system\Core\Helper\ScssHelper;
use webu\system\Core\Helper\TwigHelper;
use webu\system\Environment;

class Response
{

    /** @var Environment  */
    private $environment = null;

    /** @var string */
    private $html = '';
    /** @var int */
    private $responseCode = 200;
    /** @var TwigHelper  */
    private $twigHelper = null;
    /** @var ScssHelper  */
    private $scssHelper = null;
    /** @var HeaderHelper  */
    private $headerHelper = null;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;

        $this->headerHelper = new HeaderHelper($environment->request, $this);
        $this->twigHelper = new TwigHelper();
        $this->scssHelper = new ScssHelper();
    }



    public function prepare() {

        //gather resources from the modules
        if(ResourceCollector::isGatheringNeeded() || MODE == 'dev') {
            $resourceCollector = new ResourceCollector();
            $resourceCollector->gatherModuleData($this->environment->request->getModuleCollection());
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

        /* set headers send by before sending actual html to prevent problems */
        $this->headerHelper->setHeadersSentBy();

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
     * @return HeaderHelper
     */
    public function getHeaderHelper() {
        return $this->headerHelper;
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