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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
                   $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shipping_address_id');
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->string('shipping_method'); // standard, express
            $table->string('payment_method'); // cod, bank_transfer
            $table->decimal('total', 10, 2);
            $table->integer('total_quantity');
            $table->text('order_note')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
