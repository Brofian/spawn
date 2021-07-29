<?php declare(strict_types=1);

namespace spawn\system\Core\Helper\FrameworkHelper;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use spawn\system\Core\Base\Database\DatabaseConnection;
use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractTable;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Core\Services\ServiceTags;

class DatabaseStructureHelper {

    public static function createDatabaseStructure()
    {
        $serviceContainer = ServiceContainerProvider::getServiceContainer();

        $dbTableServices = $serviceContainer->getServicesByTag(ServiceTags::DATABASE_TABLE);

        foreach($dbTableServices as $tableService) {
            /** @var AbstractTable $table */
            $table = $tableService->getInstance();

            $table->upsertTable();
        }

        dd("finished");

    }


}