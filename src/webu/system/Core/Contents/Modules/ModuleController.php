<?php declare(strict_types=1);

namespace webu\system\Core\Contents\Modules;

class ModuleController {

    /** @var string  */
    private $class = "";

    /** @var string  */
    private $name = "";

    /**
     * ModuleController constructor.
     * @param string $class
     * @param string $name
     * @param array $actions
     */
    public function __construct(string $class, string $name)
    {
        $this->class = $class;
        $this->name = basename(str_replace("\\","/",$name));
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
     * @return mixed
     */
    public function getInstance() {
        $cls = $this->class;
        return new $cls();
    }

}