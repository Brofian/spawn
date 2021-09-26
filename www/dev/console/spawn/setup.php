<?php declare(strict_types=1);

use \bin\spawn\IO;
use PDO;
use spawn\system\Core\Helper\FrameworkHelper\DatabaseStructureHelper;

include(__DIR__ . "/callable/print-spawn.php");

$answer = IO::readLine(IO::LIGHT_RED_TEXT.'This action depends on having an empty database. Do you want to continue? (yes/no/y/n)'.IO::DEFAULT_TEXT, function ($answer) {
    return in_array($answer, ['yes','no','y','n']);
});

if($answer !== 'yes' && $answer !== 'y') {
    IO::printLine('Aborting...', IO::RED_TEXT);
    return;
}


$connection = \spawn\system\Core\Base\Database\DatabaseConnection::getConnection();

try {
	if(!$connection->isConnected()) {
		$connection->connect();
	}
}catch (\Doctrine\DBAL\Exception $e) {
	IO::printLine('Cant connect to Database! Aborting...', IO::RED_TEXT);
	return;
}


$dbStructureHelper = new DatabaseStructureHelper();
$dbStructureHelper->createBasicDatabaseStructure();
