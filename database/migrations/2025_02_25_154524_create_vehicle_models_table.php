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
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('manufacturer')->nullable()->comment('vehicle manufacturer / brand');
            $table->double('engine_cc')->nullable();
            $table->double('fuel_capacity')->nullable()->comment('max fuel capacity in liters');
            $table->double('payload_capacity')->nullable()->comment('max load capacity in kg');
            $table->double('body_length')->nullable()->comment('in feet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_models');
    }
};
