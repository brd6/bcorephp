<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 23/07/2016
 * Time: 16:57
 */

class Router
{
    /**
     * List of routes
     * @var array
     */
    protected $routes = array();

    /**
     * Current route params
     * @var array
     */
    protected $params = array();

    /**
     * add a route by method
     * @param $pattern
     * @param $method
     * @param $controller
     */
    private function add($pattern, $method, $controller)
    {
        $this->routes[$method][$pattern] = $controller;
    }

    /***
     * add a get route
     * @param $pattern
     * @param $controller
     */
    public function get($pattern, $controller)
    {
        $this->add($pattern, "GET", $controller);
    }

    public function match($url, $method)
    {
        foreach ($this->routes[$method] as $pattern => $route)
        {
            if ($url == $pattern)
                return true;
        }
        return false;
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}