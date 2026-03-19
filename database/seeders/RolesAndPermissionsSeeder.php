<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create Roles
        $roles = [
            'super admin',
            'school owner',
            'campus manager',
            'principal',
            'school administrator',
            'teacher',
            'student',
            'parent',
            'staff',
            'accountant',
            'librarian',
            'lab assistant',
            'data entry operator',
            'transport manager'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
        
        // You can add default permissions here later
    }

    public function run(): void
    {
        $this->up();
    }
}
