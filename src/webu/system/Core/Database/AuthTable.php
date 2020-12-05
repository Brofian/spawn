<?php

namespace webu\system\Core\Database;

use webu\actions\DatabaseSetupAction;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Base\Database\Storage\DatabaseIndex;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class AuthTable extends DatabaseTable
{

    public function __construct()
    {
        parent::__construct(true, true, true);
        $this->init();
    }


    public function getTableName(): string
    {
        return "webu_auth";
    }


    public function init(): bool
    {

        $col = new DatabaseColumn('username', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(50)
            ->setIndex(DatabaseIndex::UNIQUE);
        $this->addColumn($col);


        $col = new DatabaseColumn('email', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(100);
        $this->addColumn($col);


        $col = new DatabaseColumn('password', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(100);
        $this->addColumn($col);


        return true;
    }

    public function afterCreation(DatabaseHelper $dbhelper)
    {
        $qb = new QueryBuilder($dbhelper->getConnection());

        $qb->insert()
            ->into($this->getTableName())
            ->setValue('username', 'admin')
            ->setValue('email', '')
            ->setValue('password', password_hash("admin", PASSWORD_DEFAULT))
            ->execute();
    }
}