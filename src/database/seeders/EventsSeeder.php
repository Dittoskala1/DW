<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Events;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Events::count() == 0) {
            Events::create([
                'title' => 'Seminar Teknologi AI',
                'description' => 'Seminar membahas tren terbaru dalam Artificial Intelligence.',
                'start_time' => '2025-07-20 09:00:00',
                'end_time' => '2025-07-20 12:00:00',
                'location' => 'Aula FIK 1',
                'status' => 'published',
                'category_id' => 1, // make sure this exists in categories
                'poster_url' => '',
            ]);

            Events::create([
                'title' => 'Workshop UI/UX Design',
                'description' => 'Pelatihan dasar desain antarmuka dan pengalaman pengguna.',
                'start_time' => '2025-08-10 13:00:00',
                'end_time' => '2025-08-10 16:00:00',
                'location' => 'Lab Multimedia',
                'status' => 'published',
                'category_id' => 2,
                'poster_url' => '',
            ]);
        }
    }
}
