<?php

namespace App\Repositories\AdminRepositories;

use App\Core\Db\DbInterface;
use App\Core\Response\Response;
use Exception;

interface AdminInterface
{
    public function list(): array;
    public function getId(int $id): array;
    public function updateAdmin(int $id, string $email, string $role, int $age): ?Response;
    public function delete(int $id): ?Response;
}

class AdminRepositories implements AdminInterface
{
    private $db;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    private function checkSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_SESSION['token'] ?? null;
        if (!$token || $_SESSION['role'] !== 'admin') {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
    }

    public function list(): array
    {
        $this->checkSession();
        try {
            $sql = "SELECT * FROM `users`";
            return $this->db->findAll($sql);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Ошибка при отображении списка пользователей: " . $e->getMessage()]);
        }
    }

    public function getId(int $id): array
    {
        $this->checkSession();
        try {
            $sql = "SELECT * FROM `users` WHERE id = :id";
            $params = [':id' => $id];
            return $this->db->findAll($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Пользователь с таким id не найден: " . $e->getMessage()]);
        }
    }

    public function updateAdmin(int $id, string $email, string $role, int $age): ?Response
    {
        $this->checkSession();
        try {
            $columns = [];
            $params = [':id' => $id];
    
            if (!empty($email)) {
                $columns[] = '`email`= :email';
                $params[':email'] = $email;
            }
    
            if (!empty($role)) {
                $columns[] = '`role`= :role';
                $params[':role'] = $role;
            }
    
            if (!empty($age)) {
                $columns[] = '`age`= :age';
                $params[':age'] = $age;
            }

            $sql = "UPDATE `users` SET " . implode(', ', $columns) . " WHERE `id`= :id";
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось обновить данные пользователя: " . $e->getMessage()]);
        }
    }

    public function delete(int $id): ?Response
    {
        $this->checkSession();
        try {
            $sql = "DELETE FROM `users` WHERE `id` = :id";
            $params = [':id' => $id];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось удалить пользователя: " . $e->getMessage()]);
        }
    }
}
