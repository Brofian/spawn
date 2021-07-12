<?php declare(strict_types=1);

namespace spawn\system\Core\Base\Database\DBAL\Filter;

use spawn\system\Core\Base\Database\DBAL\Criteria;

class ExistsFilter extends AbstractFilter {

    /** @var Criteria */
    protected Criteria $criteria;

    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    public function getFilter(bool $useRawValues = false) : string {
        return "EXISTS (" . $this->criteria->getSQL() . ") ";
    }

    public function getValues(): array
    {
        return $this->criteria->getValues();
    }

}