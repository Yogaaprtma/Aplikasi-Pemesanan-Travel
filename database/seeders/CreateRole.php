<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Role
        $roles = ['admin', 'user'];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role]);
            }
        };

        $users = [
            ['nama' => 'Admin', 'email' => 'admin@example.com', 'no_telp' => '082110778946', 'alamat' => 'Pati', 'password' => bcrypt('12345678'), 'role' => 'admin'],
        ];

        foreach ($users as $userData) {
            $admin = User::create([
                'nama' => $userData['nama'],
                'email' => $userData['email'],
                'no_telp' => $userData['no_telp'],
                'alamat' => $userData['alamat'],
                'password' => $userData['password'],
            ]);
            $admin->assignRole($userData['role']);
        }
    }
}