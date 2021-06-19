<?php

namespace webu\system\Core\Base\Database\DBAL\Filter;


use webu\system\Core\Base\Database\DBAL\Criteria;

class InFilter extends AbstractFilter {

    protected array $values;

    public function __construct(...$values)
    {
        if($values[0] instanceof Criteria) {
            $this->values = [$values[0]];
        }
        else {
            $this->values = $values;
        }
    }

    public function getFilter(bool $useRawValues = false) : string {
        if($this->values[0] instanceof Criteria) {
            return "IN (" . $this->values[0]->getSQL() . ") ";
        }
        else {
            if($useRawValues) {
                $valuesString = implode(",", $this->values);
            }
            else {
                $valuesString = str_repeat("?,", count($this->values));
            }
            return "IN (" . trim($valuesString, ",") . ") ";
        }
    }



    public function getValues(): array
    {
        if($this->values[0] instanceof Criteria) {
            $values = $this->values[0]->getValues();
        }
        else {
            $values = $this->values;
        }

        return $values;
    }
}