<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
define("APP_ROUTE_DIR", '');
define ("APP_ROOT_DIR", __DIR__ . "/");

require_once "Framework/autoloader.php";

// Want to include our singleton here

require_once "Framework/Models/DatabaseSingleton.php";

$separators = ['/', '\\'];

$request_uri = explode("?", $_SERVER["REQUEST_URI"], 2);
$path = str_replace(APP_ROUTE_DIR, '', $request_uri[0]);
$path = trim(str_replace($separators, DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
$path = DIRECTORY_SEPARATOR . $path;

// Setting up Autoloader

$loader = new Framework\Psr4AutoloaderClass();
$loader->register();

$loader->addNamespace('App', APP_ROOT_DIR . 'App');
$loader->addNamespace('App\\Controllers', APP_ROOT_DIR . 'App/Controllers');
$loader->addNamespace('App\\Models', APP_ROOT_DIR . 'App/Models');
$loader->addNamespace('App\\Views', APP_ROOT_DIR . 'App/Views');

$loader->addNamespace('Framework', APP_ROOT_DIR . 'Framework');
$loader->addNamespace('Framework\\Controllers', APP_ROOT_DIR . 'Framework/Controllers');
$loader->addNamespace('Framework\\Models', APP_ROOT_DIR . 'Framework/Models');
$loader->addNamespace('Framework\\Views', APP_ROOT_DIR . 'Framework/Views');

// Start the session!

Framework\Controllers\SessionManager::startOrIgnore();

// Define Routes here!
$router = new Framework\Controllers\Router();

$router->addGetRoutes([
    new Framework\Controllers\Route("/401", 'App\Controllers\ErrorController', 'error401', 'GET'),
    new Framework\Controllers\Route("/404", 'App\Controllers\ErrorController', 'error404', 'GET'),
    new Framework\Controllers\Route("/500", 'App\Controllers\ErrorController', 'error500', 'GET'),

    new Framework\Controllers\Route("/", 'App\Controllers\IndexController', 'index', 'GET'),
    new Framework\Controllers\Route("/register", 'App\Controllers\RegisterController', 'index', 'GET'),
    new Framework\Controllers\Route("/login", 'App\Controllers\LoginController', 'index', 'GET'),
    new Framework\Controllers\Route("/logout", 'App\Controllers\LogoutController', 'logout', 'GET'),
    new Framework\Controllers\Route("/dashboard", 'App\Controllers\DashboardController', 'index', 'GET'),

    new Framework\Controllers\Route("/dashboard/admin", 'App\Controllers\DashboardAdminController', 'index', 'GET'),
    new Framework\Controllers\Route("/dashboard/manager", 'App\Controllers\DashboardManagerController', 'index', 'GET'),
    new Framework\Controllers\Route("/dashboard/employee", 'App\Controllers\DashboardEmployeeController', 'index', 'GET'),
    new Framework\Controllers\Route("/task/create", 'App\Controllers\TaskController', 'index', 'GET'),
    new Framework\Controllers\Route("/task/edit", 'App\Controllers\TaskController', 'edit', 'GET'),
    new Framework\Controllers\Route("/task/filter", 'App\Controllers\TaskController', 'filterTasks', 'GET'),
    new Framework\Controllers\Route("/user/edit", 'App\Controllers\DashboardAdminController', 'editUser', 'GET'),
    new Framework\Controllers\Route("/user/delete", 'App\Controllers\DashboardAdminController', 'deleteUser', 'GET'),
]);

// Can put API routes here!!
$router->addGetRoutes([
    new FrameWork\Controllers\Route("/api/v1/login", "App\Controllers\APIController", "getLoginInformation", "GET"),
]);

// Post Routes
$router->addPostRoutes([
    new Framework\Controllers\Route("/register", 'App\Controllers\RegisterController', 'handleSubmission', 'POST'),
    new Framework\Controllers\Route("/login", 'App\Controllers\LoginController', "handleSubmission", 'POST'),
    new Framework\Controllers\Route("/user/create", 'App\Controllers\RegisterController', 'adminAddNewUser', 'POST'),
    new Framework\Controllers\Route("/task/create", 'App\Controllers\TaskController', 'createTask', 'POST'),
    new Framework\Controllers\Route("/user/edit", 'App\Controllers\DashboardAdminController', 'adminUpdateUser', 'POST'),
    new Framework\Controllers\Route("/task/edit", 'App\Controllers\TaskController', 'updateTask', 'POST'),
]);

// More API Routes here!

$router->addPostRoutes([
    new Framework\Controllers\Route("/api/v1/login", "App\Controllers\APIController", "authenticate", "POST"),
]);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $router->get($path);
        break;
    case "POST":
        $router->post($path);
        break;
    case "PUT":
        $router->put($path);
        break;
    case "DELETE":
        $router->delete($path);
        break;
    default:
        $router->get("/400");
}

