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
        Schema::create('collection_banner', function (Blueprint $table) {
            $table->id();
               $table->string('title');
            $table->string('heading');
            $table->string('button_url');
            $table->string('button_text');
            $table->string('sale_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_banner');
    }
};
