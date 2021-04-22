<?php

namespace webu\system\core\base;

use webu\system\Core\Base\Helper\DatabaseHelper;

abstract class Migration {

    /**
     * @return int
     */
    abstract public static function getUnixTimestamp() : int;

    /**
     * Migration constructor.
     */
    public function __construct() {
    }


    /**
     * @param DatabaseHelper $dbHelper
     * @return mixed
     */
    abstract function run(DatabaseHelper $dbHelper);



}