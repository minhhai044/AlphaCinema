<?php

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Type_room;
use App\Models\Seat_template;
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Branch::class)->constrained();
            $table->foreignIdFor(Cinema::class)->constrained();
            $table->foreignIdFor(Type_room::class)->constrained();
            $table->foreignIdFor(Seat_template::class)->constrained();
            $table->string('name')->unique();
            $table->json('seat_structures');
            $table->boolean('is_active')->default(0)->comment("0 : Ngừng hoạt động , 1 : Hoạt động");
            $table->boolean('is_publish')->default(0)->comment("0 : Ẩn , 1 : Hiện");
            $table->timestamps();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
