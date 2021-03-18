<?php

namespace webu\system\Core\Database;

use webu\system\Core\Base\Database\DatabaseColumn;
use webu\system\Core\Base\Database\DatabaseTable;
use webu\system\Core\Base\Database\Storage\DatabaseAttributes;
use webu\system\Core\Base\Database\Storage\DatabaseType;
use webu\system\Core\Base\Helper\DatabaseHelper;

class PagesURITable extends DatabaseTable
{

    public function __construct(bool $hasId = true, bool $hasCreatedAt = true, bool $hasUpdatedAt = true)
    {
        parent::__construct($hasId, false, false);

        $this->init();
    }

    public function init(): bool
    {
        //name
        $col = new DatabaseColumn("page_id", DatabaseType::INT);
        $col->setCanBeNull(false);
        $this->setOnDeleteCascade("page_id","webu_pages","id");
        $this->addColumn($col);

        //url
        $col = new DatabaseColumn("url", DatabaseType::VARCHAR);
        $col->setCanBeNull(false);
        $col->setLength(255);
        $this->addColumn($col);

        return true;
    }

    public function getTableName(): string
    {
        return "webu_pages_uri";
    }

    public function afterCreation(DatabaseHelper $dbhelper) {}
}