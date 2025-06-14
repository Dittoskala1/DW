<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Category;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'title' => 'Laravel 101',
            'description' => 'Belajar dasar Laravel',
            'start_date' => '2025-06-25',
            'start_time' => '09:00:00',
            'end_date' => '2025-06-25',
            'end_time' => '12:00:00',
            'location' => 'Lab 1',
            'status' => 'published',
            'category_id' => Category::first()->id,
            'poster_url' => 'laravel101.png',
        ]);
    }
}
