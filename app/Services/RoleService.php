<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

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

            if(!empty($data['permission_ids'])) {
                $role->syncPermissions($data['permission_ids']);
            }

            return $role;

        });
    }
}
