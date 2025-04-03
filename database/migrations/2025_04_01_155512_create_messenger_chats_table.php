<?php

use App\Models\RoomChat;
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
        Schema::create('messenger_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RoomChat::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('image')->nullable();
            $table->text('messenge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messenger_chats');
    }
};
