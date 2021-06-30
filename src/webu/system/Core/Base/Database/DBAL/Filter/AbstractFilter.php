<?php declare(strict_types=1);

namespace webu\system\Core\Base\Database\DBAL\Filter;

abstract class AbstractFilter {

    protected string $column;
    protected string $value;

    abstract public function getFilter(bool $useRawValues = false) : string;

    abstract public function getValues() : array;


}