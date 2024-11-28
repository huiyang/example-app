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
        Schema::create('related', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('example_related', function(Blueprint $table) {
            $table->id();
            $table->foreignId('example_id')->constrained('example_models');
            $table->foreignId('related_id')->constrained();
            $table->string('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related');
        Schema::dropIfExists('example_related');
    }
};
