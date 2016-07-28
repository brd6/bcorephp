<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 20:35
 */

namespace Bcorephp;


class Utils
{
    /** Copy an array to another
     * @param array $config
     * @param $to
     */
    public static function copyArray(array $config, $to)
    {
        foreach ($config as $key => $value)
        {
            $to[$key] = $value;
        }
    }

    /** Execute the list of function
     * @see call_user_func_array
     * @param array $list
     * @param array $param
     */
    public static function executeActionList($list = array(), $param = array())
    {
        foreach ($list as $action)
        {
            if (is_callable($action))
                call_user_func_array($action, $param);
        }
    }

}