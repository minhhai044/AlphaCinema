<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('img_thumbnail')->nullable();
            $table->decimal('price',10,0)->default(0);
            $table->decimal('price_sale',10,0)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1)->comment("0 : Ngừng hoạt động , 1 : Hoạt động");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};
