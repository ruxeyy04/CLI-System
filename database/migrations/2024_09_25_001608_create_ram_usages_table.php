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
        Schema::create('ram_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ram_id')->nullable()->constrained('ram_infos')->onDelete('cascade');
            $table->decimal('usage', 10, 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ram_usages');
    }
};
