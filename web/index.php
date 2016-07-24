<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 23/07/2016
 * Time: 16:04
 */

var_dump($_SERVER);

//echo "REDIRECT_URL: ".$_SERVER["REDIRECT_URL"]."<br>";
//echo "REQUEST_METHOD: ".$_SERVER["REQUEST_METHOD"]."<br>";
//echo "QUERY_STRING: ".$_SERVER["QUERY_STRING"]."<br>";
//echo "REQUEST_URI: ".$_SERVER["REQUEST_URI"]."<br>";

// ^(?P<controller>[a-z-]+)\/?(?P<action>[a-z-]+)?$

require "../core/Router.php";

$router = new Router();

$router->get("/", "HomeController::home");
$router->get("/index", function () use ($router){
    echo 'fd';
});

$router->get("/new/{id}", "NewController::new")
    ->with("id", '\d+');

$router->get("/ok/{id}", function ($id) {
    echo $id;
})
    ->with("id", '\d+');

var_dump($router->getRoutes());

var_dump($router->match());