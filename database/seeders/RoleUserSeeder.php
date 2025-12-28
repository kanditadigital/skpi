<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => 'SuperAdmin123!',
                'role'  => 'super_admin',
            ],
            'admin' => [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => 'Admin123!',
                'role'  => 'admin_prodi',
            ],
            'pimpinan' => [
                'name' => 'Pimpinan',
                'email' => 'pimpinan@example.com',
                'password' => 'Pimpinan123!',
                'role'  => 'pimpinan',
            ],
        ];

        foreach ($roles as $roleName => $userData) {
            Role::firstOrCreate(['name' => $roleName]);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                ]
            );

            if (!$user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }
    }
}
