<?php
namespace App\Services;

use App\Models\User;

class UserService 
{

    public function getUsersWithColumns()
    {
        // Traemos columnas necesarias
        $users = User::select('id', 'name', 'email')->get();

        // Encabezados para Datatable
        $columns = [
            ['key' => 'id', 'label' => 'Id' ],
            ['key' => 'name', 'label' => 'Nombre'],
            ['key' => 'email', 'label' => 'Correo'],
            ['key' => 'actions', 'label' => 'Acciones'],
        ];

        return [
            'data' => $users,
            'columns' => $columns,
        ];
    }


}

