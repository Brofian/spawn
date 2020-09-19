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
    /** @var array  */
    private $templateFolders = array();
    /** @var TwigHelper  */
    private $twigHelper = false;

    public function __construct()
    {
        $this->loadTwig();
    }


    public function loadTwig() {
        $this->twig = new TwigHelper();
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
     * @return string
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }


}