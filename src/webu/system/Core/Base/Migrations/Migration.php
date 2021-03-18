<?php

namespace webu\system\core\base;

use webu\system\Core\Base\Helper\DatabaseHelper;

abstract class Migration {

    abstract public static function getUnixTimestamp() : int;

    public function __construct() {
    }


    abstract function run(DatabaseHelper $dbHelper);



}