<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 23/07/2016
 * Time: 16:57
 */

namespace Bcorephp;

use Bcorephp\Route;

class Router
{
    /**
     * List of routes
     * @var array of Route
     */
    private $routes = array();

    /**
     * Current route
     * @var Route
     */
    private $currentRoute = null;

    private $baseRouterDir;

    public function __construct($baseRouterDir = "")
    {
        $this->baseRouterDir = $baseRouterDir;
    }

    /**
     * Add a route by method
     * @param $pattern
     * @param $method
     * @param $controller
     * @return \Bcorephp\Route | bool
     */
    private function add($pattern, $method, $controller)
    {
        if (empty($pattern) || empty($method) || empty($controller))
        {
            echo "Add error: some parameters are empties.\n";
            return (false);
        }
        $baseRouterDirLen = strlen($this->baseRouterDir);
        $sep = "/";
        if ($baseRouterDirLen < 1 || $this->baseRouterDir[$baseRouterDirLen - 1] == "/" || $pattern[0] == "/")
        $sep = "";
        $pattern = ($this->baseRouterDir != "") ? $this->baseRouterDir.$sep.$pattern : $pattern;

        $this->routes[] = new Route($pattern, $method, $controller);
        return $this->routes[count($this->routes) - 1];
    }

    /***
     * Add a get route
     * @param $pattern
     * @param $controller
     * @return \Bcorephp\Route
     */
    public function get($pattern, $controller)
    {
        return $this->add($pattern, "GET", $controller);
    }

    /**
     * Add a post route
     * @param $pattern
     * @param $controller
     * @return \Bcorephp\Route
     */
    public function post($pattern, $controller)
    {
        return $this->add($pattern, "POST", $controller);
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function run($requestUrl = "", $requestMethod = "")
    {
        $this->match($requestUrl, $requestMethod);
        if ($this->currentRoute != null)
            $this->currentRoute->call();
    }

    private function match($requestUrl = "", $requestMethod = "")
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
        foreach ($this->routes as $route)
        {
            if ($route->getMethod() == $requestMethod)
            {
                if ($this->initRouteFetched($route, $requestUrl))
                    return true;
            }
        }
        return false;
    }

    private function initRouteFetched(Route $route, $requestUrl)
    {
        if (preg_match_all($route->getRegexPattern(), $requestUrl, $output_array)) {
            $this->setFetchRouteParam($route, $output_array);
            $route->setUrl($requestUrl);
            $this->currentRoute = $route;
            return true;
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

    /**
     * Get a route by his name
     * @param $name
     * @return bool|Route
     */
    public function getRouteByName($name)
    {
        foreach ($this->routes as $route)
        {
            if ($route->getName() == $name)
                return $route;
        }
        return false;
    }

    /**
     * Generate a url by route name and set his params
     * @param $routeName
     * @param array $param
     * @return bool|Route
     */
    public function generate($routeName, $param = array())
    {
        $route = $this->getRouteByName($routeName);
        var_dump($route);
        if (!$route)
            return (false);
        $route->generateUrl($param);
        return ($this->initRouteFetched($route, $route->getUrl())) ? $route : false;
    }

    /**
     * @return Route
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }
}