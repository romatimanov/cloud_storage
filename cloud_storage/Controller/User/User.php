<?php

namespace App\Controller\User;

use App\Core\Response\Response;
use App\Repositories\UserRepositories\UserInterface;

class User
{
    private $userRep;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRep = $userRepository;
    }

    public function list(): Response
    {
        try {
            $data = $this->userRep->list();
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getId(int $id): Response
    {
        try {
            $data = $this->userRep->getId($id);
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function searchEmail(string $email): Response
    {
        try {
            $data = $this->userRep->searchEmail($email);
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function update(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];
            $age = $data['age'];
            $this->userRep->updateUser($id, $age);
            return new Response(['message' => 'User updated successfully'], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
}
