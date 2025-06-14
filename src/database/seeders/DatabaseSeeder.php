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
        if (!Role::where('name', 'super_admin')->exists()) {
            Role::create(['name' => 'super_admin']);
        }

        if (User::count() == 0) {
            $user = \App\Models\User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
            ]);

            $user->assignRole('super_admin');
        }

        $this->call([
            CategorySeeder::class,
            EventSeeder::class,
        ]);
    }
}