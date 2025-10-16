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
            // Business dashboard permissions
            'view-business-dashboard',

            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Tenant management permissions
            'view-tenants',
            'create-tenants',
            'edit-tenants',
            'delete-tenants',
            
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

            // School management permissions
            'view-schools',
            'create-schools',
            'edit-schools',
            'delete-schools',

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

            // Organization management permissions
            'view-organizations',
            'create-organizations',
            'edit-organizations',
            'delete-organizations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create super-admin role and assign all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'developer']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Create super-admin role and assign specific permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo([
            'view-business-dashboard',
            'view-dashboard',

            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            'view-tenants',
            'create-tenants',
            'edit-tenants',
            'delete-tenants',

            'view-schools',
            'create-schools',
            'edit-schools',
            'delete-schools',

            'view-classes',
            'create-classes',
            'edit-classes',
            'delete-classes',

            'view-students',
            'create-students',
            'edit-students',
            'delete-students',

            'view-organizations',
            'create-organizations',
            'edit-organizations',
            'delete-organizations',
        ]);

        // Create admin role and assign specific permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view-dashboard',

            'view-schools',
            'create-schools',
            'edit-schools',
            'delete-schools',

            'view-classes',
            'create-classes',
            'edit-classes',
            'delete-classes',

            'view-students',
            'create-students',
            'edit-students',
            'delete-students',
        ]);

        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view-dashboard',
            'view-daily-reports',
            'create-daily-reports',
            'edit-daily-reports',
            'delete-daily-reports',
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view-dashboard',
            'view-daily-reports',
        ]);
    }
} 