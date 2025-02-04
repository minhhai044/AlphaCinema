<?php

use App\Models\Combo;
use App\Models\Food;
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
        Schema::create('combo_food', function (Blueprint $table) {
           $table->foreignIdFor(Combo::class)->constrained();
           $table->foreignIdFor(Food::class)->constrained();
           $table->bigInteger('quantity')->default(0);
           $table->primary(['combo_id', 'food_id']);
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
