<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use myPHPnotes\Microsoft\Auth;
use myPHPnotes\Microsoft\Models\User;
use App\Models\User as XperUser;

class MicrosoftAuthController extends Controller
{
    //Function for checking whether the Azure Token is valid
    public function loginAzure(Request $request)
    {
        $microsoft = new Auth(env('TENANT_ID'), env('CLIENT_ID'), env('CLIENT_SECRET'), env('CALLBACK_URL'), ["User.Read"]);
        $accessToken = $request->access_token;
        $microsoft->setAccessToken($accessToken);
 
        $user = new User; // if valid returns the user details
        // return response()->json($user);
        if (!$user) {
            return response()->json(['error' => 'Invalid Email or Password']);
        }
        $email_id = $user->data->getUserPrincipalName();
        // return response()->json($email_id);
        // Check if the user already exists in your users table
        $existingUser = XperUser::where('email_id', $email_id)->first();
        // return response()->json($existingUser);
         // If the user does not exist, create a new record
        if (!$existingUser) {
            $existingUser = XperUser::create([
                'email_id' => $email_id,
                'name' => $user->data->getDisplayName(), // Adjust field based on your users table
                'role_id' => 1, // Set default role or retrieve based on your business logic
                'du_id' => 1,
            ]);
        }

        $credentials = [
            'email_id' => $email_id
        ];
        $jwt_token = auth()->attempt($credentials); //Creates JWT token with emailid
        return $this->createNewToken($jwt_token,$user,$email_id);
    }
 
 
 
    //Funtion returns the complete details of user along with the JWT
    protected function createNewToken($token,$user,$email_id)
    {
        $existingUser = XperUser::where('email_id', $email_id)->first();


        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 3600,
            'user' => $user, 
            'XpeUser' => $existingUser,
        ]);
    }
    // protected function handleRoleCheck($token)
    // {
    //     $user = auth()->user(); //If valid JWT then returns user details
    //     $contrackUser = ContrackUser::where("experion_id", $user->id)->where("is_active", 1)->first();
 
    //     // Check if the user is authenticated
    //     if (!$contrackUser) {
    //         return response()->json(['error' => 'Access Denied. You do not have permission to access this application within the organization'], 401);
    //     } else {
    //         return $this->createNewToken($token, $contrackUser,$user);
 
    //     }
    // }
}