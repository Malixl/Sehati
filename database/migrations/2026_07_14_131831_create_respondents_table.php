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
        Schema::create('respondents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('fullname', 150);
            $table->date('birthdate');
            $table->enum('gender', ['L', 'P']);
            $table->string('phone', 25)->nullable();
            $table->text('address')->nullable();
            $table->foreignId('village_id')->constrained();
            $table->foreignId('health_post_id')->constrained();
            $table->timestamps();

            $table->index('nik');
            $table->index('village_id');
            $table->index('health_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respondents');
    }
};
