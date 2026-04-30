<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('start_at')->nullable()->after('tariff');
            $table->dateTime('end_at')->nullable()->after('start_at');
            $table->index(['car_id', 'status', 'start_at', 'end_at'], 'orders_car_status_schedule_idx');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_car_status_schedule_idx');
            $table->dropColumn(['start_at', 'end_at']);
        });
    }
};
