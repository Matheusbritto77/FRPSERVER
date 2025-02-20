<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

require_once "vendor/autoload.php";

// Carregar configuração do banco
$dbParams = require __DIR__ . "/config/database.php";

// Configuração do Doctrine
$paths = [__DIR__ . "/app/Entities"];
$isDevMode = true;

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection($dbParams, $config);

// Criação do EntityManager
$entityManager = EntityManager::create($connection, $config);
