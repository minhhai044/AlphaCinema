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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên mức rank');
            $table->decimal('total_spent', 15, 0)->comment('Tổng số tiền đã tiêu');
            $table->decimal('ticket_percentage', 5, 0)->comment('Phần trăm giảm giá vé');
            $table->decimal('food_percentage', 5, 0)->comment('Phần trăm giảm giá đồ ăn');
            $table->boolean('is_default')->default(0)->comment('Mức rank mặc định'); // ?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};
