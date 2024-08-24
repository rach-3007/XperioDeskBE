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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('seat_number');
            $table->foreignId('module_id')->constrained('modules');
            $table->foreignId('booked_by_user_id')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->foreignId('layout_entitiesId')->constrained('layout_entities');
            $table->enum('status',['available','booked','permanently_booked'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
