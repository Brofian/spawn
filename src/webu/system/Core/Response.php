<?php

namespace webu\system\core;


/*
 *  The Main Class to store all Response informations
 */

use webu\system\Core\Base\Custom\FileEditor;
use webu\system\Core\Contents\Context;
use webu\system\Core\Helper\JSHelper;
use webu\system\Core\Helper\ScssHelper;
use webu\system\Core\Helper\TwigHelper;

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
    /** @var JSHelper  */
    private $jsHelper = null;

    public function __construct()
    {
        $this->twigHelper = new TwigHelper();
        $this->scssHelper = new ScssHelper();
        $this->jsHelper   = new JSHelper();
    }



    /**
     * @param Context $context
     * @return void
     */
    public function finish(Context $context) {

        /* Render Scss */
        $this->scssHelper->createCss($context->getBackendContext());

        /* Render JS */
        $jqueryFile = ROOT . '/vendor/components/jquery/jquery.min.js';
        $this->jsHelper->addStaticScript(
            FileEditor::getFileContent($jqueryFile) ?? ''
        );
        $this->jsHelper->unifyJS();

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