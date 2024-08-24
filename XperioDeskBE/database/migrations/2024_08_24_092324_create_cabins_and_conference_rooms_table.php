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
        Schema::create('cabins_and_conference_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_entity_id')->constrained('layout_entities');
            $table->enum('type',['cabin','conference_room']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabins_and_conference_rooms');
    }
};
