<?php

namespace App\Services;


use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create(
                [
                    'name' => $data['name'],
                    'guard_name' => 'web'
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
            $role->update(['name' => $data['name']]);
            $role->syncPermissions($data['permission_ids']);
            return $role;
        });
          
    }
}
