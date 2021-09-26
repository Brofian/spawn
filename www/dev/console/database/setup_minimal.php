<?php

use bin\spawn\IO;
use spawn\system\Core\Helper\FrameworkHelper\DatabaseStructureHelper;


IO::printLine("> Creating basic database...", IO::YELLOW_TEXT);

$dbStructureHelper = new DatabaseStructureHelper();
$dbStructureHelper->createBasicDatabaseStructure();

IO::printLine('> Created basic database structure!', IO::GREEN_TEXT);
