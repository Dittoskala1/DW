<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
       
        $role = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);

        
        $permissions = [
            
            'view event',
            'view any event',

            'view event dashboard',

            'view event registration',
            'create event registration',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);

            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }
    }
}
