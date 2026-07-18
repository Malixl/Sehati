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
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('respondent_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->dateTime('screened_at');

            // BAGIAN A
            $table->boolean('a_diabetes')->nullable();
            $table->boolean('a_hipertensi')->nullable();
            $table->boolean('a_jantung')->nullable();
            $table->boolean('a_stroke')->nullable();
            $table->boolean('a_kolesterol')->nullable();

            // BAGIAN B
            $table->boolean('b_diabetes')->nullable();
            $table->boolean('b_hipertensi')->nullable();
            $table->boolean('b_jantung')->nullable();
            $table->boolean('b_stroke')->nullable();
            $table->boolean('b_asma')->nullable();
            $table->boolean('b_kolesterol')->nullable();

            // BAGIAN C
            $table->smallInteger('c_sistolik')->nullable();
            $table->smallInteger('c_diastolik')->nullable();
            $table->decimal('c_berat', 5, 2)->nullable();
            $table->decimal('c_tinggi', 5, 2)->nullable();
            $table->decimal('c_perut', 5, 2)->nullable();
            $table->decimal('c_gula', 6, 2)->nullable();
            $table->decimal('c_kolesterol', 6, 2)->nullable();
            $table->decimal('c_asam_urat', 6, 2)->nullable();
            $table->boolean('c_merokok')->nullable();

            // RULE ENGINE OUTPUT
            $table->string('engine_version', 20)->nullable();
            $table->string('clinical_guideline', 100)->nullable();
            $table->string('dm_status', 100)->nullable();
            $table->enum('dm_severity', ['good', 'info', 'moderate', 'high', 'critical', 'diagnosed', 'diagnosed_uncontrolled', 'low'])->nullable();
            $table->string('ht_status', 100)->nullable();
            $table->enum('ht_severity', ['good', 'info', 'moderate', 'high', 'critical', 'diagnosed', 'diagnosed_uncontrolled', 'low'])->nullable();
            $table->enum('recommendation_level', ['healthy', 'lifestyle', 'monitor', 'visit_posyandu', 'visit_puskesmas', 'urgent', 'emergency'])->nullable();
            $table->text('decision_explanation')->nullable();
            $table->dateTime('calculated_at')->nullable();

            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index('respondent_id');
            $table->index('device_id');
            $table->index('screened_at');
            $table->index(['respondent_id', 'screened_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
