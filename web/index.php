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

require "../core/Router.php";

$router = new Router();

$router->get("/", "HomeController::home");
$router->get("/index", "HomeController::home");

$router->get("/new/{id}/", "HomeController::home");


var_dump($router->getRoutes());

var_dump($router->match($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"]));