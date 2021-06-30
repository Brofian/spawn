<?php declare(strict_types=1);

namespace webu\system\Core\Base\Database\DBAL\Filter;

class LikeFilter extends AbstractFilter {

    public function __construct(string $column, $value)
    {
        $this->column = $column;
        $this->value = $value;
    }

    public function getFilter(bool $useRawValues = false) : string
    {
        $val = ($useRawValues) ? $this->value : "?";
        return "$this->column LIKE $val";
    }

    public function getValues(): array
    {
        return [$this->value];
    }
}