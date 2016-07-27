<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 27/07/2016
 * Time: 20:15
 */

namespace Bcorephp;

use Bcorephp\Router;
use Bcorephp\Service\IService;


class Application extends ArrayAccessContainer
{
    private $beforeActionFunctions = array();
    private $afterActionFunctions = array();

    public function __construct($config = array())
    {
        parent::__construct();

        $this["debug"] = false;
        $this['charset'] = 'UTF-8';
        $this['base_url'] = "";

        $this->copyArray($config, $this);

        $this['router'] = new Router($this);
        $this['twig'] = "";
    }

    /** Add a new service to the app
     * @param $config
     * @param IService $service
     */
    public function addService(IService $service, $config = array())
    {
        $service->initialisation($this, $config);
    }

    public static function copyArray(array $config, $to)
    {
        foreach ($config as $key => $value)
        {
            $to[$key] = $value;
        }
    }

    /**
     * Set a GET route to a controller
     * @param $pattern
     * @param $controller
     * @return Route
     */
    public function get($pattern, $controller)
    {
        return $this['router']->get($pattern, $controller);
    }

    /**
     * Set a POST route to a controller
     * @param $pattern
     * @param $controller
     * @return Route
     */
    public function post($pattern, $controller)
    {
        return $this['router']->post($pattern, $controller);
    }

    /**
     * Set a PUT route to a controller
     * @param $pattern
     * @param $controller
     * @return Route
     */
    public function put($pattern, $controller)
    {
        return $this['router']->put($pattern, $controller);
    }

    /**
     * Set a DELETE route to a controller
     * @param $pattern
     * @param $controller
     * @return Route
     */
    public function delete($pattern, $controller)
    {
        return $this['router']->delete($pattern, $controller);
    }

    /**
     * Add a before action to executed before calling any route's controller
     * @param $callable
     * @return Application
     */
    public function before($callable)
    {
        $this->beforeActionFunctions[] = $callable;
        return $this;
    }

    /**
     * Add an after action to executed before calling any route's controller
     * @param $callable
     * @return Application
     */
    public function after($callable)
    {
        $this->afterActionFunctions[] = $callable;
        return $this;
    }


    /**
     * Abort the current request
     * @param $statusCode
     * @param string $message
     * @param array $headers
     */
    public function abort($statusCode, $message = '', array $headers = array())
    {
        // TODO
    }

    /** Redirect the user to another url
     * @param $url
     * @param int $status
     */
    public function redirect($url, $status = 302)
    {
        // TODO
        /*
         *         $this->setContent(
            sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="1;url=%1$s" />

        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8')));

        $this->headers->set('Location', $url);
         */
    }

    /**
     * Escape a text html
     * @param $text
     * @param int $flags
     * @param null $charset
     * @param bool $doubleEncode
     * @return string
     */
    public function escape($text, $flags = ENT_COMPAT, $charset = null, $doubleEncode = true)
    {
        return htmlspecialchars($text, $flags, $charset ?: $this['charset'], $doubleEncode);
    }

    /**
     * Return a json response
     * @param array $data
     * @param int $status
     * @param array $headers
     */
    public function json($data = array(), $status = 200, array $headers = array())
    {

    }

    /**
     * Run the app
     */
    public function run()
    {
        Route::executeActionList($this->beforeActionFunctions);
        $this["router"]->run();
        Route::executeActionList($this->afterActionFunctions);
    }
}