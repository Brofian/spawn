<?php declare(strict_types=1);


use bin\spawn\IO;
use spawnApp\Database\AdministratorTable\AdministratorEntity;
use spawnApp\Database\AdministratorTable\AdministratorRepository;
use spawn\system\Core\Services\ServiceContainerProvider;

$username = IO::readLine("Gib einen Benutzernamen an: ");
$password = IO::readLine("Gib einen Passwort an: ");
$email = IO::readLine("Gib eine Email Adresse an: ");


//javascript kompilieren
IO::printLine("> creating admin user", IO::YELLOW_TEXT);


$adminEntity = new AdministratorEntity(
    $username,
    password_hash($password, PASSWORD_DEFAULT),
    $email
);

/** @var AdministratorRepository $administrationRepository */
$administrationRepository = ServiceContainerProvider::getServiceContainer()->getServiceInstance('system.repository.administrator');

try {
    $administrationRepository->upsert($adminEntity);
    IO::printLine("> - successfully created admin user ", IO::GREEN_TEXT);
}
catch (\Exception $e) {
    IO::printLine("> - an error occured when creating admin user! " . $e->getMessage(), IO::RED_TEXT);
    IO::printObject($e);
}




