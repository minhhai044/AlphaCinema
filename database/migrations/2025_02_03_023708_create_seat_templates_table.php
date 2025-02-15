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
        Schema::create('seat_templates', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('matrix');
            $table->string('name')->unique();
            $table->json('seat_structure')->nullable();
            $table->integer('row_regular')->nullable();
            $table->integer('row_vip')->nullable();
            $table->integer('row_double')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(0)->comment("0 : Ngừng hoạt động , 1 : Hoạt động");
            $table->boolean('is_publish')->default(0)->comment("0 : chưa xuất bản , 1 : Đã xuất bản");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_templates');
    }
};
