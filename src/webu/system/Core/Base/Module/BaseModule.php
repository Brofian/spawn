<?php declare(strict_types=1);

namespace webu\system\Core\Base\Module;

abstract class BaseModule {

    /**
     * @return mixed
     */
    public abstract function install();

    /**
     * @return mixed
     */
    public abstract function uninstall();

}