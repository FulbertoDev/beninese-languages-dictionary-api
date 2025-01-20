<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public static $wrap = null;

    public bool $preserveKeys = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'isActive' => $this->isActive,
            'role' => RoleResource::make(Role::where('name', $this->getRoleNames()[0])->first()),
        ];
    }
}
