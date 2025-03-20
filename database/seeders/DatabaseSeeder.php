<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // $this->call([
        //     CategorySeeder::class,
        //     BrandSeeder::class,
        //     ProductSeeder::class,
        // ]);

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'adminuser@gmail.com',
            'phone_number' => '09093206353',
            'role' => 'Admin',
            'status' => 'Inactive',
            'password' => bcrypt('password123'),
        ]);

        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@admin.com',
        //     'password' => bcrypt('password'),
        // ]);
    }
}
