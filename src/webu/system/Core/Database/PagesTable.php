<?php

namespace webu\system\Core\Database;

use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class PagesTable extends DatabaseTable
{

    public function __construct(bool $hasId = true, bool $hasCreatedAt = true, bool $hasUpdatedAt = true)
    {
        parent::__construct($hasId, $hasCreatedAt, $hasUpdatedAt);

        $this->init();
    }

    public function init(): bool
    {
        //name
        $col = new DatabaseColumn("name", DatabaseType::VARCHAR);
        $col->setCanBeNull(false);
        $col->setLength(255);
        $this->addColumn($col);

        //active
        $col = new DatabaseColumn("active", DatabaseType::BOOLEAN);
        $col->setDefault(false);
        $this->addColumn($col);


        return true;
    }

    public function getTableName(): string
    {
        return "webu_pages";
    }

    public function afterCreation(DatabaseHelper $dbhelper) {}
}