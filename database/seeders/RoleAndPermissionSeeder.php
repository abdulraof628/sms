<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Super Admin dashboard permissions
            'view-superadmin-dashboard',

            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Role management permissions
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            
            // Permission management permissions
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',

            // dashboard permissions
            'view-dashboard',

            // Daily report permissions
            'view-daily-reports',
            'create-daily-reports',
            'edit-daily-reports',
            'delete-daily-reports',

            // Branch management permissions
            'view-branches',
            'create-branches',
            'edit-branches',
            'delete-branches',

            // Class management permissions
            'view-classes',
            'create-classes',
            'edit-classes',
            'delete-classes',

            // Student management permissions
            'view-students',
            'create-students',
            'edit-students',
            'delete-students',

            // Tenant management permissions
            'view-tenants',
            'create-tenants',
            'edit-tenants',
            'delete-tenants',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create super-admin role and assign only superadmin-specific permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo([
            'view-superadmin-dashboard',
            'view-tenants',
            'create-tenants',
            'edit-tenants',
            'delete-tenants',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
        ]);

        // Create admin role and assign specific permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view-dashboard',

            'view-branches',
            'create-branches',
            'edit-branches',
            'delete-branches',

            'view-classes',
            'create-classes',
            'edit-classes',
            'delete-classes',

            'view-students',
            'create-students',
            'edit-students',
            'delete-students',

            // User management permissions (admin only)
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
        ]);

        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view-dashboard',
            'view-daily-reports',
            'create-daily-reports',
            'edit-daily-reports',
            'delete-daily-reports',
        ]);

        $principalRole = Role::firstOrCreate(['name' => 'principal']);
        $principalRole->givePermissionTo([
            'view-dashboard',
            'view-branches',
            'view-classes',
            'view-students',
            'view-daily-reports',
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view-dashboard',
            'view-daily-reports',
        ]);
    }
} 