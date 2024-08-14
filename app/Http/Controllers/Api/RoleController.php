<?php

namespace App\Http\Controllers\Api;

use App\Helpers\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $role = Role::create([
            "name" => Str::lower($request->get('name')),
            "description" => $request->get('description')
        ]);


        return response()->json($role);
    }

    public function setPermissions(Request $request, string $id)
    {
    }

    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($id),
            ],
            'description' => 'required|max:255|string'

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }


        $role = Role::findOrFail($id);
        $role->name = Str::lower($request->get('name'));
        $role->saveOrFail();

        return response()->json(RoleResource::make($role));
    }

    public function getRoles()
    {
        $roles = Role::where('name', '!=', RolesEnum::ADMIN_ROLE->value)->get();
        return response()->json(RoleResource::collection($roles));
    }

    public function getPermissions()
    {
        $permissions = Permission::all();
        return response()->json(PermissionResource::collection($permissions));
    }

}
