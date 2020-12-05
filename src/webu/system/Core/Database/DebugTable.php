<?php

namespace webu\system\Core\Database;

use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class DebugTable extends DatabaseTable
{

    public function __construct()
    {
        parent::__construct(true, true, true);

        $this->init();
    }


    public function getTableName(): string
    {
        return "debug_test_table";
    }


    public function init(): bool
    {

        $col = new DatabaseColumn('value', DatabaseType::INT);
        $col->setDefault(42)
            ->setCanBeNull(true)
            ->setLength(22);

        $this->addColumn($col);


        //$this->setForeignKey('value', 'testcontent', 'id');

        return true;
    }

    public function afterCreation(DatabaseHelper $dbhelper)
    {
        // TODO: Implement afterCreation() method.
    }
}