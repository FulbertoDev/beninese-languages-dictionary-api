<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AuthErrors;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @unauthenticated
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::where('email', $request->email);
        if ($user->count() <= 0) {
            return response()->json([
                'message' => AuthErrors::INVALID_CREDENTIALS
            ], 401);
        }

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => AuthErrors::INVALID_CREDENTIALS
            ], 401);
        }

        $user = User::where("email", $request->email)->firstOrFail();

        if ($user->isDisabled) {
            return response()->json([
                'message' => AuthErrors::ACCOUNT_DISABLED
            ], 401);
        }

        $token = $user->createToken('auth-token-' . $user->id)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => UserResource::make($user)
        ]);
    }


    /**
     * @authenticated
     */
    public function setPassword(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => AuthErrors::UN_AUTHORIZED
            ], 401);
        }


        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $email = $request->input("email");
        $password = $request->input("password");

        if ($user->email != $email) {
            return response()->json([
                'message' => AuthErrors::ACCOUNT_MISMATCH
            ], 422);
        }
        if ($user->isDisabled) {
            return response()->json([
                'message' => AuthErrors::ACCOUNT_DISABLED
            ], 422);
        }

        if ($user->isActive) {
            return response()->json([
                'message' => AuthErrors::ACCOUNT_ALREADY_ACTIVE
            ], 422);
        }


        $hash = Hash::make($password);
        $user->password = $hash;
        $user->isActive = true;
        $user->save();

        $result = User::findOrFail($user->id);

        return response()->json(UserResource::make($result));
    }
}
