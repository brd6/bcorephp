<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 00:43
 */

use Bcorephp\Application;
use Bcorephp\Service\TwigService;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(array(
    "base_url" => "/berdrigue"
));

// Service initialisation
$app->addService(new TwigService(), array(
    'twig.path' => __DIR__.'/../views'
));