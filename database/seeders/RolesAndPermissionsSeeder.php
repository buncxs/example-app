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
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
            'permission.view',
            'permission.create',
            'permission.edit',
            'permission.delete',
        ];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            $module = $parts[0]; // Tomamos la primera parte: 'user'
            Permission::create(['name' => $permission, 'module' => $module]);
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
