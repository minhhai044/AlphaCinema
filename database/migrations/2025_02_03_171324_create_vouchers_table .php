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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->boolean('type_discount')->default(0)->comment("0:VND, 1: %");
            $table->decimal('discount', 10, 0)->default(0);
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('limit')->default(0);
            $table->bigInteger('limit_by_user')->default(0);
            $table->boolean('is_active')->default(1)->comment("0 : Ngừng hoạt động , 1 : Hoạt động");
            $table->boolean('type_voucher')->default(0)->comment("0: voucher seat, 1: voucher food and combo");            $table->timestamps();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
