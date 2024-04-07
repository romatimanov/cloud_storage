<?php

namespace App\Controller\Admin;

use App\Core\Response\Response;
use App\Repositories\AdminRepositories\AdminInterface;

class Admin
{
    private $adminRep;

    public function __construct(AdminInterface $adminRepository)
    {
        $this->adminRep = $adminRepository;
    }

    public function list(): Response
    {
        try {
            $data = $this->adminRep->list();
            return new Response(['data' => $data], 200);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function getId(int $id): Response
    {
        try {
            $data = $this->adminRep->getId($id);
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
            $email = $data['email'];
            $role = $data['role'];
            $age = $data['age'];
            $this->adminRep->updateAdmin($id, $email, $role, $age);
            return new Response(200, ['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }

    public function delete(): Response
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'];
            $this->adminRep->delete($id);
            return new Response(200, ['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return new Response(500, ['error' => $e->getMessage()]);
        }
    }
}
