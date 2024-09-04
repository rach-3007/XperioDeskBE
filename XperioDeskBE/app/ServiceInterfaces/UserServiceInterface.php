<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;


interface UserServiceInterface
{
    public function changePassword($userId, $newPassword);
    public function login(Request $request);
    public function register(Request $request);
    public function logout();
    public function refresh();
    public function userProfile();
    public function getUsersWithRoles();
    public function updateUserRole($userId, $role);
    /**
     * Get user details by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getUserById($id);


}
