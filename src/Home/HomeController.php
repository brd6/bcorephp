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
    public function home($a, Application $appd)
    {
        //var_dump($appd);
        echo "home";
    }
}