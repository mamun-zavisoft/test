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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->enum('service_type', ['self', 'external'])->comment('self, external');
            $table->string('transaction_id')->nullable()->index();
            $table->foreignId('vehicle_id')->nullable()->constrained();
            $table->foreignId('zone_id')->nullable()->constrained();
            $table->decimal('total_amount', 14, 2);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('due_amount', 14, 2)->default(0);
            $table->enum('paid_status', ['full_due', 'partial_paid', 'full_paid', 'in_house'])->default('full_due');
            $table->text('note')->nullable();
            $table->foreignId('payment_type_id')->nullable();
            $table->boolean('any_parts_purchase')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
