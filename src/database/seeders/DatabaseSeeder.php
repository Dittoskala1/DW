<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan role super_admin ada
        if (!User::where('email', 'admin@admin.com')->exists()) {
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'), 
            ]);
            $admin->assignRole('super_admin');
        }

        if (!User::where('email', 'user@admin.com')->exists()) {
            $user = User::factory()->create([
                'name' => 'User',
                'email' => 'user@admin.com',
                'password' => bcrypt('password'), 
            ]);
            $user->assignRole('mahasiswa');  
        }


        $this->call([
            CategorySeeder::class,
            EventSeeder::class,
            LocationSeeder::class,
            OrganizerSeeder::class,
            AudienceSeeder::class,
            RoleSeeder::class,
        ]);
    }
}