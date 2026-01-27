<?php

namespace App\Services;

use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create(
                [
                    'name' => $data['name'],
                    'guard_name' => 'web',
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                ]
            );

            if (!empty($data['permission_ids'])) {
                $role->syncPermissions($data['permission_ids']);
            }

            return $role;
        });
    }

    public function updateRole(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update(
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                ]);
            $role->syncPermissions($data['permission_ids']);
            return $role;
        });
          
    }

    public function getGroupedPermissions()
    {
        $permissions = Permission::select('id', 'name', 'module')->get();
        return collect(PermissionResource::collection($permissions)
        ->resolve())->groupBy('module');
    }


}
