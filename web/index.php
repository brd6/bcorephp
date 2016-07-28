<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 23/07/2016
 * Time: 16:04
 */

require_once __DIR__.'/../App/app.php';

//$router = new Router("");
//
//// Init a get route with external function
//$router->get("/", 'MyApp\Home\HomeController::home');
//
//// Init a get route with a anonymous function
//$router->get("/index", function () use ($router){
//    View::render("Home/index.html.twig");
//});
//
//// Bind a name to a route
//$router->get("/index2", function () {
//    echo "index 2";
//})
//    ->bind("home2");
//
//// Custom route with variable
//$router->get("/new/{id}/{title}", function ($id, $title) {
//    echo "id=".$id."\n";
//    echo "title=".$title."\n";
//})
//    ->bind("new");
//
//// Custom route with variable and variable condition
//$router->get("/new/{id}", function ($id) {
//    echo "id=".$id."\n";
//})
//    ->with("id", '\d+')
//    ->bind("new2");
//
//$router->get("/ok/{id}", function ($id) {
//    echo $id;
//})
//    ->with("id", '\d+');
//
//$array = array(1,2);
//
//$index6 = $router->get("/index6", function () {
//    echo 'Index';
//})
//    ->before(function () use ($array) {
//        echo "before";
//    })
//    ->bind("index6")
//    ->after(function ()
//    {
//        echo "after";
//    });
//
//// Generate a route. It can be call inside a another route for example
////$route = $router->generate("new2", array(
////    "id" => "8"
////));
////$route->call();
//
//$router->run();


$app->get("/{a}", 'MyApp\Home\HomeController::home')
    ->with("a", ".*");

$app->run();