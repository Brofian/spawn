<?php

namespace webu\system\Core\Contents\Modules;

class ModuleController {

    /** @var string  */
    private $class = "";

    /** @var array  */
    private $actions = array();

    /** @var string  */
    private $id = "";


    public function __construct(string $id, string $class, array $actions)
    {
        $this->id = $id;
        $this->class = $class;
        $this->actions = $actions;
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
    public function getClass() {
        return $this->class;
    }

    /**
     * @return array
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * @return mixed
     */
    public function getInstance() {
        $cls = $this->class;
        return new $cls();
    }

}