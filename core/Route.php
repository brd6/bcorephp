<?php

/**
 * Created by PhpStorm.
 * User: bberd
 * Date: 24/07/2016
 * Time: 00:23
 */
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

    public function __construct($pattern, $method, $controller)
    {
        $this->pattern = strlen($pattern) > 1 ? trim($pattern, "/") : $pattern;
        $tmp = explode("::", $controller);
        $this->controller = $tmp[0];
        $this->action = count($tmp) <= 1 || empty($tmp[1]) ? "index" : $tmp[1];
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

    /**
     * @param array $paramFilters
     */
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

    public function call()
    {
        $instance = new $this->controller;
        call_user_func_array(array($instance, $this->action), $this->params);
    }

    public function with($param, $regex)
    {
        $this->paramFilters[$param] = $regex;
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
}