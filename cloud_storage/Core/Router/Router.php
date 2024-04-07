<?php

namespace App\Core\Router;

use App\Core\Db\Db;
use App\Core\Response\Response;
use App\Repositories\AdminRepositories\AdminRepositories;
use App\Repositories\FilesRepositories\FilesRepositories;
use App\Repositories\LoginRepositories\LoginRepositories;
use App\Repositories\UserRepositories\UserRepositories;

class Router
{
    public function processRequest($request, $urlList): Response
    {
        $fullUrl = explode('?', $request->getRoute())[0];
        $method = $request->getMethod();

        foreach ($urlList as $url => $methods) {
            $pattern = '#^/cloud_storage/index\.php' . preg_replace('/\{.*?\}/', '(.*?)', str_replace('/', '\/', '/' . $url)) . '$#';

            if (preg_match($pattern, $fullUrl, $matches)) {
                $params = array_slice($matches, 1);

                if (isset($methods[$method])) {
                    $dbInstance = Db::getInstance();
                 
                    if($methods[$method]['rep'] == 'user') {
                        $userRepository = new UserRepositories($dbInstance);
                    }
                    if($methods[$method]['rep'] == 'admin') {
                        $userRepository = new AdminRepositories($dbInstance);
                    }
                    if($methods[$method]['rep'] == 'login') {
                        $userRepository = new LoginRepositories($dbInstance);
                    }
                    if($methods[$method]['rep'] == 'file') {
                        $userRepository = new FilesRepositories($dbInstance);
                    }
                    $controllerInstance = new $methods[$method]['controller']($userRepository);
                    $response = call_user_func_array([$controllerInstance, $methods[$method]['method']], $params);

                    if ($response instanceof Response) {
                        header('Content-Type: application/json');
                        return $response;
                    } else {
                        header('Content-Type: application/json');
                        return new Response(500, ['error' => 'Ошибка сервера']);
                    }
                } else {
                    return new Response(404, ['error' => 'Метод не найден']);
                }
            }
        }
        return new Response(404, ['error' => 'Страница не найдена']);
    }
}
