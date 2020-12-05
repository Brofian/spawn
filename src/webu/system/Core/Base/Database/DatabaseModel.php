<?php

namespace webu\system\Core\Base\Database;

use webu\system\Core\Base\Database\Query\QueryBuilder;

abstract class DatabaseModel {

    protected $queryBuilder = null;

    public function __construct(DatabaseConnection $connection)
    {
        $this->queryBuilder = new QueryBuilder($connection);
    }


}