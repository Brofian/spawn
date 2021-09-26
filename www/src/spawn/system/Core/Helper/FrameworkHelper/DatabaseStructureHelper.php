<?php declare(strict_types=1);

namespace spawn\system\Core\Helper\FrameworkHelper;


use spawn\system\Core\Base\Database\Definition\TableDefinition\AbstractTable;
use spawn\system\Core\Services\ServiceContainerProvider;
use spawn\system\Core\Services\ServiceTags;
use spawnApp\Database\MigrationTable\MigrationTable;
use spawnApp\Database\ModuleTable\ModuleTable;

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

    }

    public static function createBasicDatabaseStructure() {

        //create migration table
        (new MigrationTable())->upsertTable();

        //create module table
        (new ModuleTable())->upsertTable();

    }


}