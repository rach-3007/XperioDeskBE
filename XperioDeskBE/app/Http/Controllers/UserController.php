<?php

namespace App\Http\Controllers;

use App\ServiceInterfaces\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        return response()->json($this->userService->changePassword($data['user_id'], $data['new_password']));
    }

    

    public function login(Request $request)
    {
        return $this->userService->login($request);
    }

    public function register(Request $request)
    {
        return $this->userService->register($request);
    }

    public function logout()
    {
        return $this->userService->logout();
    }

    public function refresh()
    {
        return $this->userService->refresh();
    }

    public function userProfile()
    {
        return $this->userService->userProfile();
    }

    // Get a list of users with their roles
    public function getUsersWithRoles()
    {
        try {
            $users = $this->userService->getUsersWithRoles();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching users with roles', 'message' => $e->getMessage()], 500);
        }
    }

    // Update the user's role
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|in:Admin,User',
        ]);

        try {
            $this->userService->updateUserRole($id, $request->input('role'));
            return response()->json(['message' => 'User role updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating user role', 'message' => $e->getMessage()], 500);
        }
    }
}
