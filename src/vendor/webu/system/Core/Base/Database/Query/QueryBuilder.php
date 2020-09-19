<?php


namespace webu\system\Core\Base\Database\Query;


use webu\system\Core\Base\Database\DatabaseConnection;
use webu\system\Core\Base\Database\Query\Types\QuerySelect;

class QueryBuilder
{

    /** @var DatabaseConnection $connection */
    public $connection;

    /**
     * @param string|array
     * @return QuerySelect
     */
    public function select($columns): QuerySelect
    {
        return new QuerySelect($columns);
    }


}