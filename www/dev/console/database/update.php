<?php

use spawn\system\Core\Helper\FrameworkHelper\DatabaseStructureHelper;
use bin\spawn\IO;

IO::printLine("> Updating database...", IO::YELLOW_TEXT);

$dbStructureHelper = new DatabaseStructureHelper();
$dbStructureHelper->createDatabaseStructure();

IO::printLine('> Updated database successfully!', IO::GREEN_TEXT);

