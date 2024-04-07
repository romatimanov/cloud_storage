<?php

namespace App\Repositories\LoginRepositories;

use App\Core\Db\DbInterface;
use App\Core\Response\Response;
use App\Services\LoginService\LoginService;
use App\Services\UserService\UserService;
use Exception;

interface LoginInterface
{
    public function handleLogin(string $email, string $password): Response;
    public function getEmail(): ?Response;
    public function logout();
    public function addUser(string $email, string $password, string $age): ?Response;
    public function resetPass(string $email): ?Response;
}

class LoginRepositories implements LoginInterface
{
    private $db;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    public function getEmail(): ?Response
    {
        try {
            $sql = "SELECT `email` FROM `users`";
            $this->db->findAll($sql);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Ошибка: " . $e->getMessage()]);
        }
    }

    public function handleLogin(string $email, string $password): Response
    {

        $serviceUser = new UserService();

        try {
            if (!empty($email) && !empty($password)) {
                $sql = "SELECT * FROM users WHERE email = :email";
                $params = [':email' => $email];
                $user = $this->db->find($sql, $params);

                if ($serviceUser->checkPass($user, $password)) {
                    session_start();

                    $additionalInfo = $this->generateAdditionalInfo($user['id']);

                    $payload = base64_encode(json_encode([
                        'user_id' => $user['id'],
                        'exp' => time() + (60 * 60),
                        'additional_info' => $additionalInfo,
                    ]));

                    $encodedPayload = urlencode($payload);

                    $secretKey = 'my_token';
                    $signature = hash_hmac('sha256', "$encodedPayload", $secretKey, true);
                    $encodedSignature = base64_encode($signature);

                    $token = "$encodedPayload.$encodedSignature";
                    $_SESSION['token'] = $token;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    header("Authorization: Bearer $token");

                    return new Response(200, ['token' => $token]);
                }
            }
        } catch (Exception $e) {
            return new Response(500, ['error' => "Error: " . $e->getMessage()]);
        }
        return new Response(401, ['error' => 'Invalid credentials']);
    }

    private function generateAdditionalInfo($userId): string
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $additionalInfo = password_hash("$ip|$userId", PASSWORD_DEFAULT);

        return $additionalInfo;
    }

    public function verifyToken($token): bool
    {
        if ($token === null) {
            return false;
        }

        $secretKey = 'my_token';

        $tokenParts = explode('.', $token);

        if (count($tokenParts) !== 2) {
            return false;
        }

        list($payload, $encodedSignature) = $tokenParts;

        $calculatedSignature = hash_hmac('sha256', $payload, $secretKey, true);
        $calculatedEncodedSignature = base64_encode($calculatedSignature);

        if ($calculatedEncodedSignature !== $encodedSignature) {
            return false;
        }

        $decodedPayload = json_decode(base64_decode($payload), true);

        if (!isset($decodedPayload['exp']) || $decodedPayload['exp'] < time()) {
            return false;
        }

        return true;
    }

    public function logout()
    {
        session_start();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');

        foreach ($_COOKIE as $key => $value) {
            setcookie($key, '', time() - 3600, '/');
        }

        header_remove('Authorization');
    }

    public function addUser(string $email, string $password, string $age): ?Response
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users`(`id`, `email`, `password`, `role`, `age`) VALUES (null, :email, :password, :role, :age)";
            $params = [':email' => $email, ':password' => $hashedPassword, ':role' => 'users', ':age' => $age];
            $this->db->findBy($sql, $params);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Не удалось добавить пользователя: " . $e->getMessage()]);
        }
    }

    public function resetPass(string $email): ?Response
    {
        $service = new LoginService();
        try {
            $decodedEmail = urldecode($email);
            $sql = "SELECT `email` FROM users WHERE email = :email";
            $params = [':email' => $decodedEmail];
            $this->db->findBy($sql, $params);
            $service->sendMail($decodedEmail);
        } catch (Exception $e) {
            return new Response(500, ['error' => "Error: " . $e->getMessage()]);
        }
    }
}
