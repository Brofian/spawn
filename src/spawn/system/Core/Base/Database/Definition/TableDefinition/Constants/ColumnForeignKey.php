<?php

namespace spawn\system\Core\Base\Database\Definition\TableDefinition\Constants;


class ColumnForeignKey {

    public const NONE = null;

    public static function NEW_FOREIGN_KEY(string $remoteTable, string $remoteColumn, bool $onUpdateCascade = true): array {
        return [
            'table' => $remoteTable,
            'column' => $remoteColumn,
            'options' => [
                'onUpdate' => $onUpdateCascade ? 'CASCADE' : 'NULL'
            ]
        ];
    }

}