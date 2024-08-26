<?php

namespace App\Services;

use App\Models\User;
use App\ServiceInterfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function changePassword($userId, $newPassword)
    {
        $user = User::findOrFail($userId);
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }

    public function updateRole($userId, $role)
    {
        $user = User::findOrFail($userId);
        $user->role = $role;
        $user->save();
        return $user;
    }
}
