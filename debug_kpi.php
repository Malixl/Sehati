<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'super_admin')->first();
Auth::login($user);

$service = new App\Services\Dashboard\DashboardAnalyticsService();
Cache::flush();
$kpi = $service->getKpiSummary($user);
print_r($kpi);
