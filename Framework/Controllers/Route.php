<?php

namespace Framework\Controllers;

final class Route{
    public string $path;
    public string $controller;
    public string $method;
    public string $httpMethod;

    public function __construct(string $path, string $controller, string $method, string $httpMethod) {
        $this->path = $path;
        $this->controller = $controller;
        $this->method = $method;
        $this->httpMethod = $httpMethod;
    }
}

