<?php

namespace webu\modules\Index\Database;

use webu\actions\DatabaseSetupAction;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Base\Database\Storage\DatabaseIndex;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class ProgrammingLanguageTable extends DatabaseTable
{

    public function __construct()
    {
        parent::__construct(true, true, true);
        $this->init();
    }


    public function getTableName(): string
    {
        return "webu_programming_languages";
    }


    public function init(): bool
    {
        $col = new DatabaseColumn('name', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(250);
        $this->addColumn($col);

        return true;
    }

    public function afterCreation(DatabaseHelper $dbhelper)
    {
        $qb = new QueryBuilder($dbhelper->getConnection());
        $qb->insert()
            ->into($this->getTableName())
            ->setValue('name', 'PHP');
    }
}