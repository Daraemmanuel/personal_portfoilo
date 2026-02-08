<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // Articles
        Permission::create(['name' => 'view articles']);
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);

        // Projects
        Permission::create(['name' => 'view projects']);
        Permission::create(['name' => 'create projects']);
        Permission::create(['name' => 'edit projects']);
        Permission::create(['name' => 'delete projects']);

        // General
        Permission::create(['name' => 'access admin panel']);

        // create roles and assign created permissions

        // editor
        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo(['view articles', 'create articles', 'edit articles', 'access admin panel']);

        // article manager
        $role = Role::create(['name' => 'article manager']);
        $role->givePermissionTo([
            'view articles', 
            'create articles', 
            'edit articles', 
            'delete articles', 
            'publish articles',
            'access admin panel'
        ]);

        // admin
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
        
        // Assign admin role to the existing admin user
        $adminEmail = config('portfolio.admin_email') ?? 'immanuelatwork@gmail.com';
        $user = User::where('email', $adminEmail)->first();
        if ($user) {
            $user->assignRole('admin');
            $this->command->info("Assigned 'admin' role to {$adminEmail}");
        }
    }
}
