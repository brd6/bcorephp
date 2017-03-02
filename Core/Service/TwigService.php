<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 00:51
 */

namespace bcorephp\Service;


use bcorephp\Application;
use bcorephp\Utils;

class TwigService implements IService
{
    /**
     * Initialise twig service
     * @param Application $app
     * @param array $config
     * @return bool
     */
    public function initialisation(Application $app, Array $config = array())
    {
        Utils::copy_array($config, $app);

        $app['twig.options'] = !isset($app['twig.options']) ? array() : $app['twig.options'];
        $app['twig.path'] = !isset($app['twig.path']) ? array() : $app['twig.path'];

        $loader = new \Twig_Loader_Filesystem($app['twig.path']);
        $app['twig.loader'] = !isset($app['twig.loader']) ? $loader : $app['twig.loader'];

        $app['twig'] = new \Twig_Environment($app['twig.loader'], $app['twig.options']);
        $app['twig']->addGlobal("app", $app);
        return (true);
    }

}