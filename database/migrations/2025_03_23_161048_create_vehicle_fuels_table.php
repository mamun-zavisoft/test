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
        Schema::create('vehicle_fuels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained();
            $table->enum('fuel_type', [1,2,3])->comment('1=Diesel , 2=Petrol, 3=Octane');
            $table->double('current_odometer')->comment('in kilometer');
            $table->double('fuel_qty')->comment('in liters');
            $table->double('fuel_rate')->comment('per liter');
            $table->double('total_price')->comment('total cost of fuel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_fuels');
    }
};
