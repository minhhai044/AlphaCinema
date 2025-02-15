<?php

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Day;
use App\Models\Movie;
use App\Models\Room;
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
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->constrained();
            $table->foreignIdFor(Movie::class)->constrained();
            $table->foreignIdFor(Day::class)->constrained();
            $table->foreignIdFor(Cinema::class)->constrained();
            $table->foreignIdFor(Room::class)->constrained();
            $table->json('seat_structure');
            $table->string('slug')->nullable();
            $table->string('format')->nullable();
            $table->date('date')->comment('Ngày chiếu');
            $table->time('start_time')->comment('Giờ bắt đầu');
            $table->time('end_time')->comment('Giờ kết thúc');
            $table->decimal('price_special', 10, 0)->comment('Giá suất chiếu đặc biệt');
            $table->text('description_special')->nullable();
            $table->boolean('status_special')->default(0)->comment('0 : Không đặc biệt, 1 : Đặc biệt');
            $table->boolean('is_active')->default(0)->comment("0 : Ngừng hoạt động , 1 : Hoạt động");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
