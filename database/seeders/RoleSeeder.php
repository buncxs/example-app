<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            [
                'name' => 'Super Administrador',
                'guard_name' => 'web',
                'description' => 'Control total y configuración técnica del sistema.',
                'icon' => 'ShieldAlert',
            ],
            [
                'name' => 'Administrador', 
                'guard_name' => 'web',
                'description' => 'Gestion de personal y operaciones del sistema.',
                'icon' => 'ShieldCheck'
            ],
            [
                'name' => 'Operador', 
                'guard_name' => 'web',
                'description' => 'Creacion y edición de registros diarios.',
                'icon' => 'UserCog',
            ],
            [
                'name' => 'Auditor', 
                'guard_name' => 'web',
                'description' => 'Acceso de solo lectura',
                'icon' => 'Eye',
            ],
        ];

        foreach($roles as $role){
            Role::create($role);
        }

    }
}
