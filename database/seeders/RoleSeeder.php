<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'staff', 'client'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::create([
            'name' => 'Rona',
            'email' => 'admin@chms.com',
            'password' => Hash::make('p@55w0rd'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
    }
}
