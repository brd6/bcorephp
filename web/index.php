<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 23/07/2016
 * Time: 16:04
 */

use Bcore\Router;

require_once __DIR__.'/../vendor/autoload.php';

$router = new Router();

$router->get("/", 'MyApp\Home\HomeController::home');
$router->get("/index", function () use ($router){
    echo 'fd';
})->bind("home");

$router->get("/new/{id}/{title}", function ($id, $title) {
    echo "id=".$id."\n";
    echo "title=".$title."\n";
})
    ->with("id", '\d+')
    ->bind("new");

$router->get("/ok/{id}", function ($id) {
    echo $id;
})
    ->with("id", '\d+');

//$route = $router->generate("new", array(
//    "id" => "8"
//));

//$route->call();

$router->run();