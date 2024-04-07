<?php

namespace App\Controller\Login;

use App\Core\Response\Response;
use App\Repositories\LoginRepositories\LoginInterface;

class Login
{
    private $loginRep;

    public function __construct(LoginInterface $loginRepositories)
    {
        $this->loginRep = $loginRepositories;
    }

    public function registerUser(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $email = $data['email'];
            $password = $data['password'];
            $age = $data['age'];

            $this->loginRep->addUser($email, $password, $age);
            
            return new Response(200, ['message' => 'User registered successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function login(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $email = $data['email'];
            $password = $data['password'];
            $this->loginRep->handleLogin($email, $password);

            return new Response(200, ['message' => 'User login successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function logout(): Response
    {
        try {
            $this->loginRep->logout();
            return new Response(200, ['message' => 'Logout successful']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function reset_password(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $email = $data['email'];
            $this->loginRep->resetPass($email);
            return new Response(200, ['message' => 'Password reset request submitted']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
}
