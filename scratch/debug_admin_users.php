<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Organization;

$orgs = Organization::on('mysql')->with('users')->get();

foreach($orgs as $org) {
    echo "Org: {$org->name} (ID: {$org->id})\n";
    echo "Users count: " . $org->users->count() . "\n";
    foreach($org->users as $user) {
        echo "- User: {$user->email}\n";
    }
    echo "-------------------\n";
}
