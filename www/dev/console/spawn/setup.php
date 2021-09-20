<?php declare(strict_types=1);

use \bin\spawn\IO;
use PDO;

include(__DIR__ . "/callable/print-spawn.php");

$answer = IO::readLine('This action will delete your database and create some base table structure! Do you want to continue? (yes/no/y/n)', function ($answer) {
	return in_array($answer, ['yes','no','y','n']);
});

if($answer !== 'yes' && $answer !== 'y') {
	IO::printLine('Aborting...', IO::RED_TEXT);
	return;
}


$dsn = 'mysql:dbname='.DB_DATABASE.';host='.DB_HOST;
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
var_dump($pdo);
die();

$connection = \spawn\system\Core\Base\Database\DatabaseConnection::getConnection();

try {
	if(!$connection->isConnected()) {
		$connection->connect();
	}
}catch (\Doctrine\DBAL\Exception $e) {
	var_dump($connection->getParams());
	throw $e;
	IO::printLine('Cant connect to Database! Aborting...', IO::RED_TEXT);
	return;
}

die("the end");

# Delete and create database
$connection->executeQuery('DROP DATABASE IF EXISTS '.DB_DATABASE);
$connection->executeQuery('CREATE DATABASE IF NOT EXISTS '.DB_DATABASE);