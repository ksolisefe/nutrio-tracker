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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->integer('fdc_id')->unique();
            $table->string('name')->nullable();
            $table->string('food_type')->nullable();
            $table->decimal('calories', 8, 2)->nullable();
            $table->decimal('protein', 8, 2)->nullable();
            $table->decimal('carbohydrates', 8, 2)->nullable();
            $table->decimal('fat', 8, 2)->nullable();
            $table->string('brand_owner')->nullable();
            $table->string('category')->index()->nullable();
            $table->string('gtin_upc')->nullable();
            $table->decimal('serving_size', 8, 2)->nullable();
            $table->string('serving_size_unit')->nullable();
            $table->decimal('fibre', 8, 2)->nullable();
            $table->decimal('sodium', 8, 2)->nullable();
            $table->decimal('sugars', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
