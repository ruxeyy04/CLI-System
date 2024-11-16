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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Notification title
            $table->text('message'); // Notification message
            $table->string('type')->nullable(); // Type of notification (e.g., "info", "warning", "error")
            $table->boolean('is_read')->default(false); // Read status
            $table->string('device_id'); // Foreign key to computer_devices
            $table->foreign('device_id')->references('id')->on('computer_devices')->onDelete('cascade');
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
