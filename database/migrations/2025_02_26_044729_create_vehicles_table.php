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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('owner_type', [1, 2])->comment('1=self, 2=external');
            $table->enum('vehicle_type', [1, 2, 3, 4, 5])->nullable()->comment('1=Covered van, 2=Motorbike, 3=Pickup, 4=Truck, 5=TBA/other');
            $table->foreignId('zone_id')->nullable()->constrained();
            $table->foreignId('hub_id')->nullable();
            $table->foreignId('vehicle_model_id')->nullable()->constrained();
            $table->string('license_plate')->unique();
            $table->date('registration_date')->nullable();
            $table->date('registration_validity')->nullable();
            $table->date('tax_token_validity')->nullable();
            $table->date('fitness_validity')->nullable();
            $table->date('road_permit_validity')->nullable();
            $table->date('insurance_validity')->nullable();
            $table->double('current_odometer')->nullable()->default(0)->comment('in kilometer');
            $table->enum('status', [1, 2])->default(1)->comment('1=active, 2=in service');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
