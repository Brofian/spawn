<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\DBAL\Filter;

class SmallerEqualsFilter extends AbstractFilter {

    public function __construct(string $column, $value)
    {
        $this->column = $column;
        $this->value = $value;
    }

    public function getFilter(bool $useRawValues = false) : string
    {
        $val = ($useRawValues) ? $this->value : "?";
        return "$this->column <= $val";
    }

    public function getValues(): array
    {
        return [$this->value];
    }
}