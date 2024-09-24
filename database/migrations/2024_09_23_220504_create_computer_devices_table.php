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
        Schema::create('computer_devices', function (Blueprint $table) {
            // Using string for id to make it a VARCHAR
            $table->string('id')->primary();
            $table->string('device_name');
            $table->string('serial_number');
            $table->string('patch_id')->nullable();
            $table->string('token')->nullable();
            $table->foreignId('laboratory_id')->nullable()->constrained('laboratories')->onDelete('set null');
            $table->dateTime('patched_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computer_devices');
    }
};
