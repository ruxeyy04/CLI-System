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
        Schema::create('ram_infos', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->string('total_ram')->nullable();
            $table->string('used')->nullable();
            $table->string('available')->nullable();
            $table->string('speed')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('computer_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ram_infos');
    }
};
