<?php

namespace App\ServiceInterfaces;

interface UserServiceInterface
{
    public function changePassword($userId, $newPassword);
    public function updateRole($userId, $role);
}
