<?php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Reset cache
app()[PermissionRegistrar::class]->forgetCachedPermissions();

// Ensure permissions exist
$permissions = [
    // Articles
    'view articles',
    'create articles',
    'edit articles',
    'delete articles',
    'publish articles',
    
    // Projects
    'view projects',
    'create projects',
    'edit projects',
    'delete projects',
    
    // General
    'access admin panel'
];

foreach ($permissions as $name) {
    if (!Permission::where('name', $name)->exists()) {
        Permission::create(['name' => $name, 'guard_name' => 'web']);
        echo "Created permission: {$name}\n";
    } else {
        echo "Permission exists: {$name}\n";
    }
}

// Ensure roles have these permissions
$editor = Role::where('name', 'editor')->first();
if ($editor) {
    $editor->givePermissionTo(['view articles', 'create articles', 'edit articles', 'access admin panel']);
    echo "Updated editor permissions\n";
}

$articleManager = Role::where('name', 'article manager')->first();
if ($articleManager) {
    $articleManager->givePermissionTo([
        'view articles', 
        'create articles', 
        'edit articles', 
        'delete articles', 
        'publish articles',
        'access admin panel'
    ]);
    echo "Updated article manager permissions\n";
}

echo "Done.\n";
