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
        Schema::table('products', function (Blueprint $table) {
              $table->foreignId('tag_id')
                  ->nullable() // Make it nullable if a product doesn't always need a tag
                  ->constrained('tags') // Links to the 'id' column of the 'tags' table
                  ->onDelete('set null') // If a tag is deleted, set product's tag_id to null
                  ->after('category_id'); // Position it after category_id (or wherever you prefer)
        });
      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
