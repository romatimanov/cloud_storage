<?php

namespace App;

use App\Core\Db\Db;
use App\Core\Request\Request;
use App\Core\Response\Response;
use App\Core\Router\Router;
use App\Repositories\LoginRepositories\LoginRepositories;
use App\Repositories\UserRepositories\UserRepositories;

require_once __DIR__ . '/vendor/autoload.php';

$dbInstance = Db::getInstance();

$userRepository = new UserRepositories($dbInstance);
$loginRepository = new LoginRepositories($dbInstance);

$route = new Router();

$routeConfig = include __DIR__ . '/Core/Routes/Routes.php';

$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], file_get_contents('php://input'));

$headers = getallheaders();
$token = $headers['Authorization'] ?? null;
$loginRepository->verifyToken($token);


try {
    $response = $route->processRequest($request, $routeConfig);
    header('Content-Type: application/json');
    echo json_encode($response->setData());
} catch (\Exception $e) {
    $response = new Response(500, ['error' => $e->getMessage()]);
    header('Content-Type: application/json');
    echo json_encode($response->setData());
}
