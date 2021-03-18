<?php

namespace webu\system\core\database;

use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class WebuMigrationTable extends DatabaseTable  {

    public function getTableName(): string
    {
        return "webu_migrations";
    }

    public function init(): bool
    {
        $col = new DatabaseColumn("class", DatabaseType::VARCHAR);
        $col->setLength(DatabaseColumn::VARCHAR_SMALL);
        $this->addColumn($col);

        $col = new DatabaseColumn("timestamp", DatabaseType::BIGINT);
        $this->addColumn($col);

        return true;
    }

    public function afterCreation(DatabaseHelper $dbhelper)
    {
    }
}