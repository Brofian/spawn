<?php

namespace webu\system\Core\Database;

use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\Storage\DatabaseType;


class debugTable extends DatabaseTable
{

    public function __construct(bool $hasId = true, bool $hasCreatedAt = true, bool $hasUpdatedAt = true)
    {
        parent::__construct($hasId, $hasCreatedAt, $hasUpdatedAt);
        $this->init();
    }

    public function getTableName(): string
    {
        return 'debug_test';
    }

    public function init(): bool
    {
        $valueColumn = new DatabaseColumn("value", DatabaseType::INT);
        $valueColumn->setDefault(42);
        $this->addColumn($valueColumn);

        return true;
    }

}