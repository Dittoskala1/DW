<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Organizer;
use App\Models\Location;
use App\Models\Audience;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
{
    $eventTypes = [
        'Webinar', 'Seminar', 'Workshop', 'Pelatihan', 'Talkshow',
        'Kuliah Umum', 'Diskusi Panel', 'Meetup', 'Conference', 'Bootcamp',
        'Forum', 'Lokakarya', 'Training', 'Sharing Session', 'Symposium',
    ];

    $topics = [
        'Digital Marketing', 'Teknologi AI', 'Data Science', 'UI/UX Design',
        'Public Speaking', 'Startup', 'Kesehatan Mental', 'Leadership',
        'Programming', 'Keamanan Siber', 'Manajemen Proyek', 'Machine Learning',
        'Blockchain', 'Cloud Computing', 'Statistik Terapan', 'Desain Grafis',
        'Keuangan Mahasiswa', 'Pemrograman Web', 'Etika Profesi', 'Networking Dasar',
    ];

    return [
        'title' => $this->faker->randomElement($eventTypes) . ' ' . $this->faker->randomElement($topics),
        'description' => $this->faker->paragraph(),
        'start_date' => $this->faker->dateTimeBetween('+1 days', '+10 days')->format('Y-m-d'),
        'start_time' => '09:00:00',
        'end_date' => $this->faker->dateTimeBetween('+11 days', '+20 days')->format('Y-m-d'),
        'end_time' => '12:00:00',
        'status' => $this->faker->randomElement(['draft', 'published', 'archived', 'pending']),
        'category_id' => \App\Models\Category::inRandomOrder()->value('id'),
        'organizer_id' => \App\Models\Organizer::inRandomOrder()->value('id'),
        'location_id' => \App\Models\Location::inRandomOrder()->value('id'),
        'audience_id' => \App\Models\Audience::inRandomOrder()->value('id'),
    ];
}

}

