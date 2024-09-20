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
        Schema::create('partitions_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_entity_id')->constrained('layout_entities');
            $table->string('x-position');
            $table->string('height');         
            $table->string('width');         
            $table->string('y-position');         
               $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partitions_tables');
    }
};
