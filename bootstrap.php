<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$isDevMode = true;

// Create a simple "default" Doctrine ORM configuration for Annotations
//$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
// if you prefer yaml
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/doctrine/yaml"), $isDevMode);
// or if you prefer XML
$config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/doctrine/xml"), $isDevMode);
// database configuration parameters
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/doctrine/db.sqlite',
);

// uncomment to use MySql 
//$conn = array(
//    'driver' => 'pdo_mysql',
//    'host' => 'localhost',
//    'dbname' => 'your_database_name',
//    'user' => 'username',
//    'password' => 'password'
//);

// live demo app specific setup:
$dbEnv = getenv('HEROKU_DB');
if ($dbEnv == 'pgsql') {
    $conn = array(
        'driver' => 'pdo_pgsql',
        'host' => getenv('DB_HOST'),
        'dbname' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'port' => getenv('DB_PORT')
    );
}

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
