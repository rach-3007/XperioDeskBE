<?php

namespace App\Services;

use App\Models\User;
use App\ServiceInterfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

use Validator;


class UserService implements UserServiceInterface
{
    public function changePassword($userId, $newPassword)
    {
        $user = User::findOrFail($userId);
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }

     // Fetch users with their roles
     public function getUsersWithRoles()
     {
         return User::with('role')->get(['id', 'name', 'email_id', 'role_id']);
     }
 
     // Update the user's role
     public function updateUserRole($userId, $role)
     {
         $user = User::findOrFail($userId);
         
         
         $roleModel = Role::where('role_name', $role)->firstOrFail();
 
         $user->role_id = $roleModel->id;
         $user->save();
     }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email_id' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'role_id' => 'required',
            'designation' => 'required|string',
            'du_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    /**
     * Get user details by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return User::find($id); // Fetch user details by ID
    }
}
