<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Role mahasiswa dengan permission terbatas
        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);

        $mahasiswaPermissions = [
            'view event',
            'view any event',
            'view event dashboard',
            'view event registration',
            'create event registration',
        ];

        foreach ($mahasiswaPermissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            if (!$mahasiswa->hasPermissionTo($permission)) {
                $mahasiswa->givePermissionTo($permission);
            }
        }

        // Role super_admin dengan semua permission
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Ambil semua permissions dan berikan ke super_admin
        $allPermissions = Permission::all();

        foreach ($allPermissions as $permission) {
            if (!$superAdmin->hasPermissionTo($permission)) {
                $superAdmin->givePermissionTo($permission);
            }
        }
    }
}
