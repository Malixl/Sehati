<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'super_admin')->first();
Auth::login($user);
if ($user) {
    echo "User: " . $user->name . " Role: " . $user->role . "\n";
    echo "Total Screening::count() = " . App\Models\Screening::count() . "\n";
    echo "Total Screening::filterByRole() = " . App\Models\Screening::filterByRole($user)->count() . "\n";
}
