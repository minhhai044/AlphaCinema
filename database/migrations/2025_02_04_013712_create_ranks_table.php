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
            $table->unsignedBigInteger('total_spent')->unique()->comment('Tổng số tiền đã tiêu');
            $table->integer('ticket_percentage')->comment('Phần trăm giảm giá vé');
            $table->integer('combo_percentage')->comment('Phần trăm giảm giá đồ ăn');
            $table->integer('feedback_percentage')->comment('Phần trăm feedback');
            $table->boolean('is_default')->default(false)->comment('Mức rank mặc định'); // ?
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
