<?php

namespace webu\system\Core\Base\Module;

abstract class BaseModule {

    public abstract function install();

    public abstract function uninstall();

}