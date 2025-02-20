<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Inclua seu arquivo bootstrap
require_once __DIR__ . "/bootstrap.php";

// Retorne o helper set com o EntityManager
return ConsoleRunner::createHelperSet($entityManager);
