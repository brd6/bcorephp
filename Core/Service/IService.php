<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 00:50
 */

namespace bcorephp\Service;

use bcorephp\Application;

interface IService
{
    public function initialisation(Application $app, Array $config = array());

}