<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            if (!empty($data['role_ids'])) {
                $user->assignRole($data['role_ids']);
            }

            // dispara el envío del correo de confirmación
            event(new Registered($user));

            return $user;
        });
    }

    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function() use($user, $data) {
            
            $user->update($data);

            if (!empty($data['role_ids'])) {
                $user->syncRoles($data['role_ids']);
            }

            return $user;

        });
        
    }



}
