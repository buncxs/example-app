<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//use Spatie\Permission\Models\Role;
use App\Models\Role;
use App\Models\Permission;
//use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpiar la caché de permisos (Muy importante en Spatie)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear Permisos Básicos (Ejemplos)
        $permissions = [
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'role.view', 'role.create', 'role.edit', 'role.delete',
            'permission.view'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 3. Crear Roles y Asignar Permisos

        // Role: Super Admin (No le asignamos permisos específicos porque usaremos un Gate)
        Role::create(['name' => 'super-admin']);

        // Role: Admin (Tiene casi todo excepto borrar administradores)
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Role: Editor (Solo gestión de contenido/usuarios, no seguridad)
        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo(['user.view', 'user.edit']);

        // Role: User
        Role::create(['name' => 'user']);
    }
}