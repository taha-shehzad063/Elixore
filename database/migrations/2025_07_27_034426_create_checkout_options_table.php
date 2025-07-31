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
        Schema::create('checkout_options', function (Blueprint $table) {
            $table->id();
             $table->string('type'); // e.g., shipping, payment, billing

            // Shared fields
            $table->string('key')->nullable();   // e.g., 'standard', 'cod', 'billing'
            $table->string('label')->nullable(); // e.g., 'Cash on Delivery', 'Bank Transfer'
            $table->decimal('shipping_cost', 8, 2)->nullable(); // Only for shipping
            $table->text('message')->nullable(); // Additional note to show in UI

            // Payment-specific fields
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();

            // Status: 1 = active, 0 = inactive
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_options');
    }
};
