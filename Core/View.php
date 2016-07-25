<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 26/07/2016
 * Time: 00:24
 */

namespace Bcorephp;


class View
{
    private static $viewDirectory = "../views/";

    public static function render($view)
    {
        $file = self::$viewDirectory.$view;
        if (is_readable($file))
        {
            require $file;
        }
        else
            echo "file not found : $file";
    }
}