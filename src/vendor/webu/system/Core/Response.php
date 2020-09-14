<?php

namespace webu\system\core;


/*
 *  The Main Class to store all Response informations
 */

class Response
{

    /** @var string */
    private $html = '';
    private $responseCode = 200;
    private $templateFolders = array();

    public function __construct()
    {
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