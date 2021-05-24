<?php

namespace webu\system\Core\Contents\Modules;

class ModuleController {

    /** @var string  */
    private $class = "";

    /** @var string  */
    private $name = "";

    /** @var array  */
    private $actions = array();

    /**
     * ModuleController constructor.
     * @param string $class
     * @param string $name
     * @param array $actions
     */
    public function __construct(string $class, string $name, array $actions)
    {
        $this->class = $class;
        $this->name = $name;
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
    public function getClass() {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * @return array
     */
    public function getActionsAsArray() {

        $actionArray = [];
        /** @var ModuleAction $action */
        foreach($this->actions as $action) {
            $actionArray[] = [
               "id" => $action->getId(),
               "action" => $action->getAction(),
               "c_url" => $action->getCustomUrl(),
            ];
        }


        return $actionArray;
    }

    /**
     * @return mixed
     */
    public function getInstance() {
        $cls = $this->class;
        return new $cls();
    }

    /**
     * @param ModuleAction $action
     */
    public function addAction(ModuleAction $action) {
        $this->actions[] = $action;
    }
}