<?php

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Cinema::class)->constrained();
            $table->foreignIdFor(Room::class)->constrained();
            $table->foreignIdFor(Movie::class)->constrained();
            $table->foreignIdFor(Showtime::class)->constrained();
            $table->string('code')->unique();
            $table->string('voucher_code')->nullable();
            $table->decimal('voucher_discount', 10, 0)->default(0);
            $table->decimal('point_use', 10, 0)->default(0);
            $table->decimal('point_discount', 10, 0)->default(0);
            $table->string('payment_name')->nullable();
            $table->json('ticket_seats')->nullable();
            $table->json('ticket_combos')->nullable();
            $table->json('ticket_foods')->nullable();
            $table->string('staff')->nullable();
            $table->unsignedBigInteger('total_price')->default(0);
            $table->datetime('expiry')->nullable();
            $table->enum('status', ['pending', 'confirmed'])->default('pending')->comment('Đang xử lý, Đã xác nhận');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
