<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (Category::count() == 0) {
            Category::create([
                'name' => 'Seminar',
                'description' => 'Kegiatan seminar kampus',
            ]);

            Category::create([
                'name' => 'Workshop',
                'description' => 'Pelatihan praktis untuk mahasiswa',
            ]);
        }
    }
}
