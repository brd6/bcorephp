<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 29/07/2016
 * Time: 00:31
 */

namespace Bcorephp\Service;


use Bcorephp\Application;
use Bcorephp\Database;

class DatabaseService implements IService
{
    public function initialisation(Application $app, Array $config = array())
    {
        if (count($app["db.options"]) < 1 && count($config) < 1)
            return (false);
        $app["db"] = Database::getInstance(count($app["db.options"]) > 0 ? $app["db.options"] : $config);
        return true;
    }
}