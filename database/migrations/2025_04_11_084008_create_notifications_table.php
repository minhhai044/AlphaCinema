<?php

use App\Models\Ticket;
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Ticket::class)->nullable()->constrained();
            // $table->foreignIdFor(Feedback::class)->nullable()->constrained();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('link')->nullable()->comment('link đến trang chi tiết');
            $table->boolean('status')->default(0)->comment('0: chưa đọc, 1: đã đọc');
            $table->string('type')->nullable()->comment('ticket, contact, feedback, system...');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
