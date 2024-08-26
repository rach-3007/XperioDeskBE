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

    public function updateRole(Request $request, $id)
    {
        $data = $request->validate([
            'role' => 'required|in:User,Admin,Privileged_User',
        ]);

        return response()->json($this->userService->updateRole($id, $data['role']));
    }
}
