<?php

namespace webu\system\Core\Base\Database;

use webu\system\Core\Base\Database\Query\QueryBuilder;

abstract class DatabaseModel {

    /** @var QueryBuilder|null  */
    protected $queryBuilder = null;

    /**
     * DatabaseModel constructor.
     * @param DatabaseConnection $connection
     */
    public function __construct(DatabaseConnection $connection)
    {
        $this->queryBuilder = new QueryBuilder($connection);
    }


}