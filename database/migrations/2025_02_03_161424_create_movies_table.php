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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->nullable();
            $table->string('category')->nullable();;
            $table->string('img_thumbnail')->nullable();;
            $table->text('description')->nullable();;
            $table->string('director')->nullable()->comment('Tác giả phim');
            $table->string('duration')->nullable()->comment('Diễn viên phim');
            $table->decimal('rating', 10, 1)->default(0)->comment('Đánh giá phim');
            $table->string('release_date')->comment('Ngày ra mắt');
            $table->string('end_date')->comment('Ngày kết thúc');
            $table->string('trailer_url')->nullable()->comment('Link trailer');
            $table->decimal('surcharge', 10, 0)->default(0)->comment('Phụ thu');
            $table->json('movie_versions')->nullable();
            $table->json('movie_genres')->nullable();
            $table->boolean('is_active')->default(0)->comment("0 : Ngừng hoạt động , 1 : Hoạt động");
            $table->boolean('is_hot')->default(0)->comment("0 : Không hot, 1 : Có hot");
            $table->boolean('is_special')->default(0)->comment("0 : Không đặc biệt, 1 : Đặc biệt");
            $table->boolean('is_publish')->default(0)->comment("0 : Ẩn , 1 : Hiện");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
