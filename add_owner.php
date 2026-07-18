<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin_posyandu', 'owner') NOT NULL");

User::updateOrCreate(
    ['email' => 'lusiane.adam@sehati.com'],
    [
        'name' => 'Lusiane Adam S.Kep, M.Kes',
        'password' => Hash::make('Lusiane2026'),
        'role' => 'owner'
    ]
);

echo "Success!\n";
