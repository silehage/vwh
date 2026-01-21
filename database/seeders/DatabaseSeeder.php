<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        User::factory()->create([
            'name' => 'Admin Istrator',
            'email' => 'admin@example.com',
            'role_id' => $role->id
        ]);

        Permission::createDefault();
    }
}
