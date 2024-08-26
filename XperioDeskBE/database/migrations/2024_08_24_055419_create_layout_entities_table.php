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
        Schema::create('layout_entities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_id')->constrained('layouts');
            $table->enum('type',['Seat','Cabin','Conference','Partition']);
            $table->string('x-position');
            $table->string('y-position');
            $table->string('rotation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layout_entities');
    }
};
