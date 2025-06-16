<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Category;
use App\Models\Organizer;
use App\Models\Location;
use App\Models\Audience;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first();
        $organizer = Organizer::first();
        $location = Location::first();
        $audience = Audience::first();

        Event::create([
            'title' => 'Campus Tech Expo 2025',
            'description' => 'A showcase of innovative student projects and technology seminars.',
            'start_date' => now()->addDays(7)->toDateString(),
            'start_time' => '09:00:00',
            'end_date' => now()->addDays(7)->toDateString(),
            'end_time' => '17:00:00',
            'status' => 'published',
            'category_id' => $category?->id,
            'organizer_id' => $organizer?->id,
            'location_id' => $location?->id,
            'audience_id' => $audience?->id,
        ]);
    }
}
