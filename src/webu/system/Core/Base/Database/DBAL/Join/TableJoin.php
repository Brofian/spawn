<?php declare(strict_types=1);

namespace webu\system\Core\Base\Database\DBAL\Join;

use webu\system\Core\Base\Database\DBAL\Filter\AbstractFilter;

class TableJoin extends AbstractJoin
{

    protected string $table;
    protected ?string $alias = null;
    protected AbstractFilter $onFilter;
    protected string $joinType;

    public function __construct(string $joinType, string $table, AbstractFilter $onFilter, string $alias = null)
    {
        $this->joinType = $joinType;
        $this->table = $table;
        $this->onFilter = $onFilter;
        $this->alias = $alias;
    }


    public function getJoin(): string
    {
        $join = "$this->joinType $this->table ";

        if($this->alias != null) {
            $join .= "AS $this->alias ";
        }

        $join .= 'ON '.$this->onFilter->getFilter(true).' ';

        return $join;
    }
}