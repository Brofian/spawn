<?php

namespace webu\system\core;


/*
 *  The Main Class to store all Response informations
 */

use Twig\Environment;
use Twig\Loader\ArrayLoader;
use webu\system\Core\Custom\Debugger;
use webu\system\Core\Helper\TwigHelper;

class Response
{

    /** @var string */
    private $html = '';
    /** @var int */
    private $responseCode = 200;
    /** @var TwigHelper  */
    private $twigHelper = false;

    public function __construct()
    {
        $this->loadTwig();

    }


    public function loadTwig() {
        $this->twigHelper = new TwigHelper();
    }


    /**
     * @return TwigHelper
     */
    public function getTwigHelper() {
        return $this->twigHelper;
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