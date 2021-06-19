<?php

namespace webu\system\Core\Base\Database\DBAL\Filter;

class AndFilter extends AbstractFilter {

    /** @var AbstractFilter[]  */
    protected array $filters = array();

    public function __construct(AbstractFilter ...$filters)
    {
        foreach($filters as $filter) {
            $this->filters[] = $filter;
        }
    }

    public function getFilter(bool $useRawValues = false) : string {
        $filterSQL = '(';

        $isFirst = true;
        foreach($this->filters as $filter) {
            if($isFirst) $isFirst = false;
            else         $filterSQL .= ' AND ';

            $filterSQL .= $filter->getFilter($useRawValues);
        }

        $filterSQL .= ')';


        return $filterSQL;
    }

    public function getValues(): array
    {
        $values = array();
        foreach($this->filters as $filter) {
            $values = array_merge($values, $filter->getValues());
        }
        return $values;
    }


}