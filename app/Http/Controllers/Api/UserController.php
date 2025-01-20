<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|max:255|unique:users',
            'role' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            "name" => $request->get('name'),
            "email" => $request->get('email'),
            'email_verified_at' => now(),
            'password' => Hash::make('azerty'),
        ]);

        $role = Role::where('name',$request->get('role'))->first();

        $user->assignRole($role);

        $data = UserResource::make(User::find($user->id));

        return response()->json($data);

    }

    public function getUsers(Request $request)
    {
        $users = User::where('id', '!=', 1)->get();
        return response()->json(UserResource::collection($users));
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json(UserResource::make($user));
    }

}
