<?php

namespace webu\system\Core\Contents\Modules;

class ModuleAction {


    /** @var string  */
    private $id = "";
    /** @var string  */
    private $c_url = "";
    /** @var string  */
    private $action = "";

    /**
     * ModuleAction constructor.
     * @param string $id
     * @param string $c_url
     * @param string $action
     */
    public function __construct(string $id, string $c_url, string $action)
    {
        $this->id = $id;
        $this->c_url = $c_url;
        $this->action = $action;
    }



    /*
     *
     * GETTER
     *
     */


    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getCustomUrl() {
        return $this->c_url;
    }

}