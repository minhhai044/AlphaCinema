<?php

use App\Models\User;
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
        // Schema::create('point_histories', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignIdFor(User::class)->constrained();
        //     $table->bigInteger('point')->comment('Số điểm');
        //     $table->string('type')->comment('Loại điểm');
        //     $table->text('description')->comment('Mô tả');
        //     $table->date('expiry_date')->nullable()->comment('Ngày hết hạn');
        //     $table->boolean('processed')->default(0)->comment('Đã xử lý');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
