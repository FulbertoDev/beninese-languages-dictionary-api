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
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'permissions' => [
                'required',
                'list',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $data = $request->get('permissions');
        $permissions = Permission::all()->pluck('name')->toArray();

        $diffElements = array_diff($data, $permissions);
        if (!empty($diffElements)) {
            return response()->json([
                "message" => "Some permissions don't exist"
            ], 404);
        }


        foreach ($permissions as $permissionValue) {
            $permission = Permission::where('name', $permissionValue)->firstOrFail();
            $role->revokePermissionTo($permission);
        }

        foreach ($data as $permissionValue) {
            $permission = Permission::where('name', $permissionValue)->firstOrFail();
            $role->givePermissionTo($permission);

        }

        $role = Role::findOrFail($id);
        return response()->json(RoleResource::make($role));

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
        $roles = Role::all();
        return response()->json(RoleResource::collection($roles));
    }

    public function getPermissions()
    {
        $permissions = Permission::all();
        return response()->json(PermissionResource::collection($permissions));
    }

}
