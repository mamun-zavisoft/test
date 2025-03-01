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
            $table->enum('type', ['self', 'external']);
            $table->bigInteger('due_amount')->default(0);
            $table->bigInteger('pay_amount')->default(0);
            $table->bigInteger('total_amount');
            $table->enum('payment_status', [1, 2, 3, 4])->comment('1=Due,2=Partial Paid,3=Full paid,4=In-house');
            $table->text('note')->nullable();
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
