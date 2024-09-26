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
        Schema::create('input_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('description');
            $table->string('input_id');
            $table->string('input_status');
            $table->string('physical_status')->default('Good')->nullable();
            $table->string('note')->nullable();
            $table->dateTime('note_added')->nullable();
            $table->dateTime('removed_on')->nullable();
            $table->string('creation_class_name');
            $table->string('device_type');
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('computer_devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_devices');
    }
};
