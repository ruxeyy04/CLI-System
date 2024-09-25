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
        Schema::create('disk_infos', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->string('volume_label')->nullable();
            $table->string('mountpoint', 10)->nullable();
            $table->string('total', 20)->nullable();
            $table->string('used', 20)->nullable();
            $table->string('free', 20)->nullable();
            $table->string('health')->nullable();
            $table->string('temperature', 20)->nullable();
            $table->string('drive_type')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->dateTime('ejected_on')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('computer_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disk_infos');
    }
};
