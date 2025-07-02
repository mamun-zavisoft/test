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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->double('mileage')->nullable()->default(0)->after('current_odometer');
        });
        
        Schema::table('vehicle_fuels', function (Blueprint $table) {
            $table->double('mileage')->nullable()->default(0)->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('mileage');
        });

        Schema::table('vehicle_fuels', function (Blueprint $table) {
            $table->dropColumn('mileage');
        });
    }
};
