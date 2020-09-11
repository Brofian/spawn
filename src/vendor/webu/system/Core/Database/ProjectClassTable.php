<?php

use webu\system\Core\Base\Database\DatabaseTable;

class ProjectClassTable extends DatabaseTable {


    public function getTableName() : string
    {
        return "project_classes";
    }


    public function init(): bool
    {
        // TODO: Implement init() method.
    }
}