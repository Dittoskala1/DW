<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organizer;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        Organizer::create([
            'name' => 'BEM Fasilkom',
            'type' => 'student_org',
            'email' => 'bem@campus.ac.id',
            'phone' => '08123456789',
            'bio' => 'Organisasi mahasiswa Fakultas Ilmu Komputer.',
            'logo_url' => null,
        ]);

        Organizer::create([
            'name' => 'Departemen Akademik',
            'type' => 'department',
            'email' => 'akademik@campus.ac.id',
            'phone' => '08129876543',
            'bio' => 'Mengelola kegiatan akademik di kampus.',
            'logo_url' => null,
        ]);

        Organizer::create([
            'name' => 'BEM Wilayah',
            'type' => 'department',
            'email' => 'bemwilk@campus.ac.id',
            'phone' => '08129876544',
            'bio' => 'Organisasi mahasiswa.',
            'logo_url' => null,
        ]);

        Organizer::create([
            'name' => 'UKM Band',
            'type' => 'department',
            'email' => 'ukmbandueu@campus.ac.id',
            'phone' => '08129876545',
            'bio' => 'UKM Universitas Esa unggul.',
            'logo_url' => null,
        ]);

        Organizer::create([
            'name' => 'UEU Community',
            'type' => 'department',
            'email' => 'UEUCOM@campus.ac.id',
            'phone' => '08129876546',
            'bio' => 'Community Universitas Esa unggul.',
            'logo_url' => null,
        ]);
    }
}

