<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\User::all() as $u) {
    Auth::login($u);
    echo "User: " . $u->name . " Role: " . $u->role . " HP: " . ($u->health_post_id ?: 'none') . "\n";
    echo "Count: " . App\Models\Screening::filterByRole($u)->count() . "\n";
    echo "Cache: ";
    $key = "kpi_summary_" . $u->role . "_" . ($u->health_post_id ?: 'global');
    print_r(Cache::get($key)['total_skrining'] ?? 'null');
    echo "\n\n";
}
