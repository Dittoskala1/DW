<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::create([
            'venue_name' => 'Auditorium A',
            'building_name' => 'Main Hall',
            'room_number' => '101',
            'address' => 'Campus Center',
            'capacity' => 300,
        ]);

        Location::create([
            'venue_name' => 'Open Field',
            'building_name' => null,
            'room_number' => null,
            'address' => 'Near Student Union',
            'capacity' => 1000,
        ]);

        Location::create([
            'venue_name' => 'Lobby',
            'building_name' => null,
            'room_number' => null,
            'address' => 'Univ Esa Unggul Building A',
            'capacity' => 1000,
        ]);

        Location::create([
            'venue_name' => 'Library',
            'building_name' => null,
            'room_number' => null,
            'address' => 'Univ Esa Unggul Building B',
            'capacity' => 1000,
        ]);

        Location::create([
            'venue_name' => 'Canteen',
            'building_name' => null,
            'room_number' => null,
            'address' => 'Univ Esa Unggul Building A',
            'capacity' => 1000,
        ]);
    }
}
