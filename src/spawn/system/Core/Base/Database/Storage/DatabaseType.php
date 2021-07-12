<?php

namespace spawn\system\Core\Base\Database\Storage;

class DatabaseType
{

    const TINYINT = 'TINYINT';
    const SMALLINT = 'SMALLINT';
    const INT = 'INT';
    const MEDIUMINT = 'MEDIUMINT';
    const BIGINT = 'BIGINT';

    const DECIMAL = 'DECIMAL';
    const FLOAT = 'FLOAT';
    const DOUBLE = 'DOUBLE';
    const REAL = 'REAL';

    const CHAR = 'CHAR';
    const VARCHAR = 'VARCHAR';
    const TINYTEXT = 'TINYTEXT';
    const MEDIUMTEXT = 'MEDIUMTEXT';
    const TEXT = 'TEXT';
    const LONGTEXT = 'LONGTEXT';

    const BINARY = 'BINARY';
    const VARBINARY = 'VARBINARY';

    const TINYBLOB = 'TINYBLOB';
    const MEDIUMBLOB = 'MEDIUMBLOB';
    const BLOB = 'BLOB';
    const LONGBLOB = 'LONGBLOB';

    const ENUM = 'ENUM';
    const SET = 'SET';

    const JSON = 'JSON';

    const BIT = 'BIT';
    const BOOLEAN = 'BOOLEAN';
    const SERIAL = 'SERIAL';

    const DATE = 'DATE';
    const DATETIME = 'DATETIME';
    const TIMESTAMP = 'TIMESTAMP';
    const TIME = 'TIME';
    const YEAR = 'YEAR';

}