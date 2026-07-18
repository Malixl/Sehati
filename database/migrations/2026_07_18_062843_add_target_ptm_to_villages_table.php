<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->integer('target_usia_produktif')->default(0)->after('name');
            $table->integer('target_ht')->default(0)->after('target_usia_produktif');
            $table->integer('target_dm')->default(0)->after('target_ht');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn(['target_usia_produktif', 'target_ht', 'target_dm']);
        });
    }
};
