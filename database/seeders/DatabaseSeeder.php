<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

use function Symfony\Component\Clock\now;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::create([
            'name' => 'Ivan Salazar',
            'email' => 'buncxs@gmail.com',
            'password' => bcrypt('3323'),
            'email_verified_at' => now(),
        ]);

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        $user->assignRole('Super Administrador');

    }
}
