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
        //var_dump($appd);
        //echo "home";
        echo $app['twig']->render('/Home/index.html.twig', array('a' => $a));
    }
}