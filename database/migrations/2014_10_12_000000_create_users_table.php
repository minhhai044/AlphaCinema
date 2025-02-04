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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('avatar')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->boolean('gender')->nullable()->comment("0 : Nam , 1 : Nữ");
            $table->date('birthday')->nullable();
            // $table->string('service_id')->nullable(); // ?
            // $table->string('service_name')->nullable(); // ?
            $table->boolean('type_user')->default(0)->comment("0 : UserMember , 1 : Admin");
            $table->integer('transaction_id')->default(0); // ?
            $table->integer('cinema_id')->default(0); // ?
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
