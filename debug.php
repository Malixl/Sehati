<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'super_admin')->first();
if (!$user) $user = App\Models\User::find(4);
if ($user) {
    echo "User: " . $user->name . " Role: " . $user->role . "\n";
    echo "Total Screening::count() = " . App\Models\Screening::count() . "\n";
    echo "Total Screening::filterByRole() = " . App\Models\Screening::filterByRole($user)->count() . "\n";
    $service = new App\Services\Dashboard\ScreeningService();
    $paginated = $service->getPaginatedScreenings($user);
    echo "ScreeningService total = " . $paginated->total() . "\n";
    
    echo "Cache super_admin: ";
    print_r(Cache::get('kpi_summary_super_admin_global'));
}
