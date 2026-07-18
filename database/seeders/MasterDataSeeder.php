<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create District
        $district = \App\Models\District::create([
            'code' => '7501', // Example code for Tilango, Gorontalo
            'name' => 'Tilango',
        ]);

        // 2. Create Villages
        $villages = [
            ['code' => '750101', 'name' => 'Desa Tualango'],
            ['code' => '750102', 'name' => 'Desa Dulomo'],
            ['code' => '750103', 'name' => 'Desa Tilote'],
            ['code' => '750104', 'name' => 'Desa Tabumela'],
            ['code' => '750105', 'name' => 'Desa Ilotidea'],
            ['code' => '750106', 'name' => 'Desa Lauwonu'],
            ['code' => '750107', 'name' => 'Desa Tenggela'],
            ['code' => '750108', 'name' => 'Desa Tinelo'],
        ];

        $createdVillages = [];
        foreach ($villages as $v) {
            $createdVillages[$v['name']] = \App\Models\Village::create([
                'district_id' => $district->id,
                'code' => $v['code'],
                'name' => $v['name'],
            ]);
        }

        // For backwards compatibility with posyandu creation below
        $village1 = $createdVillages['Desa Tilote'] ?? \App\Models\Village::first();
        $village2 = $createdVillages['Desa Tabumela'] ?? \App\Models\Village::first();

        // 3. Create Health Posts (Posyandu & Puskesmas)
        $hp0 = \App\Models\HealthPost::create([
            'village_id' => null,
            'code' => 'P-000',
            'name' => 'Puskesmas Tilango',
            'address' => 'Kecamatan Tilango',
        ]);
        $hp1 = \App\Models\HealthPost::create([
            'village_id' => $village1->id,
            'code' => 'P-001',
            'name' => 'Posyandu Mawar',
            'address' => 'Jl. Kesehatan No. 1, Tilango',
        ]);

        $hp2 = \App\Models\HealthPost::create([
            'village_id' => $village2->id,
            'code' => 'P-002',
            'name' => 'Posyandu Melati',
            'address' => 'Jl. Merdeka No. 2, Tabumela',
        ]);

        // 4. Create Users
        // Super Admin
        \App\Models\User::create([
            'name' => 'Super Admin SEHATI',
            'email' => 'superadmin@sehati.com',
            'password' => \Illuminate\Support\Facades\Hash::make('Penker2026'),
            'role' => 'super_admin',
        ]);

        \App\Models\User::create([
            'name' => 'Lusiane Adam S.Kep, M.Kes',
            'email' => 'lusiane.adam@sehati.com',
            'password' => \Illuminate\Support\Facades\Hash::make('Lusiane2026'),
            'role' => 'owner',
        ]);

        \App\Models\User::create([
            'name' => 'Admin Posyandu Mawar',
            'email' => 'admin@mawar.com',
            'password' => \Illuminate\Support\Facades\Hash::make('Admin123'),
            'role' => 'admin_posyandu',
        ]);

        // // Admin Posyandu
        // \App\Models\User::create([
        //     'name' => 'Admin Posyandu Mawar',
        //     'email' => 'admin.mawar@sehati.com',
        //     'password' => \Illuminate\Support\Facades\Hash::make('password'),
        //     'role' => 'admin_posyandu',
        //     'district_id' => $district->id,
        //     'health_post_id' => $hp1->id,
        // ]);
    }
}
