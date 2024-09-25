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
        Schema::create('gpu_infos', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->string('brand')->nullable();
            $table->string('temp')->nullable();
            $table->string('usage')->nullable();
            $table->string('memory')->nullable();
            $table->string('power')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('computer_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpu_infos');
    }
};
