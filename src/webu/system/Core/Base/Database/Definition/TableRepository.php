<?php

namespace webu\system\Core\Base\Database\Definition;

use webu\system\Core\Base\Database\DBAL\Criteria;

abstract class TableRepository {

    abstract public function getEntityClass() : string;

    public function search(Criteria $criteria = null) : EntityCollection {
        /** @var EntityCollection $entityCollection */
        $entityCollection = new EntityCollection();


        return $entityCollection;
    }

}