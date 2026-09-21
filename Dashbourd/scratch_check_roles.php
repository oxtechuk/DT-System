<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

echo "Roles count: " . Role::count() . "\n";
foreach (Role::all() as $r) {
    echo "- Role: {$r->name} (slug: {$r->slug}) - Perms: " . $r->permissions()->count() . " - Users: " . $r->users()->count() . "\n";
}

echo "Permissions count: " . Permission::count() . "\n";
echo "Users count: " . User::count() . "\n";
foreach (User::all() as $u) {
    echo "- User: {$u->name} ({$u->email}) - Role: " . ($u->role ? $u->role->name : 'None') . "\n";
}
