<?php

namespace App\Services\UserService;

class UserService
{
    public function checkPass($user, $password)
    {
        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }
}
