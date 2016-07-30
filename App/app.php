<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 00:43
 */

use Bcorephp\Application;
use Bcorephp\Service\TwigService;
use Bcorephp\Service\DatabaseService;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(array(
    "base_url" => "/",
    "debug" => true
));

// db
$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'charset' => 'utf8',
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => '2as_db',
    'user' => 'root',
    'password' => ''
);

// Error Page
$app->error(function ($e, $code) {
    echo "Erreur ".$code;
});

// Service initialisation

// Twig
$app->addService(new TwigService(), array(
    'twig.path' => __DIR__.'/../views'
));

// My Database Service
$app->addService(new DatabaseService());