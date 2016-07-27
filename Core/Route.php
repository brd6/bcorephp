<?php

/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 24/07/2016
 * Time: 00:23
 */

namespace Bcorephp;

class Route
{
    /**
     * Pattern of this route
     * @var string
     */
    private $pattern;

    /**
     * Params of this route
     * @var
     */
    private $params = array();

    /**
     * Filters of route params
     * @var array
     */
    private $paramFilters = array();

    /**
     * Method of this route
     * @var string
     */
    private $method;

    /**
     * Controller of this route
     * @var
     */
    private $controller;

    /**
     * Action of this route
     * @var
     */
    private $action;

    /**
     * Route name
     * @var
     */
    private $name;

    /**
     * Final route url
     * @var
     */
    private $url;

    private $app;


    /**
     * Liste of function to execute before calling the route's controller
     * @var array
     */
    private $beforeActionFunctions = array();

    /**
     * Liste of function to execute after calling the route's controller
     * @var array
     */
    private $afterActionFunctions = array();

    public function __construct($pattern, $method, $controller, Application $app)
    {
        $this->app = $app;

        $this->pattern = strlen($pattern) > 1 ? trim($pattern, "/") : $pattern;
        if (is_string($controller)) {
            $tmp = explode("::", $controller);
            $this->controller = $tmp[0];
            $this->action = count($tmp) <= 1 || empty($tmp[1]) ? "index" : $tmp[1];
        }
        else
            $this->controller = $controller;
        $this->method = $method;
        $this->setParamFilters();
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParamFilters()
    {
        return $this->paramFilters;
    }

    private function setParamFilters()
    {
        $matched = preg_match_all('#{(\w+)}#', $this->pattern, $output_array);
        if ($matched > 0)
        {
            foreach ($output_array[1] as $value)
            {
                $this->paramFilters[$value] = ".*";//"(?P<$value>.*)";
                $this->params[$value] = "";
            }
        }
    }

    public static function executeActionList($list = array(), $param = array())
    {
        foreach ($list as $action)
        {
            call_user_func_array($action, $param);
        }
    }

    /**
     * Execute the route controller
     */
    public function call()
    {
        // execute before action
        $this->executeActionList($this->beforeActionFunctions, array("app" => $this->app));

        $this->params["app"] = $this->app;
        var_dump($this->params);

        if (is_string($this->controller)) {
            $instance = new $this->controller;
            call_user_func_array(array($instance, $this->action), $this->params);
        }
        else
        {
            //var_dump($this->params);
            call_user_func_array($this->controller, $this->params);
        }

        // execute after action
        $this->executeActionList($this->afterActionFunctions);
    }

    /**
     * Add a regex condition to the route's variable
     * @param $param
     * @param $regex
     * @return $this
     */
    public function with($param, $regex)
    {
        $this->paramFilters[$param] = $regex;
        return $this;
    }

    /**
     * Alias of with's function
     * @see Router::with()
     * @param $param
     * @param $regex
     * @return $this
     */
    public function assert($param, $regex)
    {
        return $this->with($param, $regex);
    }

    /**
     * Bind a name to the route
     * TODO Conflit de nom à voir
     * @param $name
     * @return $this
     */
    public function bind($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * TODO Verifier que les fonctions sont bien callable (is_callable)
     * A add before action to executed before calling the route's controller
     * @param $callable
     * @return $this
     */
    public function before($callable)
    {
        $this->beforeActionFunctions[] = $callable;
        return $this;
    }


    /**
     * TODO Verifier que les fonctions sont bien callable (is_callable)
     * A add after action to executed before calling the route's controller
     * @param $callable
     * @return $this
     */
    public function after($callable)
    {
        $this->afterActionFunctions[] = $callable;
        return $this;
    }

    public function getRegexPattern()
    {
        $matched = preg_replace_callback('#({\w+})#', function ($matches) {
            $key = str_replace(array("{", "}"), "", $matches[0]);
            return "(?P<$key>".$this->paramFilters[$key].")";
        }, $this->pattern);
        return "#^".$matched."$#";
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * TODO Verification des parametres
     * TODO Verifier que tous les parametres de la route sont rensegnés
     * @param array $params
     */
    public function generateUrl($params = array())
    {
        $matched = preg_replace_callback('#({\w+})#', function ($matches) use ($params) {
            $key = str_replace(array("{", "}"), "", $matches[0]);
            return $params[$key];
        }, $this->pattern);
        $this->url = $matched;

    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}