<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 27/07/2016
 * Time: 20:15
 */

namespace bcorephp;

use bcorephp\Service\IService;


class Application extends ArrayAccessContainer
{
    private $beforeActionFunctions = array();
    private $afterActionFunctions = array();
    private $errorsCallable = array();

    public function __construct($config = array())
    {
        parent::__construct();

        $this["debug"] = isset($this["debug"]) ? $this["debug"] : false;
        $this['charset'] = isset($this['charset']) ? $this['charset'] : 'UTF-8';
        $this['base_url'] = isset($this['base_url']) ? $this['base_url'] : "";

        Utils::copy_array($config, $this);

        $this['router'] = new Router($this);

        if ($this["debug"]) {
            error_reporting(E_ALL);
            set_error_handler('bcorephp\ErrorExceptionHandler::errorHandler');
            set_exception_handler('bcorephp\ErrorExceptionHandler::exceptionHandler');
        }
        else
        {
            error_reporting(0);
            set_error_handler(null);
            set_exception_handler(null);
        }
    }

    /** Add a new service to the app
     * @param $config
     * @param IService $service
     */
    public function addService(IService $service, $config = array())
    {
        $service->initialisation($this, $config);
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
        http_response_code($statusCode);
        if (count($headers) > 0)
        {
            foreach ($headers as $key => $value)
            {
                header($key.' '.$value, false);
            }
        }
        if (!$this["debug"])
        {
            Utils::execute_action_list($this->errorsCallable, array(
                "exception" => null,
                "code" => $statusCode
            ));
        }
        else
            exit($message);
    }

    /**
     * Add an error callable
     * @param $callable
     * @return $this
     */
    public function error($callable)
    {
        $this->errorsCallable[] = $callable;
        return $this;
    }

    /** Redirect the user to another url
     * @param $url
     * @param int $status
     * @param int $refresh
     */
    public function redirect($url, $status = 302, $refresh = 0)
    {
        if ($refresh > 0)
            header("refresh:$refresh;url=$url", true, $status);
        else
            header("Location: $url", true, $status);
    }

    /**
     * Return a json response
     * @param array $data
     * @param int $status
     * @param array $headers
     */
    public function json($data = array(), $status = 200, array $headers = array())
    {
        // TODO
    }

    /**
     * Run the app
     */
    public function run()
    {
        if (!($this["router"] instanceof Router))
            throw new \Exception("Router class doesn't exist in Application.");

        Utils::execute_action_list($this->beforeActionFunctions, array("app" => $this));
        $this["router"]->run();
        Utils::execute_action_list($this->afterActionFunctions, array("app" => $this));
    }
}