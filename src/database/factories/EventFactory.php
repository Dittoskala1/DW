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

        // Acak bulan dan tanggal
        $startMonth = $this->faker->numberBetween(1, 12);
        $startDay = $this->faker->numberBetween(1, 28);
        $endMonth = $this->faker->numberBetween($startMonth, 12);
        $endDay = $this->faker->numberBetween(1, 28);

        // Buat DateTime dari bulan dan tanggal, lalu set tahun ke 2025
        $startDate = \DateTime::createFromFormat('!m-d', sprintf('%02d-%02d', $startMonth, $startDay));
        $startDate->setDate(2025, $startMonth, $startDay);

        $endDate = \DateTime::createFromFormat('!m-d', sprintf('%02d-%02d', $endMonth, $endDay));
        $endDate->setDate(2025, $endMonth, $endDay);

        return [
            'title' => $this->faker->randomElement($eventTypes) . ' ' . $this->faker->randomElement($topics),
            'description' => $this->faker->paragraph(),
            'start_date' => $startDate->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_date' => $endDate->format('Y-m-d'),
            'end_time' => '12:00:00',
            'status' => $this->faker->randomElement(['draft', 'published', 'archived', 'pending']),
            
            // Relasi dengan fallback kalau tidak ada data
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory()->create()->id,
            'organizer_id' => Organizer::inRandomOrder()->value('id') ?? Organizer::factory()->create()->id,
            'location_id' => Location::inRandomOrder()->value('id') ?? Location::factory()->create()->id,
            'audience_id' => Audience::inRandomOrder()->value('id') ?? Audience::factory()->create()->id,
        ];
    }
}
