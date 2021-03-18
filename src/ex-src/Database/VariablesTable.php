<?php

namespace webu\system\Core\Database;

use webu\actions\DatabaseSetupAction;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Base\Database\Storage\DatabaseIndex;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class VariablesTable extends DatabaseTable
{

    public function __construct()
    {
        parent::__construct(true, true, true);
        $this->init();
    }


    public function getTableName(): string
    {
        return "webu_variables";
    }


    public function init(): bool
    {

        $col = new DatabaseColumn('name', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(150);
        $this->addColumn($col);


        $col = new DatabaseColumn('namespace', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(100);
        $this->addColumn($col);


        $col = new DatabaseColumn('type', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(100);
        $this->addColumn($col);

        $col = new DatabaseColumn('value', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(100);
        $this->addColumn($col);

        $col = new DatabaseColumn('editable', DatabaseType::BOOLEAN);
        $col->setCanBeNull(true)
            ->setDefault(1);
        $this->addColumn($col);


        return true;
    }

    public function afterCreation(DatabaseHelper $dbhelper)
    {
        $qb = new QueryBuilder($dbhelper->getConnection());

        $qb->insert()
            ->into($this->getTableName())
            ->setValue('name', 'white')
            ->setValue('namespace', 'webu/system')
            ->setValue('type', "color")
            ->setValue('value', "#ffffffff")
            ->setValue('editable', 0)
            ->execute();

        $qb->insert()
            ->into($this->getTableName())
            ->setValue('name', 'black')
            ->setValue('namespace', 'webu/system')
            ->setValue('type', "color")
            ->setValue('value', "#000000ff")
            ->setValue('editable', 0)
            ->execute();
    }
}