<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 00:50
 */

namespace Bcorephp\Service;

use Bcorephp\Application;

interface IService
{
    public function initialisation(Application $app, Array $config = array());

}