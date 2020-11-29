<?php

namespace webu\system\core;


/*
 *  The Main Class to store all Response informations
 */

use webu\system\Core\Contents\Context;
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

    public function __construct()
    {
        $this->loadTwig();
        $this->loadScss();
    }

    public function loadTwig() {
        $this->twigHelper = new TwigHelper();
    }

    public function loadScss() {
        $this->scssHelper = new ScssHelper();
    }


    public function finish($context) {

        /* Render Scss */
        $this->scssHelper->createCss();

        /* Render twig */
        $this->getTwigHelper()->assign('context', $context);
        $pageHtml = $this->getTwigHelper()->finish();

        echo $pageHtml;
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