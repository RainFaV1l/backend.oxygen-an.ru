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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('material')->nullable();
            $table->unsignedFloat('price')->default(0);
            $table->string('preview_image_path');
            $table->string('size_image_path');
            $table->foreignId('color_id')->nullable()->constrained('colors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
