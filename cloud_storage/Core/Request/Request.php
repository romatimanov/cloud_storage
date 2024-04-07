<?php

namespace App\Core\Request;

interface RequestInterface
{
    public function getData();
    public function getRoute();
    public function getMethod();
}

class Request implements RequestInterface
{
    private $method;
    private $route;
    private $data;

    public function __construct($method, $route, $data)
    {
        $this->method = $method;
        $this->route = $route;
        $this->data = $data;
    }
    public function getData()
    {
        return $this->data;
    }
    public function getRoute()
    {
        return $this->route;
    }
    public function getMethod()
    {
        return $this->method;
    }
}
