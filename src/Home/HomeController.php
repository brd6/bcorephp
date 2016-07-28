<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 24/07/2016
 * Time: 14:53
 */

namespace MyApp\Home;


use Bcorephp\Application;

class HomeController
{
    public function home($a, Application $app)
    {
//        $app->abort(404, "Erreur 404");
        //$app->redirect("http://google.fr");
        //var_dump($appd);
        //echo "home";
        echo $app['twig']->render('/Home/index.html.twig', array('a' => $a));
    }
}