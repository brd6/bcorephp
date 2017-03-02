<?php
/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 23/07/2016
 * Time: 16:57
 */

namespace bcorephp;

use bcorephp\Route;

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

    private $currentRequestUrl;

    private $currentRequestMethod;

    private $baseRouterDir;

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->baseRouterDir = $app["base_url"];
    }

    /**
     * Add a route by method
     * @param $pattern
     * @param $method
     * @param $controller
     * @return \bcorephp\Route | bool
     * @throws
     */
    private function add($pattern, $method, $controller)
    {
        if (empty($pattern) || empty($method) || empty($controller))
        {
            throw new \Exception("Add error: some parameters are empties.\n");
        }

        $baseRouterDirLen = strlen($this->baseRouterDir);
        $sep = "/";
        if ($baseRouterDirLen < 1 || $this->baseRouterDir[$baseRouterDirLen - 1] == "/" || $pattern[0] == "/")
            $sep = "";
        if ($pattern[0] == "/")
            $pattern = ltrim($pattern, "/");
        $pattern = ($this->baseRouterDir != "") ? $this->baseRouterDir.$sep.$pattern : $pattern;

        $this->routes[] = new Route($pattern, $method, $controller, $this->app);
        return $this->routes[count($this->routes) - 1];
    }

    /***
     * Add a get route
     * @param $pattern
     * @param $controller
     * @return \bcorephp\Route
     */
    public function get($pattern, $controller)
    {
        return $this->add($pattern, "GET", $controller);
    }

    /**
     * Add a post route
     * @param $pattern
     * @param $controller
     * @return \bcorephp\Route
     */
    public function post($pattern, $controller)
    {
        return $this->add($pattern, "POST", $controller);
    }

    /**
     * Add a put route
     * @param $pattern
     * @param $controller
     * @return \bcorephp\Route|bool
     */
    public function put($pattern, $controller)
    {
        return $this->add($pattern, "PUT", $controller);
    }

    /**
     * Add a delete route
     * @param $pattern
     * @param $controller
     * @return \bcorephp\Route|bool
     */
    public function delete($pattern, $controller)
    {
        return $this->add($pattern, "DELETE", $controller);
    }

    /**
     * Get the list of route
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function run($currentRequestUrl = "", $currentRequestMethod = "")
    {
        $this->currentRequestUrl = $currentRequestUrl;
        $this->currentRequestMethod = $currentRequestMethod;
        $this->match();
        if ($this->currentRoute != null)
            $this->currentRoute->call();
        else {
            if (!$this->app["debug"])
                $this->app->abort(404);
            $exceptMessage = "The request : '".$this->currentRequestMethod." ".$this->currentRequestUrl."' doesn't match any route.";
            throw new \Exception($exceptMessage);
        }
    }

    private function match()
    {
        $this->currentRequestMethod = empty($this->currentRequestMethod) ?
            $_SERVER["REQUEST_METHOD"] : $this->currentRequestMethod;
        $this->currentRequestUrl = empty($this->currentRequestUrl) && strlen($_SERVER["REQUEST_URI"]) > 1 ?
            $_SERVER["REQUEST_URI"] : $this->currentRequestUrl;

        if (($pos = strpos($this->currentRequestUrl, '?')) !== false) {
            $this->currentRequestUrl = substr($this->currentRequestUrl, 0, $pos);
        }
        return $this->fetchRoutes($this->currentRequestMethod, $this->currentRequestUrl);
    }

    private function fetchRoutes()
    {
        foreach ($this->routes as $route)
        {
            if ($route->getMethod() == $this->currentRequestMethod)
            {
                if ($this->initRouteFetched($route, $this->currentRequestUrl))
                    return true;
            }
        }
        return false;
    }

    private function initRouteFetched(Route $route)
    {
        if (preg_match_all($route->getRegexPattern(), $this->currentRequestUrl, $output_array)) {
            $this->setFetchRouteParam($route, $output_array);
            $route->setUrl($this->currentRequestUrl);
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