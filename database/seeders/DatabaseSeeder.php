<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Database\Seeders\RoomSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call(RoleSeeder::class);
        $this->call(RoomSeeder::class);
    }
}
