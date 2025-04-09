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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable()->index();
            $table->string('account_id')->nullable()->index();
            $table->foreignId('zone_id')->nullable()->constrained();
            $table->enum('type', ['self', 'external', 'only_sale'])->comment('self, external, only_sale');
            $table->decimal('grand_total', 14, 2);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('due_amount', 14, 2)->default(0);
            $table->enum('paid_status', ['full_due', 'partial_paid', 'full_paid', 'in_house'])->default('full_due');
            $table->text('note')->nullable();
            $table->char('phone')->nullable()->index()->comment('walking customer phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
