<?php

namespace App\Core\Db;

use Exception;
use PDO;

interface DbInterface
{
    public function getConnection();
    public function findAll(string $sql, array $params = []): array;
    public function find(string $sql, array $params = []): ?array;
    public function findBy(string $sql, array $params = []);
}

class Db implements DbInterface
{
    private static $instance;

    private $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=localhost;dbname=cloud_storage;charset=utf8", 'root', '12345');
        } catch (Exception $e) {
            throw new Exception("Ошибка соединения: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {

        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
    public function findAll(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error executing query: " . $e->getMessage());
        }
    }
    public function find(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false ? $result : null;
        } catch (Exception $e) {
            throw new Exception("Error executing query: " . $e->getMessage());
        }
    }
    public function findBy(string $sql, array $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (Exception $e) {
            throw new Exception("Error executing query: " . $e->getMessage());
        }
    }
}
