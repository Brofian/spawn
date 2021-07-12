<?php declare(strict_types=1);

namespace spawn\system\Core\database;

use spawn\system\Core\Base\Database\DatabaseColumn;
use spawn\system\Core\Base\Database\DatabaseTable;
use spawn\system\Core\Base\Database\Storage\DatabaseType;
use spawn\system\Core\Base\Helper\DatabaseHelper;

class SpawnMigrationTable extends DatabaseTable  {

    public function getTableName(): string
    {
        return "spawn_migrations";
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