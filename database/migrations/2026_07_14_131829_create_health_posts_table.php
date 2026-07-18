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
        Schema::create('health_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('code', 30)->unique();
            $table->string('name', 150);
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('pic_name', 150)->nullable();
            $table->string('phone', 25)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_posts');
    }
};
