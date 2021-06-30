<?php declare(strict_types=1);

namespace webu\system\Core\Base\Database\DBAL\Join;

use webu\system\Core\Base\Database\DBAL\Criteria;
use webu\system\Core\Base\Database\DBAL\Filter\AbstractFilter;

class QueryJoin extends AbstractJoin
{

    protected Criteria $criteria;
    protected ?string $alias = null;
    protected AbstractFilter $onFilter;
    protected string $joinType;

    public function __construct(string $joinType, Criteria $criteria, AbstractFilter $onFilter, string $alias = null)
    {
        $this->joinType = $joinType;
        $this->criteria = $criteria;
        $this->onFilter = $onFilter;
        $this->alias = $alias;
    }


    public function getJoin(): string
    {
        $join = "$this->joinType (".$this->criteria->getSQL().")";

        if($this->alias != null) {
            $join .= "AS $this->alias ";
        }

        $join .= 'ON '.$this->onFilter->getFilter(true).' ';

        return $join;
    }
}