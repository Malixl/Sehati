<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update data lama
        DB::table('screenings')->whereIn('dm_severity', ['diagnosed'])->update(['dm_severity' => 'high']);
        DB::table('screenings')->whereIn('dm_severity', ['diagnosed_uncontrolled'])->update(['dm_severity' => 'critical']);
        DB::table('screenings')->whereIn('dm_severity', ['good', 'info'])->update(['dm_severity' => 'low']);

        DB::table('screenings')->whereIn('ht_severity', ['diagnosed'])->update(['ht_severity' => 'high']);
        DB::table('screenings')->whereIn('ht_severity', ['diagnosed_uncontrolled'])->update(['ht_severity' => 'critical']);
        DB::table('screenings')->whereIn('ht_severity', ['good', 'info'])->update(['ht_severity' => 'low']);

        DB::table('screenings')->where('recommendation_level', 'healthy')->update(['recommendation_level' => 'lifestyle']);
        DB::table('screenings')->where('recommendation_level', 'visit_posyandu')->update(['recommendation_level' => 'monitor']);
        DB::table('screenings')->where('recommendation_level', 'urgent')->update(['recommendation_level' => 'emergency']);

        // 2. Alter column types (ENUM)
        DB::statement("ALTER TABLE screenings MODIFY COLUMN dm_severity ENUM('low', 'moderate', 'high', 'critical') DEFAULT NULL");
        DB::statement("ALTER TABLE screenings MODIFY COLUMN ht_severity ENUM('low', 'moderate', 'high', 'critical') DEFAULT NULL");
        DB::statement("ALTER TABLE screenings MODIFY COLUMN recommendation_level ENUM('lifestyle', 'monitor', 'visit_puskesmas', 'emergency') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE screenings MODIFY COLUMN dm_severity ENUM('good', 'info', 'moderate', 'high', 'critical', 'diagnosed', 'diagnosed_uncontrolled', 'low') DEFAULT NULL");
        DB::statement("ALTER TABLE screenings MODIFY COLUMN ht_severity ENUM('good', 'info', 'moderate', 'high', 'critical', 'diagnosed', 'diagnosed_uncontrolled', 'low') DEFAULT NULL");
        DB::statement("ALTER TABLE screenings MODIFY COLUMN recommendation_level ENUM('healthy', 'lifestyle', 'monitor', 'visit_posyandu', 'visit_puskesmas', 'urgent', 'emergency') DEFAULT NULL");
    }
};
