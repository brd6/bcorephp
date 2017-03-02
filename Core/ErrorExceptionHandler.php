<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 30/07/2016
 * Time: 12:19
 */

namespace bcorephp;


class ErrorExceptionHandler
{
    /**
     * Convert error to exception
     * @param $level
     * @param $message
     * @param $file
     * @param $line
     * @throws \ErrorException
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0)
            throw new \ErrorException($message, 0, $level, $file, $line);
    }

    public static function exceptionHandler(\Exception $exception)
    {
        echo "<h1>Fatal error (".$exception->getCode().")</h1>";
        echo "<p><b>Uncaught exception</b>: ".get_class($exception)."</p>";
        echo "<p><b>Message</b>:<br>".$exception->getMessage()."</p><br>";
        echo "<p><b>Stack trace</b>: <pre>".$exception->getTraceAsString()."</pre>";
        echo "<p><b>Thrown in </b>'".$exception->getFile()."' on line ".$exception->getLine()."</p>";
    }
}