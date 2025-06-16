<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Audience;

class AudienceSeeder extends Seeder
{
    public function run(): void
    {
        Audience::insert([
            ['name' => 'Freshmen'],
            ['name' => 'Sophomores'],
            ['name' => 'Final Year Students'],
            ['name' => 'All Students'],
            ['name' => 'Staff & Faculty'],
        ]);
    }
}

