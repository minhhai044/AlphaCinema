<?php

use App\Models\Food;
use App\Models\Combo;
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
        Schema::create('combo_food', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Combo::class)->constrained();
            $table->foreignIdFor(Food::class)->constrained();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_food');
    }
};
