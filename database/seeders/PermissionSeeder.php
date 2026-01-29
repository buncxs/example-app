<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert(
            [
                ['name' => 'users.view', 'guard_name' => 'web', 'module' => 'users'],
                ['name' => 'users.create', 'guard_name' => 'web', 'module' => 'users'],
                ['name' => 'users.edit', 'guard_name' => 'web', 'module' => 'users'],
                ['name' => 'users.delete', 'guard_name' => 'web', 'module' => 'users'],
                ['name' => 'roles.view', 'guard_name' => 'web', 'module' => 'roles'],
                ['name' => 'roles.create', 'guard_name' => 'web', 'module' => 'roles'],
                ['name' => 'roles.edit', 'guard_name' => 'web', 'module' => 'roles'],
                ['name' => 'roles.delete', 'guard_name' => 'web', 'module' => 'roles'],
            ]
        );
    }
}
