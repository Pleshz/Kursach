<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('tariff')->default('minute'); // minute|hour|day
            $table->string('start_at_text')->nullable(); // демо: "Сейчас" и т.п.
            $table->string('end_zone')->nullable();

            $table->string('status')->default('created'); // created|active|finished|cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

