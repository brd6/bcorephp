<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 00:51
 */

namespace Bcorephp\Service;


use Bcorephp\Application;

class TwigService implements IService
{
    public function initialisation(Application $app, Array $config = array())
    {
        Application::copyArray($config, $app);

        $app['twig.options'] = !isset($app['twig.options']) ? array() : $app['twig.options'];
        $app['twig.path'] = !isset($app['twig.path']) ? array() : $app['twig.path'];
        $app['twig.templates'] = !isset($app['twig.templates']) ? array() : $app['twig.templates'];

        $loader = new \Twig_Loader_Filesystem($app['twig.path']);
        $app['twig.loader'] = !isset($app['twig.loader']) ? $loader : $app['twig.loader'];

        $app['twig'] = new \Twig_Environment($app['twig.loader'], $app['twig.options']);
    }

}