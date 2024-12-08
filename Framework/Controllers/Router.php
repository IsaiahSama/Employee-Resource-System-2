<?php

namespace Framework\Controllers;

final class Router {
    
    // Each stores an array of `path => Framework\Controllers\Route` entries.
    public static $get_routes = [];
    public static $post_routes = [];
    public static $put_routes = [];
    public static $delete_routes = [];

    public static function routeMerge(array $method_routes, $routes){
        $merged_routes = $method_routes;

        foreach ($routes as $route) {
            $merged_routes[$route->path] = $route;
        }

        return $merged_routes;
    }

    public static function addGetRoutes(array $routes) {
        self::$get_routes = self::routeMerge(self::$get_routes, $routes);
    }

    public static function addPostRoutes(array $routes) {
        self::$post_routes = self::routeMerge(self::$post_routes, $routes);
    }

    public static function addPutRoutes(array $routes) {
        self::$put_routes = self::routeMerge(self::$put_routes, $routes);
    }

    public static function addDeleteRoutes(array $routes) {
        self::$delete_routes = self::routeMerge(self::$delete_routes, $routes);
    }

    public static function route($routes, $path) {
        if (isset($routes[$path])) {
            $route = $routes[$path];
            $controller = new $route->controller();
            $controller->{$route->method}();
        }
        else {
            header("HTTP/1.0 404 Not Found");
            header("Location: /404");
        }
    }

    public static function get(string $path){
        self::route(self::$get_routes, $path);
    }

    public static function post(string $path){
        self::route(self::$post_routes, $path);
    }

    public static function put(string $path){
        self::route(self::$put_routes, $path);
    }

    public static function delete(string $path){
        self::route(self::$delete_routes, $path);
    }
}


