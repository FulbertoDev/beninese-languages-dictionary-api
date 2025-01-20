<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where("email", $request->email)->firstOrFail();

        if(!$user->isActive){
            return response()->json([
                'message' => 'Account not active'
            ], 401);
        }



        $token = $user->createToken('auth_token')->plainTextToken;

        if(!$user->isActive){
            return response()->json([
                'message' => 'Account not active'
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => UserResource::make($user)
        ]);
    }

}
