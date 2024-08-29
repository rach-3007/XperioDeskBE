<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;


interface UserServiceInterface
{
    public function changePassword($userId, $newPassword);
    public function updateRole($userId, $role);
    public function login(Request $request);
    public function register(Request $request);
    public function logout();
    public function refresh();
    public function userProfile();

}
