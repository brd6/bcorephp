<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 23/07/2016
 * Time: 16:57
 */

require "Route.php";

class Router
{
    /**
     * List of routes
     * @var array of Route
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
     * @return Route
     */
    private function add($pattern, $method, $controller)
    {
        $this->routes[] = new Route($pattern, $method, $controller);
        return $this->routes[count($this->routes) - 1];
    }

    /***
     * add a get route
     * @param $pattern
     * @param $controller
     * @return Route
     */
    public function get($pattern, $controller)
    {
        return $this->add($pattern, "GET", $controller);
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function match($requestUrl = "", $requestMethod = "")
    {
        $requestMethod = empty($requestMethod) ? $_SERVER["REQUEST_METHOD"] : $requestMethod;
        $requestUrl = empty($requestUrl) ? $_SERVER["REQUEST_URI"] : $requestUrl;

        if (($pos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $pos);
        }
        if (($pos = strpos($requestUrl, "/")) !== false && $pos == 0 && strlen($requestUrl) > 1)
        {
            $requestUrl = substr($requestUrl, 1);
        }

        return $this->fetchRoutes($requestMethod, $requestUrl);
    }

    private function fetchRoutes($requestMethod, $requestUrl)
    {
        var_dump($requestUrl);
        foreach ($this->routes as $route)
        {
            if ($route->getMethod() == $requestMethod)
            {
                if (preg_match_all($route->getRegexPattern(), $requestUrl, $output_array)) {
                    $this->setFetchRouteParam($route, $output_array);
                    return ($route);
                }
            }
        }
        return false;
    }

    private function setFetchRouteParam(Route $route, array $regexArray)
    {
        $params = array();
        foreach ($route->getParams() as $key => $value)
        {
            $params[$key] = $regexArray[$key][0];
        }

        $route->setParams($params);
    }
}