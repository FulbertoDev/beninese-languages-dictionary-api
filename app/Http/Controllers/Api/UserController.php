<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RolesHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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

        $data = UserResource::make(User::find($user->id));

        return response()->json($data);

    }

    public function getUsers(Request $request)
    {
        $currentUserId = $request->user()->id;
        $users = User::where('id', '!=', $currentUserId)->get();
        return response()->json(UserResource::collection($users));
    }

    public function setUserAbilities(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            RolesHelpers::canAddWord => 'required|boolean|max:255',
            RolesHelpers::canEditWord => 'required|boolean|max:255',
            RolesHelpers::canDeleteWord => 'required|boolean|max:255',
            RolesHelpers::canManageUsers => 'required|boolean|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $data = array();
        if ($request->get(RolesHelpers::canAddWord)) {
            $data[] = RolesHelpers::canAddWord;
        }
        if ($request->get(RolesHelpers::canEditWord)) {
            $data[] = RolesHelpers::canEditWord;
        }
        if ($request->get(RolesHelpers::canDeleteWord)) {
            $data[] = RolesHelpers::canDeleteWord;
        }
        if ($request->get(RolesHelpers::canManageUsers)) {
            $data[] = RolesHelpers::canManageUsers;
        }

        $abilities = join(";", $data);

        $user->abilities = $abilities;
        $user->save();

        return response()->json(UserResource::make($user));
    }

}
