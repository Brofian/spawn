<?php

namespace webu\modules\Index\Database;

use webu\actions\DatabaseSetupAction;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\Query\QueryBuilder;
use webu\system\Core\Base\Database\Storage\DatabaseIndex;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class ContactTable extends DatabaseTable
{

    public function __construct()
    {
        parent::__construct(true, true, true);
        $this->init();
    }


    public function getTableName(): string
    {
        return "webu_contact_messages";
    }


    public function init(): bool
    {

        $col = new DatabaseColumn('email', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(250);
        $this->addColumn($col);


        $col = new DatabaseColumn('subject', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(250);
        $this->addColumn($col);


        $col = new DatabaseColumn('message', DatabaseType::VARCHAR);
        $col->setCanBeNull(false)
            ->setLength(1000);
        $this->addColumn($col);


        return true;
    }

    public function afterCreation(DatabaseHelper $dbhelper)
    {
    }
}