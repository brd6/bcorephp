<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 28/07/2016
 * Time: 20:35
 */

namespace bcorephp;


class Utils
{
    /** Copy an array to another
     * @param array $config
     * @param $to
     * @return bool
     */
    public static function copy_array(array $config, $to)
    {
        if (!($to instanceof \ArrayAccess) && !(is_array($to)))
            return (false);
        foreach ($config as $key => $value)
        {
            $to[$key] = $value;
        }
        return (true);
    }

    /** Execute the list of function
     * @see call_user_func_array
     * @param array $list
     * @param array $param
     * @throws \Exception
     */
    public static function execute_action_list($list = array(), $param = array())
    {
        foreach ($list as $action)
        {
            if (is_callable($action))
                call_user_func_array($action, $param);
            else
                throw new \Exception($action." isn't a callable function.");
        }
    }

    public static function str_lreplace($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);

        if($pos !== false)
        {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

}