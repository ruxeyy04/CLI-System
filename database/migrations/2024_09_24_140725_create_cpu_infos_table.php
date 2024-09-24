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
        Schema::create('cpu_infos', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->string('brand')->nullable();
            $table->string('arch')->nullable();
            $table->string('bits')->nullable();
            $table->string('cores')->nullable();
            $table->string('threads')->nullable();
            $table->string('frequency')->nullable();
            $table->string('base_speed')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('computer_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpu_infos');
    }
};
