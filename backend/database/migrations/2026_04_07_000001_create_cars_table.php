<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('segment')->nullable(); // Эконом/Комфорт/Бизнес/Электро
            $table->string('transmission')->nullable(); // Автомат/Механика
            $table->string('fuel')->nullable(); // Бензин/Дизель/Электро
            $table->text('description')->nullable();
            $table->unsignedInteger('rate_minute')->default(0);
            $table->unsignedInteger('rate_hour')->default(0);
            $table->unsignedInteger('rate_day')->default(0);
            $table->unsignedInteger('range_km')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};

