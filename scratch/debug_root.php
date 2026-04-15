<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$email = 'test@example.com'; // Probando con el del seeder

echo "--- Debugging Root User ---\n";
echo "Email target: $email\n";

// Forzar conexión mysql (central)
$user = User::on('mysql')->where('email', $email)->first();

if (!$user) {
    echo "ERROR: User not found in 'mysql' connection.\n";
    
    // Ver usuarios existentes en mysql para ver qué hay
    $allUsers = DB::connection('mysql')->table('users')->pluck('email')->toArray();
    echo "Users in central DB: " . implode(', ', $allUsers) . "\n";
} else {
    echo "User found! ID: " . $user->id . "\n";
    echo "Checking roles on central connection...\n";
    
    // Intentar ver roles directamente de la tabla para evitar problemas de caché de Spatie
    $roles = DB::connection('mysql')->table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('model_id', $user->id)
        ->pluck('roles.name')
        ->toArray();
    
    echo "Roles found in DB: " . implode(', ', $roles) . "\n";
    
    if (in_array('root', $roles)) {
        echo "SUCCESS: User HAS the 'root' role in the central DB.\n";
    } else {
        echo "ERROR: User DOES NOT have the 'root' role in the central DB.\n";
    }
}
