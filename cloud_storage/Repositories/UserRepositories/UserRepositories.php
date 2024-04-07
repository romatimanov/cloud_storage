<?php

namespace App\Repositories\UserRepositories;

use App\Core\Db\DbInterface;
use App\Core\Response\Response;
use Exception;

interface UserInterface
{
    public function list(): array;
    public function getId(int $id): array;
    public function updateUser(int $id, int $age): ?Response;
    public function searchEmail(string $email): array;
}

class UserRepositories implements UserInterface
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

        if (!$token) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
    }

    public function list(): array
    {
        $this->checkSession();

        try {
            $sql = "SELECT `age` FROM `users`";
            return $this->db->findAll($sql);
        } catch (Exception $e) {
            throw new Exception("Ошибка при отображении списка пользователей: " . $e->getMessage());
        }
    }

    public function searchEmail(string $email): array
    {
        $this->checkSession();
        try {
            $sql = "SELECT `email` FROM `users` WHERE `email` = :email";
            $params = [':email' => $email];
            return $this->db->findAll($sql, $params);
        } catch (Exception $e) {
            throw new Exception("Ошибка: " . $e->getMessage());
        }
    }
    public function getId(int $id): array
    {
        $this->checkSession();
        try {
            $sql = "SELECT `id`, `age` FROM `users` WHERE id = :id";
            $params = [':id' => $id];
            return $this->db->find($sql, $params);
        } catch (Exception $e) {
            throw new Exception("Пользователь с таким id не найден: " . $e->getMessage());
        }
    }

    public function updateUser(int $id, int $age): ?Response
    {
        $this->checkSession();

        if ($_SESSION['user_id'] == $id) {
            try {
                $sql = "UPDATE `users` SET `age`= :age WHERE `id`= :id";
                $params = [':id' => $id, ':age' => $age];
                $this->db->findBy($sql, $params);
            } catch (Exception $e) {
                return new Response(500, ['error' => "Не удалось обновить данные пользователя: " . $e->getMessage()]);
            }
        } else {
            return new Response(500, ['error' => "Вы не можете изменять данные другого пользователя: "]);
        }
    }
}
