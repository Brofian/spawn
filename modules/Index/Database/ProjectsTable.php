<?php

namespace webu\modules\Index\Database;

use webu\actions\DatabaseSetupAction;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Base\Database\Storage\DatabaseIndex;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class ProjectsTable extends DatabaseTable
{

    public function __construct()
    {
        parent::__construct(true, true, true);
        $this->init();
    }


    public function getTableName(): string
    {
        return "webu_projects";
    }


    public function init(): bool
    {
        $col = new DatabaseColumn('title', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(DatabaseColumn::VARCHAR_SMALL);
        $this->addColumn($col);

        $col = new DatabaseColumn('content', DatabaseType::VARCHAR);
        $col->setCanBeNull(true)
            ->setLength(DatabaseColumn::VARCHAR_MAX);
        $this->addColumn($col);

        $col = new DatabaseColumn('languages', DatabaseType::VARCHAR);
        $col->setCanBeNull(true)
            ->setLength(DatabaseColumn::VARCHAR_SMALL);
        $this->addColumn($col);


        return true;
    }

    public function afterCreation(DatabaseHelper $dbhelper)
    {
    }
}